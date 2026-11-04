<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Agenda;

/* @var $this yii\web\View */
/* @var $model app\models\MeetingAgenda */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="meeting-agenda-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    // Usage with ActiveForm and model
    $data = ArrayHelper::map(Agenda::find()->isDeleted(FALSE)->orderBy('id ASC')->all(), 'id', 'name');
//    \yii\helpers\VarDumper::dump($data, 10, TRUE);
    echo $form->field($model, 'agenda_id')->widget(Select2::classname(), [
        'data' => $data,
        'options' => ['placeholder' => ''],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?> 
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>


<div class="col-md-6">
            <div class="product-index">
    <div class="ajaxCrudDatatable panel panel-default">
        <div id="crud-datatable-product-pjax" data-pjax-container="" data-pjax-push-state="" data-pjax-timeout="1000"><div id="crud-datatable-product" class="grid-view hide-resize" data-krajee-grid="kvGridInit_1a3faeb8"><div class="panel panel-primary">
    
 
<table class="kv-grid-table table table-bordered table-striped table-condensed kv-table-wrap" >

<thead><tr class="size-row" ><th class="floatThead-col" >#</th><th class="floatThead-col" >หมายเลขโครงการ</th><th class="floatThead-col" ></th></tr></thead><tbody>
<tr data-key="22"><td class="kv-align-center kv-align-middle" style="width:30px;" data-col-seq="0">1</td><td data-col-seq="1">HE22343334</td><td class="skip-export kv-align-center kv-nowrap kv-align-middle"  data-col-seq="3"><a href="/survey/company-product/select-products?id=1673&amp;productId=22&amp;companyId=1673" title="" role="modal-remote" data-toggle="tooltip" data-original-title="เลือก"><i class="icon md-arrow-right font-size-18"></i></a></td></tr>
<tr data-key="23"><td class="kv-align-center kv-align-middle" style="width:30px;" data-col-seq="0">2</td><td data-col-seq="1">HE22311223</td><td class="skip-export kv-align-center kv-nowrap kv-align-middle"  data-col-seq="3"><a href="/survey/company-product/select-products?id=1673&amp;productId=23&amp;companyId=1673" title="" role="modal-remote" data-toggle="tooltip" data-original-title="เลือก"><i class="icon md-arrow-right font-size-18"></i></a></td></tr>
<tr data-key="24"><td class="kv-align-center kv-align-middle" style="width:30px;" data-col-seq="0">3</td><td data-col-seq="1">HE22311226</td><td class="skip-export kv-align-center kv-nowrap kv-align-middle"  data-col-seq="3"><a href="/survey/company-product/select-products?id=1673&amp;productId=24&amp;companyId=1673" title="" role="modal-remote" data-toggle="tooltip" data-original-title="เลือก"><i class="icon md-arrow-right font-size-18"></i></a></td></tr>
<tr data-key="25"><td class="kv-align-center kv-align-middle" style="width:30px;" data-col-seq="0">4</td><td data-col-seq="1">HE22311227</td><td class="skip-export kv-align-center kv-nowrap kv-align-middle"  data-col-seq="3"><a href="/survey/company-product/select-products?id=1673&amp;productId=25&amp;companyId=1673" title="" role="modal-remote" data-toggle="tooltip" data-original-title="เลือก"><i class="icon md-arrow-right font-size-18"></i></a></td></tr>
<tr data-key="26"><td class="kv-align-center kv-align-middle" style="width:30px;" data-col-seq="0">5</td><td data-col-seq="1">HE22311228</td><td class="skip-export kv-align-center kv-nowrap kv-align-middle"  data-col-seq="3"><a href="/survey/company-product/select-products?id=1673&amp;productId=26&amp;companyId=1673" title="" role="modal-remote" data-toggle="tooltip" data-original-title="เลือก"><i class="icon md-arrow-right font-size-18"></i></a></td></tr>
<tr data-key="27"><td class="kv-align-center kv-align-middle" style="width:30px;" data-col-seq="0">6</td><td data-col-seq="1">HE22311229</td><td class="skip-export kv-align-center kv-nowrap kv-align-middle"  data-col-seq="3"><a href="/survey/company-product/select-products?id=1673&amp;productId=27&amp;companyId=1673" title="" role="modal-remote" data-toggle="tooltip" data-original-title="เลือก"><i class="icon md-arrow-right font-size-18"></i></a></td></tr>
</tbody><fthfoot style="display:table-footer-group;border-spacing:0;height:0;border-collapse:collapse;visibility:hidden"><fthtr style="display:table-row;border-spacing:0;height:0;border-collapse:collapse"><fthtd style="display:table-cell;height:0;width:auto;"></fthtd><fthtd style="display:table-cell;height:0;width:auto;"></fthtd><fthtd style="display:table-cell;height:0;width:auto;"></fthtd><fthtd style="display:table-cell;height:0;width:auto;"></fthtd></fthtr></fthfoot></table></div>

</div></div></div>    </div>
</div>

<div class="col-md-6">
            <div class="product-index">
    <div class="ajaxCrudDatatable panel panel-default">
        <div id="crud-datatable-product-pjax" data-pjax-container="" data-pjax-push-state="" data-pjax-timeout="1000"><div id="crud-datatable-product" class="grid-view hide-resize" data-krajee-grid="kvGridInit_1a3faeb8"><div class="panel panel-primary">
    
 
<table class="kv-grid-table table table-bordered table-striped table-condensed kv-table-wrap" >

<thead><tr class="size-row" ><th class="floatThead-col" >#</th><th class="floatThead-col" >ลำดับ</th><th class="floatThead-col" >หมายเลขโครงการ</th><th class="floatThead-col" ></th></tr></thead><tbody>
<tr data-key="22"><td class="kv-align-center kv-align-middle" style="width:30px;" data-col-seq="0">1</td><td data-col-seq="1">3.1.1</td><td data-col-seq="1">HE2234322</td><td class="skip-export kv-align-center kv-nowrap kv-align-middle"  data-col-seq="3"><a href="/survey/company-product/select-products?id=1673&amp;productId=22&amp;companyId=1673" title="" role="modal-remote" data-toggle="tooltip" data-original-title="เลือก"><i class="glyphicon glyphicon-trash font-size-18"></i></a></td></tr>
<tr data-key="23"><td class="kv-align-center kv-align-middle" style="width:30px;" data-col-seq="0">2</td><td data-col-seq="1">3.1.2</td><td data-col-seq="1">HE6020333</td><td class="skip-export kv-align-center kv-nowrap kv-align-middle"  data-col-seq="3"><a href="/survey/company-product/select-products?id=1673&amp;productId=23&amp;companyId=1673" title="" role="modal-remote" data-toggle="tooltip" data-original-title="เลือก"><i class="glyphicon glyphicon-trash font-size-18"></i></a></td></tr>
<tr data-key="24"><td class="kv-align-center kv-align-middle" style="width:30px;" data-col-seq="0">3</td><td data-col-seq="1">3.1.3</td><td data-col-seq="1">HE6020334</td><td class="skip-export kv-align-center kv-nowrap kv-align-middle"  data-col-seq="3"><a href="/survey/company-product/select-products?id=1673&amp;productId=24&amp;companyId=1673" title="" role="modal-remote" data-toggle="tooltip" data-original-title="เลือก"><i class="glyphicon glyphicon-trash font-size-18"></i></a></td></tr>
<tr data-key="25"><td class="kv-align-center kv-align-middle" style="width:30px;" data-col-seq="0">4</td><td data-col-seq="1">3.1.4</td><td data-col-seq="1">HE6020335</td><td class="skip-export kv-align-center kv-nowrap kv-align-middle"  data-col-seq="3"><a href="/survey/company-product/select-products?id=1673&amp;productId=25&amp;companyId=1673" title="" role="modal-remote" data-toggle="tooltip" data-original-title="เลือก"><i class="glyphicon glyphicon-trash font-size-18"></i></a></td></tr>
<tr data-key="26"><td class="kv-align-center kv-align-middle" style="width:30px;" data-col-seq="0">5</td><td data-col-seq="1">3.1.5</td><td data-col-seq="1">HE6020367</td><td class="skip-export kv-align-center kv-nowrap kv-align-middle"  data-col-seq="3"><a href="/survey/company-product/select-products?id=1673&amp;productId=26&amp;companyId=1673" title="" role="modal-remote" data-toggle="tooltip" data-original-title="เลือก"><i class="glyphicon glyphicon-trash font-size-18"></i></a></td></tr>
<tr data-key="27"><td class="kv-align-center kv-align-middle" style="width:30px;" data-col-seq="0">6</td><td data-col-seq="1">3.1.6</td><td data-col-seq="1">HE6020331</td><td class="skip-export kv-align-center kv-nowrap kv-align-middle"  data-col-seq="3"><a href="/survey/company-product/select-products?id=1673&amp;productId=27&amp;companyId=1673" title="" role="modal-remote" data-toggle="tooltip" data-original-title="เลือก"><i class="glyphicon glyphicon-trash font-size-18"></i></a></td></tr>
</tbody><fthfoot style="display:table-footer-group;border-spacing:0;height:0;border-collapse:collapse;visibility:hidden"><fthtr style="display:table-row;border-spacing:0;height:0;border-collapse:collapse"><fthtd style="display:table-cell;height:0;width:auto;"></fthtd><fthtd style="display:table-cell;height:0;width:auto;"></fthtd><fthtd style="display:table-cell;height:0;width:auto;"></fthtd><fthtd style="display:table-cell;height:0;width:auto;"></fthtd></fthtr></fthfoot></table></div>

</div></div></div>    </div>
</div>

<div>
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
 </div>   
</div>
