<?php
/* @var $this yii\web\View */

use yii\helpers\Html;


$this->title = Yii::t('app', 'งานวิจัยต่อเนื่อง');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">

        <div class=" panel col-md-12">
                    <div class="page-header">
            <h1 class="page-title">ข้อมูลวิจัย : xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx</h1>
            <div class="page-header-actions">
                <button type="button" class="btn btn-sm btn-icon btn-primary btn-round waves-effect waves-light waves-round" data-toggle="tooltip" data-original-title="Refresh">
                    <i class="icon md-refresh-alt" aria-hidden="true"></i>
                </button>
                <button type="button" class="btn btn-sm btn-icon btn-primary btn-round waves-effect waves-light waves-round" data-toggle="tooltip" data-original-title="Setting">
                    <i class="icon md-settings" aria-hidden="true"></i>
                </button>
            </div>
        </div>
            <!-- Example Tabs Line Left With JS -->
            <div class=" example-wrap">
                <div class="nav-tabs-vertical">
                    <ul class="nav nav-tabs nav-tabs-line margin-right-25" data-plugin="nav-tabs" role="tablist">
                        <li class="active" role="presentation"><a data-toggle="tab" href="#exampleTabsLineLeftOne" aria-controls="exampleTabsLineLeftOne"
                                                                  role="tab">ข้อมูลทั่วไป</a></li>
                        <li role="presentation"><a data-toggle="tab" href="#exampleTabsLineLeftTwo" aria-controls="exampleTabsLineLeftTwo"
                                                   role="tab">เอกสารที่เกี่ยวข้อง</a></li>
                        <li role="presentation"><a data-toggle="tab" href="#exampleTabsLineLeftThree" aria-controls="exampleTabsLineLeftThree"
                                                   role="tab">เลือกกรรมการและเลขา</a></li>
                        <li role="presentation"><a data-toggle="tab" href="#exampleTabsLineLeftFour" aria-controls="exampleTabsLineLeftFour"
                                                   role="tab">บรรจุวาระการประชุม</a></li>
                        <li role="presentation"><a data-toggle="tab" href="#exampleTabsLineLeftFive" aria-controls="exampleTabsLineLeftFour"
                                                   role="tab">ประวัติการดำเนินการ</a></li>
                    </ul>
                    <div class="tab-content padding-vertical-15">
                        <div class="tab-pane active" id="exampleTabsLineLeftOne" role="tabpanel">
                            <div class="form-group form-material">
                                <label class="control-label" for="inputUserNameOne">ชื่องานวิจัยภาษาไทย</label>
                                <input type="text" class="form-control" id="inputCardNumberOne">
                            </div>

                            <div class="form-group form-material">
                                <label class="control-label" for="inputPasswordOne">ชื่องานวิจัยภาษาอังกฤษ</label>
                                <input type="text" class="form-control" id="inputCardNumberOne">
                            </div>
                            <div class="form-group form-material">
                                <label class="control-label" for="inputCardNumberOne">เลือกประเภทโครงการวิจัย</label>
                                <select name="agenda" id="agenda" class="form-control">
                                    <option value="">กรุณาเลือกประเภทวิจัย</option>
                                    <option value="3">โครงการวิจัยใหม่ที่เข้าข่ายยกเว้นการรับรองการพิจารณาจริยธรรมฯ  (Exemption</option> <option value="4" selected="">โครงการวิจัยใหม่ที่เข้าข่ายการขอรับพิจารณาแบบเร็ว และประธานให้ความเห็นชอบ (Expedited Research)</option><option value="7">โครงการวิจัยใหม่ที่เข้าข่ายการพิจารณาแบบเร็ว แต่ประะธานขอให้ที่ประชุมพิจารณา (Request for full board consideration)</option> <option value="8">โครงการวิจัยใหม่ทางคลินิก (New clinical study Protocol)</option> <option value="9">โครงการวิจัยใหม่ทางสังคมศาสตร์/มานุษย์วิทยา (Social &amp; Behavioral  Study)</option>     </select>                        </div>
                            <div class="form-group form-material col-md-6">
                                <label class="control-label" for="inputCardNumberOne"> คณะ/หน่วยงานที่สังกัด</label>
                                <input name="dep_detail" class="form-control" id="dep_detail" placeholder=""></textarea>
                            </div>
                            <div class="form-group form-material col-md-6">
                                <label class="control-label" for="inputCardNumberOne">สาขา</label>
                                <input name="dep_detail" class="form-control" id="dep_detail" placeholder=""></textarea>
                            </div>
                            <div class="form-group form-material">
                                <label class="control-label" for="inputCardNumberOne"> แหล่งทุนการศึกษาวิจัย</label>
                                <select name="depart" id="depart" class="form-control">
                                    <option value=""></option>
                                    <option value="หน่วยงานเอกชน/บริษัท">หน่วยงานเอกชน/บริษัท</option>
                                    <option value="มูลนิธิ /องค์กรอิสระ/สมาคมที่ไม่หวังผลกำไร">มูลนิธิ /องค์กรอิสระ/สมาคมที่ไม่หวังผลกำไร</option>
                                    <option value="หน่วยงานรัฐบาลที่ส่งเสริมการทำวิจัย">หน่วยงานรัฐบาลที่ส่งเสริมการทำวิจัย</option>
                                    <option value="ภายในมหาวิทยาลัยขอนแก่น">ภายในมหาวิทยาลัยขอนแก่น</option>
                                    <option value="ทุนส่วนตัว">- ทุนส่วนตัว</option>
                                </select>
                            </div>
                            <div class="form-group form-material col-md-6">
                                <label class="control-label" for="inputCardNumberOne"> รายละเอียดทุนวิจัย</label>
                                <input name="dep_detail" class="form-control" id="dep_detail" placeholder=""></textarea>
                            </div>
                            <div class="form-group form-material col-md-6"><label class="control-label" for="inputCardNumberOne">เลขที่ อย. ลำดับที่ <span class="box-number">1</span> (สำหรับโครงการวิจัยยา)</label><input type="text" class="form-control" value=""><input name="fda_id[]" type="hidden" id="fda_id1" value=""></div>

                        </div>
                        <div class="tab-pane" id="exampleTabsLineLeftTwo" role="tabpanel">
                            <div class="highlight clearfix">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-5 control-label">1. เอกสาร แบบเสนอเพื่อขอรับการพิจารณาฯ</label>
                                    <div class="col-sm-5">
                                        <input name="file_title" id="file_title0" type="hidden" value="แบบเสนอเพื่อขอรับการพิจารณาฯ">
                                        <div id="uploadifive-file_upload0" class="uploadifive-button" style="height: 30px; line-height: 30px; overflow: hidden; position: relative; text-align: center; width: 100px;">Select Files<input id="file_upload0" name="file_upload" type="file" class="file_upload" multiple="" style="display:none"><input type="file" style="font-size: 30px; opacity: 0; position: absolute; right: -3px; top: -3px; z-index: 999;" multiple="multiple" accept="image/png,image/jpg,image/jpeg,image/gif,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/excel,application/vnd.ms-excel,application/x-excel,application/x-msexcel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain,application/vnd.oasis.opendocument.text,application/vnd.oasis.opendocument.spreadsheet,application/vnd.ms-xpsdocument"></div><div id="uploadifive-file_upload0-queue" class="uploadifive-queue"></div>
                                        <div class="column-right margin_bottom20" id="queue0"></div>
                                    </div>
                                </div><div class="form-group">
                                    <label for="inputEmail3" class="col-sm-5 control-label">2. เอกสาร หลักฐานการชำระค่าธรรมเนียม</label>
                                    <div class="col-sm-5">
                                        <input name="file_title" id="file_title1" type="hidden" value="หลักฐานการชำระค่าธรรมเนียม">
                                        <div id="uploadifive-file_upload1" class="uploadifive-button" style="height: 30px; line-height: 30px; overflow: hidden; position: relative; text-align: center; width: 100px;">Select Files<input id="file_upload1" name="file_upload" type="file" class="file_upload" multiple="" style="display:none"><input type="file" style="font-size: 30px; opacity: 0; position: absolute; right: -3px; top: -3px; z-index: 999;" multiple="multiple" accept="image/png,image/jpg,image/jpeg,image/gif,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/excel,application/vnd.ms-excel,application/x-excel,application/x-msexcel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain,application/vnd.oasis.opendocument.text,application/vnd.oasis.opendocument.spreadsheet,application/vnd.ms-xpsdocument"></div><div id="uploadifive-file_upload1-queue" class="uploadifive-queue"></div>
                                        <div class="column-right margin_bottom20" id="queue1"></div>
                                    </div>
                                </div><div class="form-group">
                                    <label for="inputEmail3" class="col-sm-5 control-label">3. เอกสาร แบบคำชี้แจงอาสาสมัคร</label>
                                    <div class="col-sm-5">
                                        <input name="file_title" id="file_title2" type="hidden" value="แบบคำชี้แจงอาสาสมัคร">
                                        <div id="uploadifive-file_upload2" class="uploadifive-button" style="height: 30px; line-height: 30px; overflow: hidden; position: relative; text-align: center; width: 100px;">Select Files<input id="file_upload2" name="file_upload" type="file" class="file_upload" multiple="" style="display:none"><input type="file" style="font-size: 30px; opacity: 0; position: absolute; right: -3px; top: -3px; z-index: 999;" multiple="multiple" accept="image/png,image/jpg,image/jpeg,image/gif,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/excel,application/vnd.ms-excel,application/x-excel,application/x-msexcel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain,application/vnd.oasis.opendocument.text,application/vnd.oasis.opendocument.spreadsheet,application/vnd.ms-xpsdocument"></div><div id="uploadifive-file_upload2-queue" class="uploadifive-queue"></div>
                                        <div class="column-right margin_bottom20" id="queue2"></div>
                                    </div>
                                </div><div class="form-group">
                                    <label for="inputEmail3" class="col-sm-5 control-label">4. เอกสาร แบบคำยินยอมอาสาสมัคร</label>
                                    <div class="col-sm-5">
                                        <input name="file_title" id="file_title3" type="hidden" value="แบบคำยินยอมอาสาสมัคร">
                                        <div id="uploadifive-file_upload3" class="uploadifive-button" style="height: 30px; line-height: 30px; overflow: hidden; position: relative; text-align: center; width: 100px;">Select Files<input id="file_upload3" name="file_upload" type="file" class="file_upload" multiple="" style="display:none"><input type="file" style="font-size: 30px; opacity: 0; position: absolute; right: -3px; top: -3px; z-index: 999;" multiple="multiple" accept="image/png,image/jpg,image/jpeg,image/gif,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/excel,application/vnd.ms-excel,application/x-excel,application/x-msexcel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain,application/vnd.oasis.opendocument.text,application/vnd.oasis.opendocument.spreadsheet,application/vnd.ms-xpsdocument"></div><div id="uploadifive-file_upload3-queue" class="uploadifive-queue"></div>
                                        <div class="column-right margin_bottom20" id="queue3"></div>
                                    </div>
                                </div><div class="form-group">
                                    <label for="inputEmail3" class="col-sm-5 control-label">5. เอกสาร โครงการวิจัย</label>
                                    <div class="col-sm-5">
                                        <input name="file_title" id="file_title4" type="hidden" value="โครงการวิจัย">
                                        <div id="uploadifive-file_upload4" class="uploadifive-button" style="height: 30px; line-height: 30px; overflow: hidden; position: relative; text-align: center; width: 100px;">Select Files<input id="file_upload4" name="file_upload" type="file" class="file_upload" multiple="" style="display:none"><input type="file" style="font-size: 30px; opacity: 0; position: absolute; right: -3px; top: -3px; z-index: 999;" multiple="multiple" accept="image/png,image/jpg,image/jpeg,image/gif,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/excel,application/vnd.ms-excel,application/x-excel,application/x-msexcel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain,application/vnd.oasis.opendocument.text,application/vnd.oasis.opendocument.spreadsheet,application/vnd.ms-xpsdocument"></div><div id="uploadifive-file_upload4-queue" class="uploadifive-queue"></div>
                                        <div class="column-right margin_bottom20" id="queue4"></div>
                                    </div>
                                </div><div class="form-group">
                                    <label for="inputEmail3" class="col-sm-5 control-label">6. เอกสาร ประวัติความรู้ความชำนาญนักวิจัยและผู้ร่วมวิจัย</label>
                                    <div class="col-sm-5">
                                        <input name="file_title" id="file_title5" type="hidden" value="ประวัติความรู้ความชำนาญนักวิจัยและผู้ร่วมวิจัย">
                                        <div id="uploadifive-file_upload5" class="uploadifive-button" style="height: 30px; line-height: 30px; overflow: hidden; position: relative; text-align: center; width: 100px;">Select Files<input id="file_upload5" name="file_upload" type="file" class="file_upload" multiple="" style="display:none"><input type="file" style="font-size: 30px; opacity: 0; position: absolute; right: -3px; top: -3px; z-index: 999;" multiple="multiple" accept="image/png,image/jpg,image/jpeg,image/gif,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/excel,application/vnd.ms-excel,application/x-excel,application/x-msexcel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain,application/vnd.oasis.opendocument.text,application/vnd.oasis.opendocument.spreadsheet,application/vnd.ms-xpsdocument"></div><div id="uploadifive-file_upload5-queue" class="uploadifive-queue"></div>
                                        <div class="column-right margin_bottom20" id="queue5"></div>
                                    </div>
                                </div><div class="form-group">
                                    <label for="inputEmail3" class="col-sm-5 control-label">7. เอกสาร หลักฐานการอบรมจริยธรรมฯ</label>
                                    <div class="col-sm-5">
                                        <input name="file_title" id="file_title6" type="hidden" value="หลักฐานการอบรมจริยธรรมฯ">
                                        <div id="uploadifive-file_upload6" class="uploadifive-button" style="height: 30px; line-height: 30px; overflow: hidden; position: relative; text-align: center; width: 100px;">Select Files<input id="file_upload6" name="file_upload" type="file" class="file_upload" multiple="" style="display:none"><input type="file" style="font-size: 30px; opacity: 0; position: absolute; right: -3px; top: -3px; z-index: 999;" multiple="multiple" accept="image/png,image/jpg,image/jpeg,image/gif,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/excel,application/vnd.ms-excel,application/x-excel,application/x-msexcel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain,application/vnd.oasis.opendocument.text,application/vnd.oasis.opendocument.spreadsheet,application/vnd.ms-xpsdocument"></div><div id="uploadifive-file_upload6-queue" class="uploadifive-queue"></div>
                                        <div class="column-right margin_bottom20" id="queue6"></div>
                                    </div>
                                </div><div class="form-group">
                                    <label for="inputEmail3" class="col-sm-5 control-label">8. เอกสาร เครื่องมือที่ใช้ในการวิจัย</label>
                                    <div class="col-sm-5">
                                        <input name="file_title" id="file_title7" type="hidden" value="เครื่องมือที่ใช้ในการวิจัย">
                                        <div id="uploadifive-file_upload7" class="uploadifive-button" style="height: 30px; line-height: 30px; overflow: hidden; position: relative; text-align: center; width: 100px;">Select Files<input id="file_upload7" name="file_upload" type="file" class="file_upload" multiple="" style="display:none"><input type="file" style="font-size: 30px; opacity: 0; position: absolute; right: -3px; top: -3px; z-index: 999;" multiple="multiple" accept="image/png,image/jpg,image/jpeg,image/gif,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/excel,application/vnd.ms-excel,application/x-excel,application/x-msexcel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain,application/vnd.oasis.opendocument.text,application/vnd.oasis.opendocument.spreadsheet,application/vnd.ms-xpsdocument"></div><div id="uploadifive-file_upload7-queue" class="uploadifive-queue"></div>
                                        <div class="column-right margin_bottom20" id="queue7"></div>
                                    </div>
                                </div><div class="form-group">
                                    <label for="inputEmail3" class="col-sm-5 control-label">9. เอกสาร เอกสารอื่นๆ เพื่อประกอบการพิจารณา</label>
                                    <div class="col-sm-5">
                                        <input name="file_title" id="file_title8" type="hidden" value="เอกสารอื่นๆ เพื่อประกอบการพิจารณา">
                                        <div id="uploadifive-file_upload8" class="uploadifive-button" style="height: 30px; line-height: 30px; overflow: hidden; position: relative; text-align: center; width: 100px;">Select Files<input id="file_upload8" name="file_upload" type="file" class="file_upload" multiple="" style="display:none"><input type="file" style="font-size: 30px; opacity: 0; position: absolute; right: -3px; top: -3px; z-index: 999;" multiple="multiple" accept="image/png,image/jpg,image/jpeg,image/gif,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/excel,application/vnd.ms-excel,application/x-excel,application/x-msexcel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain,application/vnd.oasis.opendocument.text,application/vnd.oasis.opendocument.spreadsheet,application/vnd.ms-xpsdocument"></div><div id="uploadifive-file_upload8-queue" class="uploadifive-queue"></div>
                                        <div class="column-right margin_bottom20" id="queue8"></div>
                                    </div>
                                </div><div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-sm-5 col-md-offset-4">
                                            <p><a class="btn btn-primary" href="javascript:$('.file_upload').uploadifive('upload')">Upload Files</a></p>
                                        </div>
                                    </div>  
                                </div>

                                <div class="col-sm-12" id="alert_upload">
                                </div>
                                <div class="column-right margin_bottom20" id="queue"></div>
                                <div id="show_num"></div>
                                <div id="show" class="col-xs-12 ui-sortable"></div>
                            </div>
                        </div>
                        <div class="tab-pane" id="exampleTabsLineLeftThree" role="tabpanel">
                            <div class="kv-panel-before">    <div class="pull-right">
                                    <div class="btn-toolbar kv-grid-toolbar" role="toolbar">
                                        <div class="btn-group"><a class="btn btn-success btn-lg waves-effect waves-light" href="/survey/content/create" title="เพิ่มกรรมการและเลขา" data-pjax="0"><i class="glyphicon glyphicon-plus"></i> เพิ่มกรรมการและเลขา</a><a class="btn btn-default btn-lg waves-effect waves-light" href="/survey/content/index" title="โหลดใหม่" data-pjax="1"><i class="glyphicon glyphicon-repeat"></i> โหลดใหม่</a></div>
                                    </div>    
                                </div>
                                <div class="summary">แสดง <b>1 ถึง 20</b> จาก <b>4</b> ผลลัพธ์</div>
                                <div class="clearfix"></div></div>

                            <table class="kv-grid-table table table-bordered table-striped table-condensed kv-table-wrap"><colgroup><col style="width: 72px;">

                                <thead><tr class="size-row" ><th class="floatThead-col" >#</th><th class="floatThead-col" >ชื่อ-สกุล</th><th class="floatThead-col" >สังกัดหน่วยงาน</th><th class="floatThead-col" >สถานะ</th><th class="floatThead-col">จัดการข้อมูล</th></tr></thead><tbody>
                                    <tr data-key="11"><td class="kv-align-center kv-align-middle" style="width:30px;" data-col-seq="0">1</td><td data-col-seq="1">รศ.ดร.รมณ  หาทวายการ</td><td data-col-seq="2">คณะแพทยศาตร์ มหาวิทยาลัยขอนแก่น</td><td data-col-seq="3">กรรมการ</td><td class="skip-export kv-align-center kv-nowrap kv-align-middle" style="width:80px;" data-col-seq="4"><a class="btn btn-success waves-effect waves-light" href="/survey/content/view?id=11" title="" role="modal-remote" data-toggle="tooltip" data-original-title="รายละเอียด"><i class="glyphicon glyphicon-eye-open"></i></a> <a class="btn btn-info waves-effect waves-light" href="/survey/content/update?id=11" data-request-method="post" data-toggle="tooltip" data-pjax="0" data-original-title="" title=""><i class="glyphicon glyphicon-pencil"></i></a> <a class="btn btn-danger waves-effect waves-light" href="/survey/content/delete?id=11" title="" role="modal-remote" data-request-method="post" data-toggle="tooltip" data-confirm-title="ยืนยันการลบ" data-confirm-message="ต้องการลบรายการนี้ใช่หรือไม่ ?" data-confirm-ok="ใช่" data-confirm-cancel="ไม่" data-original-title="ลบ"><i class="glyphicon glyphicon-trash"></i></a></td></tr>
                                    <tr data-key="10"><td class="kv-align-center kv-align-middle" style="width:30px;" data-col-seq="0">2</td><td data-col-seq="1">ดร.กนกกานต์ รัตนวารี</td><td data-col-seq="2">อายุรกรรม โรงพยาบาลศรีนครินทร์ มหาวิทยาลัยขอนแก่น</td><td data-col-seq="3">กรรมการ</td><td class="skip-export kv-align-center kv-nowrap kv-align-middle" style="width:80px;" data-col-seq="4"><a class="btn btn-success waves-effect waves-light" href="/survey/content/view?id=10" title="" role="modal-remote" data-toggle="tooltip" data-original-title="รายละเอียด"><i class="glyphicon glyphicon-eye-open"></i></a> <a class="btn btn-info waves-effect waves-light" href="/survey/content/update?id=10" data-request-method="post" data-toggle="tooltip" data-pjax="0" data-original-title="" title=""><i class="glyphicon glyphicon-pencil"></i></a> <a class="btn btn-danger waves-effect waves-light" href="/survey/content/delete?id=10" title="" role="modal-remote" data-request-method="post" data-toggle="tooltip" data-confirm-title="ยืนยันการลบ" data-confirm-message="ต้องการลบรายการนี้ใช่หรือไม่ ?" data-confirm-ok="ใช่" data-confirm-cancel="ไม่" data-original-title="ลบ"><i class="glyphicon glyphicon-trash"></i></a></td></tr>
                                </tbody><fthfoot style="display:table-footer-group;border-spacing:0;height:0;border-collapse:collapse;visibility:hidden"><fthtr style="display:table-row;border-spacing:0;height:0;border-collapse:collapse"><fthtd style="display:table-cell;height:0;width:auto;"></fthtd><fthtd style="display:table-cell;height:0;width:auto;"></fthtd><fthtd style="display:table-cell;height:0;width:auto;"></fthtd><fthtd style="display:table-cell;height:0;width:auto;"></fthtd><fthtd style="display:table-cell;height:0;width:auto;"></fthtd></fthtr></fthfoot></table>
                            <div class="kv-panel-after"></div>
                            <div class="panel-footer">    <div class="kv-panel-pager">
                                    <ul class="pagination"><li class="first disabled"><span><i class="icon md-skip-previous"></i></span></li>
                                        <li class="prev disabled"><span><i class="icon md-fast-rewind"></i></span></li>
                                        <li class="active"><a href="/survey/content/index?page=1" data-page="0">1</a></li>
                                        <li><a href="/survey/content/index?page=2" data-page="1">2</a></li>
                                        <li class="next"><a href="/survey/content/index?page=2" data-page="1"><i class="icon md-fast-forward"></i></a></li>
                                        <li class="last"><a href="/survey/content/index?page=2" data-page="1"><i class="icon md-skip-next"></i></a></li></ul>
                                </div>

                                <div class="clearfix"></div></div>

                        </div>
                        <div class="tab-pane" id="exampleTabsLineLeftFour" role="tabpanel">
                            <div class="kv-panel-before">    <div class="pull-right">
                                    <div class="btn-toolbar kv-grid-toolbar" role="toolbar">
                                        <div class="btn-group"><a class="btn btn-success btn-lg waves-effect waves-light" href="/survey/content/create" title="บรรจุวาระการประชุม" data-pjax="0"><i class="glyphicon glyphicon-plus"></i> บรรจุวาระการประชุม</a><a class="btn btn-default btn-lg waves-effect waves-light" href="/survey/content/index" title="โหลดใหม่" data-pjax="1"><i class="glyphicon glyphicon-repeat"></i> โหลดใหม่</a></div>
                                    </div>    
                                </div>
                                <div class="summary">แสดง <b>1 ถึง 20</b> จาก <b>1</b> ผลลัพธ์</div>
                                <div class="clearfix"></div></div>

                            <table class="kv-grid-table table table-bordered table-striped table-condensed kv-table-wrap"><colgroup><col style="width: 72px;">

                                <thead><tr class="size-row" ><th class="floatThead-col" >#</th><th class="floatThead-col" >การประชุม</th><th class="floatThead-col" >วันที่ประชุม</th><th class="floatThead-col" >สถานะ</th><th class="floatThead-col">จัดการข้อมูล</th></tr></thead><tbody>
                                    <tr data-key="11"><td class="kv-align-center kv-align-middle" style="width:30px;" data-col-seq="0">1</td><td data-col-seq="1">12/2560</td><td data-col-seq="2">12/10/2560</td><td data-col-seq="3">ปิดประชุม</td><td class="skip-export kv-align-center kv-nowrap kv-align-middle" style="width:80px;" data-col-seq="4"><a class="btn btn-success waves-effect waves-light" href="/survey/content/view?id=11" title="" role="modal-remote" data-toggle="tooltip" data-original-title="รายละเอียด"><i class="glyphicon glyphicon-eye-open"></i></a> <a class="btn btn-info waves-effect waves-light" href="/survey/content/update?id=11" data-request-method="post" data-toggle="tooltip" data-pjax="0" data-original-title="" title=""><i class="glyphicon glyphicon-pencil"></i></a> <a class="btn btn-danger waves-effect waves-light" href="/survey/content/delete?id=11" title="" role="modal-remote" data-request-method="post" data-toggle="tooltip" data-confirm-title="ยืนยันการลบ" data-confirm-message="ต้องการลบรายการนี้ใช่หรือไม่ ?" data-confirm-ok="ใช่" data-confirm-cancel="ไม่" data-original-title="ลบ"><i class="glyphicon glyphicon-trash"></i></a></td></tr>
                                </tbody><fthfoot style="display:table-footer-group;border-spacing:0;height:0;border-collapse:collapse;visibility:hidden"><fthtr style="display:table-row;border-spacing:0;height:0;border-collapse:collapse"><fthtd style="display:table-cell;height:0;width:auto;"></fthtd><fthtd style="display:table-cell;height:0;width:auto;"></fthtd><fthtd style="display:table-cell;height:0;width:auto;"></fthtd><fthtd style="display:table-cell;height:0;width:auto;"></fthtd><fthtd style="display:table-cell;height:0;width:auto;"></fthtd></fthtr></fthfoot></table>
                            <div class="kv-panel-after"></div>
                            <div class="panel-footer">    <div class="kv-panel-pager">
                                    <ul class="pagination"><li class="first disabled"><span><i class="icon md-skip-previous"></i></span></li>
                                        <li class="prev disabled"><span><i class="icon md-fast-rewind"></i></span></li>
                                        <li class="active"><a href="/survey/content/index?page=1" data-page="0">1</a></li>
                                        <li><a href="/survey/content/index?page=2" data-page="1">2</a></li>
                                        <li class="next"><a href="/survey/content/index?page=2" data-page="1"><i class="icon md-fast-forward"></i></a></li>
                                        <li class="last"><a href="/survey/content/index?page=2" data-page="1"><i class="icon md-skip-next"></i></a></li></ul>
                                </div>

                                <div class="clearfix"></div></div>
                        </div>
                        <div class="tab-pane" id="exampleTabsLineLeftFive" role="tabpanel">
                            <div class="kv-panel-before">    <div class="pull-right">
                                    <div class="btn-toolbar kv-grid-toolbar" role="toolbar">
                                        <div class="btn-group"><a class="btn btn-default btn-lg waves-effect waves-light" href="/survey/content/index" title="โหลดใหม่" data-pjax="1"><i class="glyphicon glyphicon-repeat"></i> โหลดใหม่</a></div>
                                    </div>    
                                </div>
                                <div class="summary">แสดง <b>1 ถึง 20</b> จาก <b>1</b> ผลลัพธ์</div>
                                <div class="clearfix"></div></div>

                            <table class="kv-grid-table table table-bordered table-striped table-condensed kv-table-wrap"><colgroup><col style="width: 72px;">

                                <thead><tr class="size-row" ><th class="floatThead-col" >#</th><th class="floatThead-col" >การดำเนินการ</th><th class="floatThead-col" >ผู้บันทึกข้อมูล</th><th class="floatThead-col" >การจัดการ</th></tr></thead><tbody>
                                    <tr data-key="11"><td class="kv-align-center kv-align-middle" style="width:30px;" data-col-seq="0">1</td><td data-col-seq="1">ตรวจสอบเอกสาร</td><td data-col-seq="2">ชาณิภา  หาทวายการ</td><td class="skip-export kv-align-center kv-nowrap kv-align-middle" style="width:80px;" data-col-seq="4"><a class="btn btn-success waves-effect waves-light" href="/survey/content/view?id=11" title="" role="modal-remote" data-toggle="tooltip" data-original-title="รายละเอียด"><i class="glyphicon glyphicon-eye-open"></i></a> </td></tr>
                                </tbody><fthfoot style="display:table-footer-group;border-spacing:0;height:0;border-collapse:collapse;visibility:hidden"><fthtr style="display:table-row;border-spacing:0;height:0;border-collapse:collapse"><fthtd style="display:table-cell;height:0;width:auto;"></fthtd><fthtd style="display:table-cell;height:0;width:auto;"></fthtd><fthtd style="display:table-cell;height:0;width:auto;"></fthtd><fthtd style="display:table-cell;height:0;width:auto;"></fthtd><fthtd style="display:table-cell;height:0;width:auto;"></fthtd></fthtr></fthfoot></table>
                            <div class="kv-panel-after"></div>
                            <div class="panel-footer">    <div class="kv-panel-pager">
                                    <ul class="pagination"><li class="first disabled"><span><i class="icon md-skip-previous"></i></span></li>
                                        <li class="prev disabled"><span><i class="icon md-fast-rewind"></i></span></li>
                                        <li class="active"><a href="/survey/content/index?page=1" data-page="0">1</a></li>
                                        <li><a href="/survey/content/index?page=2" data-page="1">2</a></li>
                                        <li class="next"><a href="/survey/content/index?page=2" data-page="1"><i class="icon md-fast-forward"></i></a></li>
                                        <li class="last"><a href="/survey/content/index?page=2" data-page="1"><i class="icon md-skip-next"></i></a></li></ul>
                                </div>

                                <div class="clearfix"></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

</div>