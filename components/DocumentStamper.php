<?php

namespace app\components;

use Yii;
use Mpdf\Mpdf;
use Phpdocx\Create\CreateDocx;

/**
 * Applies an electronic-signature "stamp" (signer name + date + statement)
 * onto researcher documents (CV / training certificates).
 *
 * - PDF source            -> overlay the stamp on the last page (FPDI import).
 * - .doc / .docx source   -> convert to PDF (LibreOffice via phpdocx), stamp.
 * - other (images, etc.)  -> generate a one-page attestation cover sheet PDF.
 *
 * The original file is left intact; a new stamped file is written and its
 * basename returned so the caller can update cv_file / file.
 */
class DocumentStamper {

    /** mPDF font directory + data for Thai rendering (mirrors ReportController). */
    private static function fontOptions() {
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        return [
            'mode' => 'utf-8',
            'fontDir' => array_merge($fontDirs, [Yii::getAlias('@app/web/fonts')]),
            'fontdata' => $fontData + [
                'thsarabun' => [
                    'R' => 'THSarabunNew.ttf',
                    'B' => 'THSarabunNew-Bold.ttf',
                    'I' => 'THSarabunNew-Italic.ttf',
                    'BI' => 'THSarabunNew-BoldItalic.ttf',
                ],
            ],
            'default_font' => 'thsarabun',
        ];
    }

    /**
     * Returns the HTML block used as the visible stamp / attestation text.
     *
     * @param array $lines associative info: signerName, signedAt, statement
     */
    private static function stampHtml($lines) {
        $signer = isset($lines['signerName']) ? htmlspecialchars($lines['signerName'], ENT_QUOTES, 'UTF-8') : '';
        $signedAt = isset($lines['signedAt']) ? htmlspecialchars($lines['signedAt'], ENT_QUOTES, 'UTF-8') : '';
        $updatedAt = isset($lines['updatedAt']) ? htmlspecialchars($lines['updatedAt'], ENT_QUOTES, 'UTF-8') : '';
        $statement = isset($lines['statement']) ? htmlspecialchars($lines['statement'], ENT_QUOTES, 'UTF-8') : '';
        $title = Yii::t('app', 'ลงนามอิเล็กทรอนิกส์');
        $byLabel = Yii::t('app', 'ผู้ลงนาม');
        $signedLabel = Yii::t('app', 'วันที่ลงนาม');
        // The update-date line is CV-specific (training docs have no CV-update date).
        $updatedLine = $updatedAt !== ''
                ? Yii::t('app', 'วันที่ปรับปรุงข้อมูลล่าสุด') . ": {$updatedAt}<br>"
                : '';
        return <<<HTML
<div style="border:1px solid #2e7d32; padding:6px 10px; font-family: thsarabun; font-size:12px; color:#1b5e20; background:rgba(232,245,233,0.45);">
    <strong>{$title}</strong><br>
    {$byLabel}: {$signer}<br>
    {$updatedLine}{$signedLabel}: {$signedAt}<br>
    <span style="font-size:11px;">{$statement}</span>
</div>
HTML;
    }

    /**
     * Stamp an existing PDF: import all pages (preserving each page's
     * orientation) and overlay the stamp at the bottom-left of every page.
     *
     * @param string $srcPath absolute source PDF path
     * @param string $destPath absolute destination PDF path
     * @param array $lines stamp info (signerName, signedAt, statement)
     * @return bool
     */
    public static function stampPdf($srcPath, $destPath, $lines) {
        $opts = self::fontOptions();
        $mpdf = new Mpdf([
            'mode' => $opts['mode'],
            'fontDir' => $opts['fontDir'],
            'fontdata' => $opts['fontdata'],
            'default_font' => $opts['default_font'],
            'tempDir' => Yii::getAlias('@app/runtime/mpdf'),
        ]);
        $pageCount = $mpdf->setSourceFile($srcPath);
        for ($i = 1; $i <= $pageCount; $i++) {
            $tpl = $mpdf->importPage($i);
            $size = $mpdf->getTemplateSize($tpl);
            // mPDF _setPageSize() swaps width/height for 'L'. Always pass the
            // format as portrait-normalized [short, long] and let the orientation
            // flag rotate — passing landscape dims AND 'L' double-swaps to portrait.
            $orientation = ($size['width'] > $size['height']) ? 'L' : 'P';
            $fw = min($size['width'], $size['height']);
            $fh = max($size['width'], $size['height']);
            $mpdf->AddPageByArray([
                'orientation' => $orientation,
                'newformat' => [$fw, $fh],
            ]);
            $mpdf->useTemplate($tpl);
            // Stamp EVERY page at a fixed position (no page-flow impact). Use the
            // page's actual rendered dimensions ($mpdf->w/$mpdf->h, already
            // orientation-correct) so the box anchors to the bottom-left.
            $margin = 8; // mm
            $boxW = min(110, $mpdf->w - 2 * $margin);
            $boxH = 28; // mm; overflow 'auto' shrinks/grows to fit
            $x = $margin;
            $y = $mpdf->h - $boxH - $margin;
            if ($y < 0) {
                $y = 0;
            }
            $mpdf->WriteFixedPosHTML(self::stampHtml($lines), $x, $y, $boxW, $boxH, 'auto');
        }
        $mpdf->Output($destPath, \Mpdf\Output\Destination::FILE);
        return file_exists($destPath);
    }

    /**
     * Generate a standalone attestation cover-sheet PDF (for non-PDF docs).
     *
     * @param string $destPath absolute destination PDF path
     * @param array $lines stamp info + 'fileName'
     * @return bool
     */
    public static function makeCoverSheet($destPath, $lines) {
        $opts = self::fontOptions();
        $mpdf = new Mpdf([
            'mode' => $opts['mode'],
            'fontDir' => $opts['fontDir'],
            'fontdata' => $opts['fontdata'],
            'default_font' => $opts['default_font'],
            'tempDir' => Yii::getAlias('@app/runtime/mpdf'),
        ]);
        $fileName = isset($lines['fileName']) ? htmlspecialchars($lines['fileName'], ENT_QUOTES, 'UTF-8') : '';
        $heading = Yii::t('app', 'ใบรับรองการลงนามอิเล็กทรอนิกส์');
        $fileLabel = Yii::t('app', 'เอกสารแนบ');
        $html = '<div style="font-family: thsarabun; font-size:16px;">'
                . '<h2 style="text-align:center;">' . $heading . '</h2>'
                . '<p>' . $fileLabel . ': ' . $fileName . '</p>'
                . self::stampHtml($lines)
                . '</div>';
        $mpdf->WriteHTML($html);
        $mpdf->Output($destPath, \Mpdf\Output\Destination::FILE);
        return file_exists($destPath);
    }

    /**
     * Stamp a document by extension, writing the stamped output next to the
     * original (a new uniqid name). Returns details for the caller.
     *
     * @param string $dir absolute directory holding the file
     * @param string $fileName current file basename
     * @param array $lines stamp info (signerName, signedAt, statement)
     * @return array{success:bool, fileName:string|null, stampType:int|null}
     *   fileName is the new stamped basename (or null on failure);
     *   stampType is PersonDocumentAudit::STAMP_PDF_OVERLAY|STAMP_COVERSHEET.
     */
    public static function stampFile($dir, $fileName, $lines) {
        $fail = ['success' => false, 'fileName' => null, 'stampType' => null];
        if (empty($fileName)) {
            return $fail;
        }
        $srcPath = rtrim($dir, '/') . '/' . $fileName;
        if (!file_exists($srcPath)) {
            return $fail;
        }
        @mkdir(Yii::getAlias('@app/runtime/mpdf'), 0777, true);
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $lines['fileName'] = isset($lines['fileName']) ? $lines['fileName'] : $fileName;

        try {
            if ($ext === 'pdf') {
                $newName = uniqid('signed-') . '.pdf';
                $destPath = rtrim($dir, '/') . '/' . $newName;
                if (self::stampPdf($srcPath, $destPath, $lines)) {
                    return ['success' => true, 'fileName' => $newName, 'stampType' => 1]; // STAMP_PDF_OVERLAY
                }
                return $fail;
            } else if (in_array($ext, ['doc', 'docx'])) {
                // Convert to PDF first (LibreOffice via phpdocx), then stamp.
                $pdfTmp = rtrim($dir, '/') . '/' . uniqid('conv-') . '.pdf';
                $docx = new CreateDocx();
                $docx->transformDocument($srcPath, $pdfTmp, 'libreoffice', ['homeFolder' => Yii::getAlias('@app')]);
                if (!file_exists($pdfTmp)) {
                    // Conversion failed -> fall back to a cover sheet.
                    $newName = uniqid('signed-') . '.pdf';
                    $destPath = rtrim($dir, '/') . '/' . $newName;
                    if (self::makeCoverSheet($destPath, $lines)) {
                        return ['success' => true, 'fileName' => $newName, 'stampType' => 2]; // COVERSHEET
                    }
                    return $fail;
                }
                $newName = uniqid('signed-') . '.pdf';
                $destPath = rtrim($dir, '/') . '/' . $newName;
                $ok = self::stampPdf($pdfTmp, $destPath, $lines);
                @unlink($pdfTmp);
                if ($ok) {
                    return ['success' => true, 'fileName' => $newName, 'stampType' => 1];
                }
                return $fail;
            } else {
                // Images / unsupported -> attestation cover sheet alongside original.
                $newName = uniqid('signed-') . '.pdf';
                $destPath = rtrim($dir, '/') . '/' . $newName;
                if (self::makeCoverSheet($destPath, $lines)) {
                    return ['success' => true, 'fileName' => $newName, 'stampType' => 2];
                }
                return $fail;
            }
        } catch (\Throwable $e) {
            Yii::error('DocumentStamper failed: ' . $e->getMessage(), __METHOD__);
            return $fail;
        }
    }

}
