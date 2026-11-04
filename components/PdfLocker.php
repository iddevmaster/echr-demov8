<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\Exception;

class PdfLocker extends Component
{
    public function lock($pdfPath)
    {
        if (!file_exists($pdfPath)) {
            throw new Exception("PDF not found: {$pdfPath}");
        }

        $ownerPass = bin2hex(random_bytes(24));
        $tmpPath = $pdfPath . '.locked.tmp';

        $cmd = sprintf(
            'qpdf --encrypt %s %s 256 --print=full --modify=none --extract=n --annotate=n -- %s %s 2>&1',
            escapeshellarg(''),
            escapeshellarg($ownerPass),
            escapeshellarg($pdfPath),
            escapeshellarg($tmpPath)
        );

        exec($cmd, $output, $rc);
        if ($rc !== 0 && $rc !== 3) {
            @unlink($tmpPath);
            Yii::error("qpdf failed ({$rc}) for {$pdfPath}: " . implode("\n", $output), __METHOD__);
            return false;
        }

        if (!@rename($tmpPath, $pdfPath)) {
            @unlink($tmpPath);
            Yii::error("Failed to replace {$pdfPath} with locked tmp file", __METHOD__);
            return false;
        }
        return true;
    }
}
