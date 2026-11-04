<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class ForgetPasswordForm extends Model {

    public $email;
//    public $idcardNo;
    public $person;
    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            // username and password are both required
            [['email'], 'required'],
            // rememberMe must be a boolean value
            ['email', 'email'],
            ['email', 'validateIdcardNo'],
        ];
    }

    public function attributeLabels() {
        return [
            'email' => Yii::t('app', 'อีเมลล์'),
            'idcardNo' => Yii::t('app', 'เลขบัตรประชาชน'),
        ];
    }
    
    public function validateIdcardNo($attribute, $params) {
        $this->person = Person::find()->joinWith(['user'])->isDeleted(FALSE)->active(TRUE)->email($this->email)->one();
        if (!isset($this->person)) {
            $this->addError($attribute, \Yii::t('app', 'ไม่พบข้อมูลผู้ใช้งานที่ระบุ หรืออีเมลล์ไม่ตรงกับที่ลงทะเบียนไว้'));
        }
    }
}
