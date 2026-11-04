<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ChangePanelForm extends Model {

    public $panelId;
    public $responsiblePersonId;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            // name, email, subject and body are required
            [['panelId', 'responsiblePersonId'], 'required'],
            [['panelId', 'responsiblePersonId'], 'integer'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels() {
        return [
            'panelId' => Yii::t('app', 'Panel'),
            'responsiblePersonId' => Yii::t('app', 'ผู้รับผิดชอบ'),
        ];
    }

}
