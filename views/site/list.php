<?php

use yii\widgets\ListView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;

//\johnitvn\ajaxcrud\CrudAsset::register($this);
//$dataProvider->pagination->pageSize = 2;
?>
<div class="col-lg-12">
    <!-- Panel Add &amp; Remove Rows -->
    <div class="panel">
        <header class="panel-heading">
            <h3 class="panel-title">งานวิจัยใหม่</h3>
        </header>
        <div class="panel-body">
            <div class="form-inline padding-bottom-15">
                <div class="row">

                    <div class="col-sm-12 text-right">
                        <div class="form-group">
                            <input id="addRemoveSearch" type="text" placeholder="ค้นหาข้อมูลตามชื่อโครงการ" class="form-control" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div><table id="exampleFooAddRemove" class="table table-bordered table-hover toggle-circle default footable-loaded footable" data-page-size="7">
                <thead>
                    <tr>
                        <th data-sort-initial="true" data-toggle="true" class="footable-visible footable-first-column footable-sortable footable-sorted">#<span class="footable-sort-indicator"></span></th>
                        <th class="footable-visible footable-sortable">หมายเลขโครงการ<span class="footable-sort-indicator"></span></th>
                        <th data-hide="phone, tablet" class="footable-visible footable-sortable">ชื่อโครงการวิจัย<span class="footable-sort-indicator"></span></th>
                        <th data-hide="phone, tablet" class="footable-visible footable-sortable">ตรวจสอบเอกสาร<span class="footable-sort-indicator"></span></th>
                        <th data-sort-ignore="true" class="min-width footable-visible footable-last-column">จัดการข้อมูล</th>
                    </tr>
                </thead>

                <tbody>
                    <tr class="footable-odd" style="display: table-row;">
                        <td class="footable-visible footable-first-column"><span class="footable-toggle"></span>1</td>
                        <td class="footable-visible">HE00122</td>
                        <td class="footable-visible">ข้อมูลการติดเชื้อเอชไอวีในเด็กของเอเชีย</td>
                        <td class="footable-visible">
                            <span class="label label-table label-danger">ยังไม่ตรวจสอบ</span>
                        </td>
                        <td class="footable-visible footable-last-column text-center">
                            <?=
                            Html::a('<i class="icon wb-download" aria-hidden="true" data-toggle="tooltip" data-original-title="แสดงข้อมูลงานวิจัย"></i>', ['site/project-submission'], [
                                'data-confirm' => false, 'data-method' => false, // for overide yii data api
                                //           'class' => 'btn btn-primary btn-lg',
//            'data-pjax' => FALSE,
//            'data-request-method' => 'post',
                                'data-toggle' => 'tooltip'])
                            ?>
                        </td>
                    </tr><tr class="footable-even" style="display: table-row;">
                        <td class="footable-visible footable-first-column"><span class="footable-toggle"></span>2</td>
                        <td class="footable-visible">HE98093</td>
                        <td class="footable-visible">กลไกการเกิดโรคมะเร็งที่เกิดจากการติดเชื้อพยาธิใบไม้ตับในประเทศไทย</td>
                        <td class="footable-visible">
                            <span class="label label-table label-danger">ยังไม่ตรวจสอบ</span>
                        </td>
                        <td class="footable-visible footable-last-column text-center">
                            <?=
                            Html::a('<i class="icon wb-download" aria-hidden="true" data-toggle="tooltip" data-original-title="แสดงข้อมูลงานวิจัย"></i>', ['site/project-submission'], [
                                'data-confirm' => false, 'data-method' => false, // for overide yii data api
                                //           'class' => 'btn btn-primary btn-lg',
//            'data-pjax' => FALSE,
//            'data-request-method' => 'post',
                                'data-toggle' => 'tooltip'])
                            ?>                        </td>
                    </tr><tr class="footable-odd" style="display: table-row;">
                        <td class="footable-visible footable-first-column"><span class="footable-toggle"></span>3</td>
                        <td class="footable-visible">HE22233</td>
                        <td class="footable-visible">กำหนดเวลาเชิงกลยุทธ์ในการรักษาด้วยยาต้านไวรัสเอชไอวี</td>
                        <td class="footable-visible">
                            <span class="label label-table label-success">ตรวจสอบแล้ว</span>
                        </td>
                        <td class="footable-visible footable-last-column text-center">
                            <?=
                            Html::a('<i class="icon wb-download" aria-hidden="true" data-toggle="tooltip" data-original-title="แสดงข้อมูลงานวิจัย"></i>', ['site/project-submission'], [
                                'data-confirm' => false, 'data-method' => false, // for overide yii data api
                                //           'class' => 'btn btn-primary btn-lg',
//            'data-pjax' => FALSE,
//            'data-request-method' => 'post',
                                'data-toggle' => 'tooltip'])
                            ?>                        </td>
                    </tr></tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" class="footable-visible">
                            <div class="text-right">
                                <ul class="pagination"><li class="footable-page-arrow"><a data-page="first" href="#first">«</a></li><li class="footable-page-arrow"><a data-page="prev" href="#prev">‹</a></li><li class="footable-page"><a data-page="0" href="#">1</a></li><li class="footable-page active"><a data-page="1" href="#">2</a></li><li class="footable-page-arrow"><a data-page="next" href="#next">›</a></li><li class="footable-page-arrow"><a data-page="last" href="#last">»</a></li></ul>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <!-- End Panel Add &amp; Remove Rows -->
</div>