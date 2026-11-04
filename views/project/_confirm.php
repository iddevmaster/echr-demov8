<?php

use yii\helpers\Html;
use kartik\widgets\Select2;
use kartik\depdrop\DepDrop;
use yii\widgets\MaskedInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $personal app\models\RegisterTransaction */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col-md-6">
        <div class="panel panel-bordered panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">ข้อมูลงานวิจัย</h3>
            </div>
            <div class="panel-body">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td class="font-weight-900"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="font-weight-900"></td>
                            <td></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>
    <div class="col-md-6">
        <div class="panel panel-bordered panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">ข้อมูลนักวิจัย</h3>
            </div>
            <div class="panel-body">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td class="font-weight-900"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="font-weight-900"></td>
                            <td></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>
    <div class="col-md-12">
        <div class="panel panel-bordered panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">ข้อมูลงเอกสารงานวิจัย</h3>
            </div>
            <div class="panel-body">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td class="font-weight-900"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="font-weight-900"></td>
                            <td></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>
</div>


<div class="row">
    <div class="form-group">
        <div class="pull-left">
            <?= Html::submitButton('ก่อนหน้า', ['class' => 'btn btn-primary', 'name' => 'previous-step']) ?>
        </div>
        <div class="pull-right">
            <?= Html::submitButton('ยืนยัน', ['class' => 'btn btn-primary', 'name' => 'next-step']) ?>
        </div>
    </div>
</div>