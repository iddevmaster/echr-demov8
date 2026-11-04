<?php
namespace app\components;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Writer\PngWriter;
use Yii;

class QrCodeHelper
{
    /**
     * สร้าง QR code เป็นไฟล์ PNG แล้วคืน path เต็มของไฟล์
     * เหมาะกับการนำไปฝังใน PHPDocx
     */
    public static function generateFile(string $data, int $size = 300): string
    {
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($data)
            ->encoding(new Encoding('UTF-8'))
            // High = ทนต่อความเสียหาย/พิมพ์เบลอได้ดี เหมาะกับเอกสารราชการ
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size($size)
            ->margin(10)
            ->build();

        // เก็บไว้ใน runtime เพื่อให้ลบทิ้งภายหลังได้ง่าย
        $dir = Yii::getAlias('@runtime/qrcode');
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        $path = $dir . '/' . md5($data) . '.png';
        $result->saveToFile($path);

        return $path;
    }

    /** คืนเป็น data URI สำหรับแสดงในหน้าเว็บ <img src="..."> */
    public static function generateDataUri(string $data, int $size = 300): string
    {
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($data)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size($size)
            ->margin(10)
            ->build();

        return $result->getDataUri();
    }
}