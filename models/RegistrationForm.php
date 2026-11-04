<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class RegistrationForm extends Model {

    public $first_name;
    public $last_name;
    public $tel;
    public $email;
    public $companyId;
    public $companyName;
    public $username;
    public $password;
    public $password_repeat;
    public $user_id;
    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            // username and password are both required
            [['username'], 'required'],
            [['user_id'], 'integer'],
            ['username', 'match', 'pattern' => '/^[-a-zA-Z0-9_\.@]+$/'],
            ['username', 'filter', 'filter' => 'trim'],
            [
                'username',
                'unique',
                'targetClass' => User::className(),
                'filter' => function($query) {
                    return $query->andWhere(['not', ['status' => User::STATUS_DELETED]])
                                    ->andFilterWhere(['not', ['id' => $this->user_id]]);
                    //['and', ['status'=> User::STATUS_ACTIVE], ['not', ['id' => $this->user_id]]], //['status'=> User::STATUS_ACTIVE, ['not', ['id' => $this->user_id]]]
                },
//                'message' => Yii::t('user', 'This username has already been taken')
            ],
//            [
//                'companyId',
//                'unique',
//                'targetClass' => UserProfile::className(),
//                'filter' => ['and', ['deleted'=> 0], ['not', ['company_id' => $this->companyId]]], //['status'=> User::STATUS_ACTIVE, ['not', ['id' => $this->user_id]]],
////                'message' => Yii::t('user', 'This username has already been taken')
//            ],
            [['password', 'password_repeat'], 'required', 'on' => ['register', 'reset-password']],
            ['password', 'string', 'min' => 6],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'skipOnEmpty' => false, 'message' => 'ยืนยันรหัสผ่านไม่ตรงกับรหัสผ่าน'],
//            [['verifyCode'], 'captcha', 'on' => ['register']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'username' => Yii::t('app', 'ชื่อผู้ใช้ (username)'),
            'password' => Yii::t('app', 'รหัสผ่าน (password)'),
            'password_repeat' => Yii::t('app', 'ยืนยันรหัสผ่าน'),
            'first_name' => Yii::t('app', 'ชื่อ'),
            'last_name' => Yii::t('app', 'นามสกุล'),
            'tel' => Yii::t('app', 'เบอร์โทร'),
            'email' => Yii::t('app', 'อีเมล์'),
//            'verifyCode' => Yii::t('app', 'โปรดพิมพ์อักษรที่ท่านเห็นข้างล่างนี้ลงในช่องแล้วกด ถัดไป'),
        ];
    }

}
