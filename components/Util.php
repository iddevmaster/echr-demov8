<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;

use Yii;
use yii\helpers\Html;
use yii\helpers\VarDumper;

class Util extends \yii\base\Component {

    public $thaiMonths =
    [
        1 => 'มกราคม',
        2 => 'กุมภาพันธ์',
        3 => 'มีนาคม',
        4 => 'เมษายน',
        5 => 'พฤษภาคม',
        6 => 'มิถุนายน',
        7 => 'กรกฎาคม',
        8 => 'สิงหาคม',
        9 => 'กันยายน',
        10 => 'ตุลาคม',
        11 => 'พฤศจิกายน',
        12 => 'ธันวาคม',
    ];

    public function getTotalMinutes(\DateInterval $int) {
//        \yii\helpers\VarDumper::dump($int->days, 10, TRUE);
//        exit;
        return ($int->days * 24 * 60) + ($int->h * 60) + $int->i;
    }

    public function getMeetingIntervalFormat(\DateInterval $int) {
        $mins = $this->getTotalMinutes($int);
        return $this->getHourMinuteFormat($mins);
    }

    public function getHourMinuteFormat($mins) {
        $h = floor($mins / 60);
        $m = $mins % 60;
        return str_pad($h, 2, '0', STR_PAD_LEFT) . " : " . str_pad($m, 2, '0', STR_PAD_LEFT);
    }

    public function booleanIconFormat($value) {
        return $value ? Html::tag('i', '', ['class' => 'icon md-check font-size-18 green-500']) : Html::tag('i', '', ['class' => 'icon md-close font-size-18 red-500']);
    }

    public function getEmptyControlClass($val) {
        return (empty($val) ? "empty" : "");
    }

    public function getFormControlClass($val) {
        return "form-control " . (empty($val) ? "empty" : "");
    }

    public function calculateAmountBfv($amount, $vatPercent) {
        return ($amount * 100 / (100 + $vatPercent));
    }

    public function errorSummary($model) {
        $message = '';
        if (count($model->errors) > 0)
            $message .= '<br /><ul>';
        foreach ($model->errors as $error) {
            $message .= '<li>' . $error[0] . '</li>';
        }
        if (count($model->errors) > 0)
            $message .= '</ul>';
        return $message;
    }

    public function toDateFormat($fromFormat, $toFormat, $dateString) {
        $dt = empty($dateString) ? NULL : \DateTime::createFromFormat($fromFormat, $dateString);
        return $dt !== FALSE && isset($dt) ? $dt->format($toFormat) : "";
    }

    public function getCheckBox($value) {
        if ($value) {
            return '<i class="icon md-check primary-500"></i>';
        }
        return '<i class="icon md-close red-500"></i>';
        ;
    }

    public function isBrowserLegacy() {
//        $browser = get_browser(NULL, TRUE);
        $bd = new BrowserDetection();
        $bd->detect();
//        echo $bd->detect()->getInfo();
//        \yii\helpers\VarDumper::dump($_SERVER['HTTP_USER_AGENT']);
        $browser = $bd->getBrowser();
        $version = intval($bd->getVersion());
        return $browser == "Google Chrome" && $version < 50;
//        return $browser['browser'] == "Chrome" && $browser['version'] < 50;
    }

    public function checkPermission($permission) {
        return \Yii::$app->user->can(\Yii::$app->id . ".{$permission}");
    }

    public function generateToken() {
        return Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function getLanguageItems() {
        return [
            'languages' => [
                 'en' => '<span class="flag-icon flag-icon-us"></span> English',
                'th' => '<span class="flag-icon flag-icon-th"></span> ไทย',
            ],
            'options' => ['encode' => false],
        ];
    }
    
     public function getGenerateCode() {

         $beYear = Yii::$app->util->getBEYearEng(new \DateTime());
        $runNo = \app\models\RunningCode::getRunningNo($beYear, 1);
        $padRunNo = str_pad($runNo, 5, '0', STR_PAD_LEFT);
        $code = "{$beYear}.{$padRunNo}";
        return $code;
    }

    public function getBEYear($date) {
        $y = $date->format('Y');
        return $y + 543;
    }
    public function getBEYearEng($date) {
        $y = $date->format('Y');
        return $y;
    }

    function execInBackground($cmd) {
        if (substr(php_uname(), 0, 7) == "Windows") {
            pclose(popen("start /B " . $cmd, "r"));
        } else {
            exec($cmd . " > /dev/null &");
        }
    }

    function getI18nAttribute($attr) {
        return $attr . \Yii::$app->params['i18nSuffixes'][\Yii::$app->language];
    }

    public function getYesNoLabels() {
        return [
            1 => \Yii::t('app', 'ใช่'),
            0 => \Yii::t('app', 'ไม่ใช่'),
        ];
    }

    public function getYesNoUnknownLabels() {
        return [
            1 => \Yii::t('app', 'ใช่'),
            0 => \Yii::t('app', 'ไม่ใช่'),
            2 => \Yii::t('app', 'ไม่ทราบผล'),
        ];
    }

    public function getMonthNames() {
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $d = new \DateTime(date('Y') . "-{$i}-01");
            $months[$i] = \Yii::$app->formatter->asDate($d->format('Y-m-d'), 'php:F');
        }
        return $months;
    }

    public function getSelectYears() {
        $startYear = 2010;
        $endYear = date('Y');
        $years = [];
        for ($i = $endYear; $i >= $startYear; $i--) {
            $years[$i] = $i;
        }
        return $years;
    }

    function standardDeviation($array) {

        $number_of_elements = count($array);

        $variance = 0.0;

// using array_sum() function to calculate mean

        $avg = array_sum($array) / $number_of_elements;

        foreach ($array as $i) {
            $variance += pow(($i - $avg), 2);
        }

        return (float) sqrt($variance / $number_of_elements);
    }

    public function mmmr($array, $output = 'mean') {
        if (!is_array($array)) {
            return FALSE;
        } else {
            switch ($output) {
                case 'mean':
                    $count = count($array);
                    $sum = array_sum($array);
                    $total = $sum / $count;
                    break;
                case 'median':
                    // VarDumper::dump($array, 10, true);
                    rsort($array);
                    // VarDumper::dump($array, 10, true);
                    $middle = round(count($array)/2, 0);
                    // VarDumper::dump($middle, 10, true);
                    $total = $array[$middle - 1];
                    // echo $total;
                    // exit;
                    break;
                case 'mode':
                    $v = array_count_values($array);
                    arsort($v);
                    foreach ($v as $k => $v) {
                        $total = $k;
                        break;
                    }
                    break;
                case 'range':
                    sort($array);
                    $sml = $array[0];
                    rsort($array);
                    $lrg = $array[0];
                    $total = $lrg - $sml;
                    break;
            }
            return $total;
        }
    }

    public function buildErrorForApi($errors) {
        $res = [];
        foreach ($errors as $field => $er) {
            $r = [
                'field' => $field,
                'messages' => $er
            ];
            $res[] = $r;
        }
        return $res;
    }

}

class BrowserDetection {

    private $_user_agent;
    private $_name;
    private $_version;
    private $_platform;
    private $_basic_browser = array(
        'Trident\/7.0' => 'Internet Explorer 11',
        'Beamrise' => 'Beamrise',
        'Opera' => 'Opera',
        'OPR' => 'Opera',
        'Shiira' => 'Shiira',
        'Chimera' => 'Chimera',
        'Phoenix' => 'Phoenix',
        'Firebird' => 'Firebird',
        'Camino' => 'Camino',
        'Netscape' => 'Netscape',
        'OmniWeb' => 'OmniWeb',
        'Konqueror' => 'Konqueror',
        'icab' => 'iCab',
        'Lynx' => 'Lynx',
        'Links' => 'Links',
        'hotjava' => 'HotJava',
        'amaya' => 'Amaya',
        'IBrowse' => 'IBrowse',
        'iTunes' => 'iTunes',
        'Silk' => 'Silk',
        'Dillo' => 'Dillo',
        'Maxthon' => 'Maxthon',
        'Arora' => 'Arora',
        'Galeon' => 'Galeon',
        'Iceape' => 'Iceape',
        'Iceweasel' => 'Iceweasel',
        'Midori' => 'Midori',
        'QupZilla' => 'QupZilla',
        'Namoroka' => 'Namoroka',
        'NetSurf' => 'NetSurf',
        'BOLT' => 'BOLT',
        'EudoraWeb' => 'EudoraWeb',
        'shadowfox' => 'ShadowFox',
        'Swiftfox' => 'Swiftfox',
        'Uzbl' => 'Uzbl',
        'UCBrowser' => 'UCBrowser',
        'Kindle' => 'Kindle',
        'wOSBrowser' => 'wOSBrowser',
        'Epiphany' => 'Epiphany',
        'SeaMonkey' => 'SeaMonkey',
        'Avant Browser' => 'Avant Browser',
        'Firefox' => 'Firefox',
        'Chrome' => 'Google Chrome',
        'MSIE' => 'Internet Explorer',
        'Internet Explorer' => 'Internet Explorer',
        'Safari' => 'Safari',
        'Mozilla' => 'Mozilla'
    );
    private $_basic_platform = array(
        'windows' => 'Windows',
        'iPad' => 'iPad',
        'iPod' => 'iPod',
        'iPhone' => 'iPhone',
        'mac' => 'Apple',
        'android' => 'Android',
        'linux' => 'Linux',
        'Nokia' => 'Nokia',
        'BlackBerry' => 'BlackBerry',
        'FreeBSD' => 'FreeBSD',
        'OpenBSD' => 'OpenBSD',
        'NetBSD' => 'NetBSD',
        'UNIX' => 'UNIX',
        'DragonFly' => 'DragonFlyBSD',
        'OpenSolaris' => 'OpenSolaris',
        'SunOS' => 'SunOS',
        'OS\/2' => 'OS/2',
        'BeOS' => 'BeOS',
        'win' => 'Windows',
        'Dillo' => 'Linux',
        'PalmOS' => 'PalmOS',
        'RebelMouse' => 'RebelMouse'
    );

    function __construct($ua = '') {
        if (empty($ua)) {
            $this->_user_agent = (!empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : getenv('HTTP_USER_AGENT'));
        } else {
            $this->_user_agent = $ua;
        }
    }

    function detect() {
        $this->detectBrowser();
        $this->detectPlatform();
        return $this;
    }

    function detectBrowser() {
        foreach ($this->_basic_browser as $pattern => $name) {
            if (preg_match("/" . $pattern . "/i", $this->_user_agent, $match)) {
                $this->_name = $name;
                // finally get the correct version number
                $known = array('Version', $pattern, 'other');
                $pattern_version = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
                if (!preg_match_all($pattern_version, $this->_user_agent, $matches)) {
                    // we have no matching number just continue
                }
                // see how many we have
                $i = count($matches['browser']);
                if ($i != 1) {
                    //we will have two since we are not using 'other' argument yet
                    //see if version is before or after the name
                    if (strripos($this->_user_agent, "Version") < strripos($this->_user_agent, $pattern)) {
                        @$this->_version = $matches['version'][0];
                    } else {
                        @$this->_version = $matches['version'][1];
                    }
                } else {
                    $this->_version = $matches['version'][0];
                }
                break;
            }
        }
    }

    function detectPlatform() {
        foreach ($this->_basic_platform as $key => $platform) {
            if (stripos($this->_user_agent, $key) !== false) {
                $this->_platform = $platform;
                break;
            }
        }
    }

    function getBrowser() {
        if (!empty($this->_name)) {
            return $this->_name;
        }
    }

    function getVersion() {
        return $this->_version;
    }

    function getPlatform() {
        if (!empty($this->_platform)) {
            return $this->_platform;
        }
    }

    function getUserAgent() {
        return $this->_user_agent;
    }

    function getInfo() {
        return "<strong>Browser Name:</strong> {$this->getBrowser()}<br/>\n" .
                "<strong>Browser Version:</strong> {$this->getVersion()}<br/>\n" .
                "<strong>Browser User Agent String:</strong> {$this->getUserAgent()}<br/>\n" .
                "<strong>Platform:</strong> {$this->getPlatform()}<br/>";
    }

}
