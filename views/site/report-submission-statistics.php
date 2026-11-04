<?php

use app\assets\TableExportAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use miloschuman\highcharts\Highcharts;

TableExportAsset::register($this);

$this->title = Yii::t('app', 'สถิติโครงการวิจัย');
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['site/report-list']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="submission-index ">
    <?php
    echo $this->renderFile('@app/views/site/_search-report-submission-statistics.php', ['searchModel' => $searchModel]);
    ?>
    <div class="row">
        <div class="col-md-12">

            <div class="panel">
                <?php
                echo $this->renderFile('@app/views/site/_report-submission-statistics.php', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
                ?>
            </div>
        </div>
    </div>
</div>

<?php

$js = <<<js
    $('body').on('click', '.btn-pdf', function() {
//        console.log(this);
        $('#pdf').val(1);
        $('#search-form').attr('target', '_blank');
        $('#search-form').submit();
    });
        
    $('body').on('click', '.btn-search', function() {
//        console.log(this);
        $('#pdf').val(0);
        $('#search-form').attr('target', '_self');
        $('#search-form').submit();
    });

    $('body').on('click', '.btn-excel', function() {
        var ExportButtons = document.getElementById('table-statistics');
        // console.log(ExportButtons);
        var instance = new TableExport(ExportButtons, {
            formats: ['xlsx'],
            exportButtons: false
        });
    //    console.log(instance.getExportData());
        //                                        // "id" of selector    // format
        var exportData = instance.getExportData()['table-statistics']['xlsx'];
        for (var i = 0; i < exportData.data.length; i++) {
            exportData.data[i][5].t = 's';
            exportData.data[i][6].t = 's';
            exportData.data[i][7].t = 's';
            exportData.data[i][8].t = 's';
            exportData.data[i][9].t = 's';
            exportData.data[i][12].t = 's';
            exportData.data[i][13].t = 's';
            exportData.data[i][14].t = 's';
            if (exportData.data[i][16]) {
                exportData.data[i][16].t = 's';
                exportData.data[i][17].t = 's';
                exportData.data[i][18].t = 's';
            }
        }
//        console.log(exportData.data)
        instance.export2file(exportData.data, exportData.mimeType, 'สถิติโครงการวิจัย', exportData.fileExtension);
    });
js;
$this->registerJs($js);