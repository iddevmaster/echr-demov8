<?php

use yii\helpers\Html;
use kartik\select2\Select2;
use app\models\Organization;
use yii\helpers\ArrayHelper;
use app\models\FundingSource;
use app\models\SubmissionType;
use app\models\Submission;

/* @var $this yii\web\View */
/* @var $model app\models\Project */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="col-md-12">
    <div class="panel panel-bordered panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">ข้อมูลทั่วไปของงานวิจัย <?php
                            if (isset($submission->project->project_code)) {
                                echo Yii::t('app', 'เลขที่โครงการ : ') . $submission->project->project_code;
                            }
                            ?></h3>
        </div>
        <div class="panel-body">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td class="font-weight-900"><?php
                                echo isset($submission->project->name_thai) ? Yii::t('app', 'ชื่อโครงการภาษาไทย : ') . $submission->project->name_thai : "";
                                ?></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="font-weight-900"><?php
                                echo isset($submission->project->name_eng) ? Yii::t('app', 'ชื่อโครงการภาษาอังกฤต : ') . $submission->project->name_eng : "";
                                ?></td>
                            <td></td>
                        </tr>
                        <tr><td>

                                <div class="col-md-6">
                                    <?php
                                    echo isset($submission->project->start_date) ? Yii::t('app', 'วันที่เริ่มโครงการ : ') . $submission->project->start_date : "";
                                    ?>
                                </div>

                                <div class="col-md-6">
                                    <?php
                                    echo isset($submission->project->end_date) ? Yii::t('app', 'วันที่สินสุดโครงการ : ') . $submission->project->end_date : "";
                                    ?>                       
                                </div>
                                <div class="col-md-6">
                                    <?php
                                    echo isset($submission->project->organization) ? Yii::t('app', 'องค์กร : ') . $submission->project->organization->name : "";
                                    ?>    
                                </div>    
                                <div class="col-md-6">
                                    <?php
                                    echo isset($submission->project->department) ? Yii::t('app', 'ภาคสาขาวิชา : ') . $submission->project->department->name : "";
                                    ?>    

                                </div> 

                                <div class="col-md-12">
                                    <?php
                                    echo isset($submission->project->funding_source_description) ? Yii::t('app', 'รายละเอียดแหล่งทุน : ') . $submission->project->funding_source_description : "";
                                    ?>    

                                </div>

                                <div class="col-md-12">
                                    <?php
                                    echo isset($submission->project->remark) ? Yii::t('app', 'รายละเอียดเพิ่มเติม : ') . $submission->project->remark : "";
                                    ?>    
                                </div>
                            </td></tr>
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
