<?php

use yii\widgets\DetailView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SubmissionDocument */
?>
<div class="submission-document-view">

    <div class="highlight clearfix">
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-6 control-label"> 1. <a href="#">เอกสาร แบบเสนอเพื่อขอรับการพิจารณาฯ</a></label>
            <div class="col-sm-7">
                <input name="file_title" id="file_title0" type="hidden" value="แบบเสนอเพื่อขอรับการพิจารณาฯ">
                <div><?= Html::a('<i class="glyphicon glyphicon-ok"></i> ' . Yii::t('app', 'ผ่าน'), ['site/submission-continue'], [ 'class' => 'btn btn-success btn-pure']) ?> <?= Html::a('<i class="glyphicon glyphicon-remove"></i> ' . Yii::t('app', 'ไม่ผ่าน'), ['site/submission-continue'], [ 'class' => 'btn btn-warning btn-pure']) ?>  หมายเหตุ : <input type="input" ></div>
              
            </div>
        </div><div class="form-group">
            <label for="inputEmail3" class="col-sm-6 control-label "> 2. เอกสาร หลักฐานการชำระค่าธรรมเนียม</label>
            <div class="col-sm-7">
                <input name="file_title" id="file_title1" type="hidden" value="หลักฐานการชำระค่าธรรมเนียม">
                <div><?= Html::a('<i class="glyphicon glyphicon-ok"></i> ' . Yii::t('app', 'ผ่าน'), ['site/submission-continue'], [ 'class' => 'btn btn-success btn-pure']) ?> <?= Html::a('<i class="glyphicon glyphicon-remove"></i> ' . Yii::t('app', 'ไม่ผ่าน'), ['site/submission-continue'], [ 'class' => 'btn btn-warning btn-pure']) ?>  หมายเหตุ : <input type="input" ></div>

            </div>
        </div><div class="form-group">
            <label for="inputEmail3" class="col-sm-6 control-label"> 3. เอกสาร แบบคำชี้แจงอาสาสมัคร</label>
            <div class="col-sm-7">
                <input name="file_title" id="file_title2" type="hidden" value="แบบคำชี้แจงอาสาสมัคร">
                <div><?= Html::a('<i class="glyphicon glyphicon-ok"></i> ' . Yii::t('app', 'ผ่าน'), ['site/submission-continue'], [ 'class' => 'btn btn-success btn-pure']) ?> <?= Html::a('<i class="glyphicon glyphicon-remove"></i> ' . Yii::t('app', 'ไม่ผ่าน'), ['site/submission-continue'], [ 'class' => 'btn btn-warning btn-pure']) ?>  หมายเหตุ : <input type="input" ></div>

            </div>
        </div><div class="form-group">
            <label for="inputEmail3" class="col-sm-6 control-label "> 4. เอกสาร แบบคำยินยอมอาสาสมัคร</label>
            <div class="col-sm-7">
                <input name="file_title" id="file_title3" type="hidden" value="แบบคำยินยอมอาสาสมัคร">
                <div><?= Html::a('<i class="glyphicon glyphicon-ok"></i> ' . Yii::t('app', 'ผ่าน'), ['site/submission-continue'], [ 'class' => 'btn btn-success btn-pure']) ?> <?= Html::a('<i class="glyphicon glyphicon-remove"></i> ' . Yii::t('app', 'ไม่ผ่าน'), ['site/submission-continue'], [ 'class' => 'btn btn-warning btn-pure']) ?>  หมายเหตุ : <input type="input" ></div>

            </div>
        </div><div class="form-group">
            <label for="inputEmail3" class="col-sm-6 control-label"> 5. เอกสาร โครงการวิจัย</label>
            <div class="col-sm-7">
                <input name="file_title" id="file_title4" type="hidden" value="โครงการวิจัย">
                <div><?= Html::a('<i class="glyphicon glyphicon-ok"></i> ' . Yii::t('app', 'ผ่าน'), ['site/submission-continue'], [ 'class' => 'btn btn-success btn-pure']) ?> <?= Html::a('<i class="glyphicon glyphicon-remove"></i> ' . Yii::t('app', 'ไม่ผ่าน'), ['site/submission-continue'], [ 'class' => 'btn btn-warning btn-pure']) ?>  หมายเหตุ : <input type="input" ></div>

            </div>
        </div><div class="form-group">
            <label for="inputEmail3" class="col-sm-6 control-label"> 6. เอกสาร ประวัติความรู้ความชำนาญนักวิจัยและผู้ร่วมวิจัย</label>
            <div class="col-sm-7">
                <input name="file_title" id="file_title5" type="hidden" value="ประวัติความรู้ความชำนาญนักวิจัยและผู้ร่วมวิจัย">
                <div><?= Html::a('<i class="glyphicon glyphicon-ok"></i> ' . Yii::t('app', 'ผ่าน'), ['site/submission-continue'], [ 'class' => 'btn btn-success btn-pure']) ?> <?= Html::a('<i class="glyphicon glyphicon-remove"></i> ' . Yii::t('app', 'ไม่ผ่าน'), ['site/submission-continue'], [ 'class' => 'btn btn-warning btn-pure']) ?>  หมายเหตุ : <input type="input" ></div>

            </div>
        </div><div class="form-group">
            <label for="inputEmail3" class="col-sm-6 control-label"> 7. เอกสาร หลักฐานการอบรมจริยธรรมฯ</label>
            <div class="col-sm-7">
                <input name="file_title" id="file_title6" type="hidden" value="หลักฐานการอบรมจริยธรรมฯ">
                <div><?= Html::a('<i class="glyphicon glyphicon-ok"></i> ' . Yii::t('app', 'ผ่าน'), ['site/submission-continue'], [ 'class' => 'btn btn-success btn-pure']) ?> <?= Html::a('<i class="glyphicon glyphicon-remove"></i> ' . Yii::t('app', 'ไม่ผ่าน'), ['site/submission-continue'], [ 'class' => 'btn btn-warning btn-pure']) ?>  หมายเหตุ : <input type="input" ></div>

            </div>
        </div><div class="form-group">
            <label for="inputEmail3" class="col-sm-6 control-label"> 8. เอกสาร เครื่องมือที่ใช้ในการวิจัย</label>
            <div class="col-sm-7">
                <input name="file_title" id="file_title7" type="hidden" value="เครื่องมือที่ใช้ในการวิจัย">
                <div><?= Html::a('<i class="glyphicon glyphicon-ok"></i> ' . Yii::t('app', 'ผ่าน'), ['site/submission-continue'], [ 'class' => 'btn btn-success btn-pure']) ?> <?= Html::a('<i class="glyphicon glyphicon-remove"></i> ' . Yii::t('app', 'ไม่ผ่าน'), ['site/submission-continue'], [ 'class' => 'btn btn-warning btn-pure']) ?>  หมายเหตุ : <input type="input" ></div>

            </div>
        </div><div class="form-group">
            <label for="inputEmail3" class="col-sm-6 control-label"> 9. เอกสาร เอกสารอื่นๆ เพื่อประกอบการพิจารณา</label>
            <div class="col-sm-7">
                <input name="file_title" id="file_title8" type="hidden" value="เอกสารอื่นๆ เพื่อประกอบการพิจารณา">
                <div><?= Html::a('<i class="glyphicon glyphicon-ok"></i> ' . Yii::t('app', 'ผ่าน'), ['site/submission-continue'], [ 'class' => 'btn btn-success btn-pure']) ?> <?= Html::a('<i class="glyphicon glyphicon-remove"></i> ' . Yii::t('app', 'ไม่ผ่าน'), ['site/submission-continue'], [ 'class' => 'btn btn-warning btn-pure']) ?>  หมายเหตุ : <input type="input" ></div>

            </div>
        </div>


        <div class="col-sm-12" id="alert_upload">
        </div>
        <div class="column-right margin_bottom20" id="queue"></div>
        <div id="show_num"></div>
        <div id="show" class="col-xs-12 ui-sortable"></div>
    </div>

</div>
