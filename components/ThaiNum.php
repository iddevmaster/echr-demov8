<?php
namespace app\components;
/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class ThaiNum extends \yii\base\Component {

    private $g_digits = [ "ศูนย์", "หนึ่ง", "สอง", "สาม", "สี่", "ห้า", "หก", "เจ็ด", "แปด", "เก้า"];

    public function init() {
        
    }

    public function sayMoney($number) {
        $prefix = '';
        if ($number < 0) {
            $prefix = 'ลบ';
            $number = -$number;
        }
//        echo "number=$number";
        $number = round($number, 2);
//        echo "number=$number";
        $text = '';
        $baht = intval($number);
//        echo "baht=$baht";
//        echo "test=".($number - intval($number));
        $satang = number_format(($number - $baht) * 100, 0, ".", "");//intval(round(($number - intval($number)), 2) * 100);
//        echo "satang=$satang";
        $text = $this->sayInt($baht) . 'บาท';
        if ($satang > 0) {
            $text .= $this->sayInt($satang) . 'สตางค์';
        } else {
            $text .= 'ถ้วน';
        }
        return $prefix . $text;
    }

    private function sayPosition($n) {
        $np = $n % 6;
        if ($np == 1)
            return 'สิบ';
        if ($np == 2)
            return 'ร้อย';
        if ($np == 3)
            return 'พัน';
        if ($np == 4)
            return 'หมื่น';
        if ($np == 5)
            return 'แสน';
        return "";
    }

    private function sayDigit($number) {
//        echo "digit=[$number=>".$this->g_digits[$number]."]";
        return $this->g_digits[$number];
    }

    private function sayPlace($number, $position, $digitCount) {
        if ($digitCount == 1)
            return $this->sayDigit($number);
        if ($number == 0)
            return "";
        if ($position % 6 == 0 && $position + 1 < $digitCount && $number == 1)
            return 'เอ็ด';
        if ($position % 6 == 1 && $number == 1)
            return 'สิบ';
        if ($position % 6 == 1 && $number == 2)
            return 'ยี่สิบ';
//        echo "position=".$this->sayPosition($position);
        return $this->sayDigit($number) . $this->sayPosition($position);
    }

    private function sayInt($integer) {
        $text = "";
        if ($integer == 0)
            return 'ศูนย์';
        $minus = $integer < 0;
        $integer = abs($integer);
//        echo "integer=$integer";
        $digitCount = strlen($integer); //intval(log($integer)) + 1;
//        echo "digitCount=$digitCount";
        $position = 0;
        $text = $this->doEachDigit($integer, $position, $digitCount) . $text;
        $integer = intval($integer / 10);
        $position++;
        while ($position < $digitCount) {
            $text = $this->doEachDigit($integer, $position, $digitCount) . $text;
            $integer = intval($integer / 10);
            $position++;
        }
        if ($minus)
            $text = 'ลบ' . $text;
        return $text;
    }

    private function doEachDigit($integer, $position, $digitCount) {
        $value = $integer % 10;
//        echo "value=$value";
        $str = $this->sayPlace($value, $position, $digitCount);
//        echo "str=$str";
        if ($position % 6 == 0) {
            $millionCount = intval($position / 6);
            for ($i = 0; $i < $millionCount; $i++) {
                $str .= 'ล้าน';
            }
        }
        return $str;
    }

}