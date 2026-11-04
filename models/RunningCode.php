<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "running_code".
 *
 * @property int $id
 * @property string $prefix
 * @property int $number
 * @property string $created_at
 * @property string $updated_at
 */
class RunningCode extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'running_code';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['number'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['prefix'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'prefix' => Yii::t('app', 'Prefix'),
            'number' => Yii::t('app', 'Number'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => function() {
                    return date('Y-m-d H:i:s');
                },
            ],
        ];
    }

    /**
     * @inheritdoc
     * @return RunningCodeQuery the active query used by this AR class.
     */
    public static function find() {
        return new RunningCodeQuery(get_called_class());
    }

    public static function getRunningNo($prefix, $digitNumbers = NULL)
    {
        $dc = RunningCode::find()->where([
            'prefix' => $prefix
        ])->one();
        $runNo = 1;
        //        $code = '';
        if (isset($dc)) {
            $runNo = ++$dc->number;
            //            $code = $prefix . str_pad($runNo, $digitNumbers, '0', STR_PAD_LEFT);
        } else {
            $dc = new RunningCode();
            $dc->prefix = strval($prefix);
            //            $code = $prefix . str_pad($runNo, $digitNumbers, '0', STR_PAD_LEFT);
        }
        $dc->number = $runNo;
        if (!$dc->save()) {
            throw new \yii\web\HttpException(500, Yii::$app->util->errorSummary($dc));
        }
        return $runNo;
    }

    public static function generateCode($prefix, $digitNumbers) {
        $dc = RunningCode::find()->where([
                    'prefix' => $prefix
                ])->one();
        $runNo = 1;
        $code = '';
        if (isset($dc)) {
            $runNo = ++$dc->number;
            $code = $prefix . str_pad($runNo, $digitNumbers, '0', STR_PAD_LEFT);
        } else {
            $dc = new RunningCode();
            $dc->prefix = $prefix;
            $code = $prefix . str_pad($runNo, $digitNumbers, '0', STR_PAD_LEFT);
        }
        $dc->number = $runNo;
        $dc->save();
        return $code;
    }

}
