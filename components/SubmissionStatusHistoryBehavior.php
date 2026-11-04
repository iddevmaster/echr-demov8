<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;

use yii\db\ActiveRecord;
use yii\base\Behavior;
use app\models\SubmissionStatusHistory;
use yii\helpers\ArrayHelper;

class SubmissionStatusHistoryBehavior extends Behavior {

    // ...

    public function events() {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
        ];
    }

    public function afterInsert($event) {
//        \yii\helpers\VarDumper::dump($event, 10, TRUE);
        self::saveHistory($event->sender);
    }

    public function afterUpdate($event) {
//        \yii\helpers\VarDumper::dump($event, 10, TRUE);
        if (ArrayHelper::keyExists('status', $event->changedAttributes)) {
            self::saveHistory($event->sender);
        }
    }
    
    private static function saveHistory($model) {
        $history = new SubmissionStatusHistory();
        $history->submission_id = $model->id;
        $history->status = $model->status;
        if(isset($model->remark_checkdoc_staff)){
            $history->remark_checkdoc_staff = $model->remark_checkdoc_staff;
        }
        $history->save(FALSE);
    }

}
