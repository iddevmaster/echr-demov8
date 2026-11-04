<?php

use yii\widgets\DetailView;
use yii\redactor\widgets\Redactor;
use yii\redactor\widgets\RedactorAsset;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SubmissionCommittee */
?>
<div class="submission-committee-view">

    <div class="well">
        <table width="100%" class="table borderless">
            <tbody><tr>
                    <td colspan="2" align="center"><strong>กรรมการผู้ประเมิน</strong></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        ศ.นพ.รมณ หาทวายการ  </td>
                </tr>
                <tr bordercolor="#000000">
                    <td width="50%" align="right"><strong><strong>กรุณาส่งแบบประเมินก่อนวันที่ </strong></strong></td>
                    <td width="50%"><div class="form-group input-group col-xs-8 input-append date">
                            <input name="start_date[]" type="text" class="form-control" id="start_date" value="31/05/2016"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div></td>
                </tr>
      
                <tr bordercolor="#000000">
                    <td colspan="2"><strong>อัพแบบประเมิน</strong></td>
                </tr>
                <tr bordercolor="#000000">
                    <td colspan="2">

                        <div class="col-lg-12">
                            <div class="col-lg-6">
                                <div id="uploadifive-file_upload1" class="uploadifive-button" style="height: 30px; line-height: 30px; overflow: hidden; position: relative; text-align: center; width: 100px;">Select Files<input id="file_upload1" name="file_upload1" type="file" multiple="true" style="display:none"><input type="file" style="font-size: 30px; opacity: 0; position: absolute; right: -3px; top: -3px; z-index: 999;" multiple="multiple"></div><div id="uploadifive-file_upload1-queue" class="uploadifive-queue"></div>
                                <a style="position: relative; top: 8px;" href="javascript:$('#file_upload1').uploadifive('upload')" class="btn btn-primary"><i class="fa fa-upload"></i> Upload Files</a>
                            </div>

                        </div>
                    </td>
                </tr>
                <tr bordercolor="#000000">
                    <td colspan="2"><p><strong>สรุปความเห็นโดยรวม ท่านเห็นชอบให้ดำเนินการวิจัยโครงการวิจัยนี้หรือไม่</strong></p>
                        <?=
                        Redactor::widget([
                            'model' => $model,
                            'attribute' => 'status'
                        ])
                        ?>
                    </td>
                </tr> 

                <tr bordercolor="#000000">
                    <td colspan="2"><p dir="ltr" id="docs-internal-guid-608896ec-3cd0-aa51-281e-11ad472a"><strong>หากเป็นโครงการวิจัยที่เกี่ยวข้องกับเครื่องมือแพทย์</strong></p>
                        <div class="checkbox">
                            <label>
                                <input name="medical_devices[0][]" type="checkbox" id="medical_devices[]" value="1">Non-significant risk
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input name="medical_devices[0][]" type="checkbox" id="medical_devices[]" value="2">Significant risk (เครื่องมือหรืออุปกรณ์ที่มีความเสี่ยงสูงต่อการเสียชีวิต หรือการเกิดความพิการอย่างถาวร หรือต้องอาศัยการผ่าตัดเพื่อฝังหรือสอดใส่อุปกรณ์ดังกล่าวเข้าไปในร่างกาย หรือต้องใช้ร่วมกับยาหรือสารบางชนิดไปตลอดชีวิตเพื่อป้องกันการล้มเหลวของอุปกรณ์ และยาหรือสารเคมีเหล่านั้นอาจทำให้เสียชีวิตหรือเกิดพิษต่อร่างกายหรือเกิดความพิการอย่างถาวร)
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input name="medical_devices[0][]" type="checkbox" id="medical_devices[]" value="3">ได้ขึ้นทะเบียนการวิจัยกบหน่วยงาน USFDA / MDD หรือหน่วยงานที่เกี่ยวข้อง และมีหลักฐานหรือข้อมูลประกอบ
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input name="medical_devices[0][]" type="checkbox" id="medical_devices[]" value="4">ยังไม่ได้ขึ้นทะเบียนการวิจัยกบหน่วยงาน USFDA / MDD หรือหน่วยงานที่เกี่ยวข้อง หรือยังไม่มีหลักฐานหรือข้อมูล
                            </label>
                        </div>
                    </td>
                </tr>
                <tr bordercolor="#000000">
                    <td colspan="2">
                        <p><strong>ประเมินความเสี่ยงของโครงการ</strong></p>
                        <select name="committee_detail[0][]" class="form-control" id="hazard0">
                            <option value="" selected="selected">------------</option>
                            <option value="มีความเสี่ยงไม่เกินความเสี่ยงเล็กน้อย" selected="selected">มีความเสี่ยงไม่เกินความเสี่ยงเล็กน้อย</option><option value="มีความเสี่ยงเกินกว่าความเสี่ยงเล็กน้อย แต่มีประโยชน์ต่อตัวอาสาสมัครโดยตรง">มีความเสี่ยงเกินกว่าความเสี่ยงเล็กน้อย แต่มีประโยชน์ต่อตัวอาสาสมัครโดยตรง</option><option value="มีความเสี่ยงเกินกว่าความเสี่ยงเล็กน้อย และไม่มีประโยชน์ต่อตัวอาสาสมัครโดยตรง แต่มีความเป็นไปได้ที่จะได้รับความรู้เกี่ยวกับโรคหรือสภาวะที่อาสาสมัครเป็น">มีความเสี่ยงเกินกว่าความเสี่ยงเล็กน้อย และไม่มีประโยชน์ต่อตัวอาสาสมัครโดยตรง แต่มีความเป็นไปได้ที่จะได้รับความรู้เกี่ยวกับโรคหรือสภาวะที่อาสาสมัครเป็น</option><option value="มีความเสี่ยงและประโยชน์ไม่ตรงกับที่กล่าวมาแล้วทั้งสามข้อ แต่อาจมีโอกาสที่จะเข้าใจหรือป้องกันหรือบรรเทาปัญหาร้ายแรงที่กระทบสุขภาพและความเป็นอยู่ที่ดีของอาสาสมัคร">มีความเสี่ยงและประโยชน์ไม่ตรงกับที่กล่าวมาแล้วทั้งสามข้อ แต่อาจมีโอกาสที่จะเข้าใจหรือป้องกันหรือบรรเทาปัญหาร้ายแรงที่กระทบสุขภาพและความเป็นอยู่ที่ดีของอาสาสมัคร</option>          
                        </select>
                        <input name="tag_committee[0][]" type="hidden" id="tag_committee[0][]" value="hazard"></td>
                </tr>
                <tr bordercolor="#000000">
                    <td><strong>ระยะเวลาการรายงานความก้าวหน้าของโครงการวิจัย </strong></td>
                    <td width="50%"><select name="time_report[]" class="form-control" id="time_report0">
                            <option value=""></option>
                            <option value="3">3 เดือน</option>
                            <option value="6">6 เดือน</option>
                            <option value="12" selected="selected">12 เดือน</option>
                        </select></td>
                </tr>
                <tr bordercolor="#000000">
                    <td colspan="2"><strong>ข้อเสนอแนะอื่นๆ</strong>
                        <?=
                        Redactor::widget([
                            'model' => $model,
                            'attribute' => 'remark'
                        ])
                        ?>
                    </td>
                </tr>

            <input type="hidden" name="research_committee_id[]" id="research_committee_id" value="85">        <tr bordercolor="#000000">
                <td align="right"><strong><strong>ส่งอีเมล์ </strong></strong></td>
                <td>
                    <label><input name="send_email[0]" type="radio" id="send_email_0" value="yes"> บันทึกพร้อมส่งเมล์</label>
                    <label><input type="radio" name="send_email[0]" value="no" id="send_email_1" checked=""> บันทึกไม่ส่งเมล์</label>
                </td>
            </tr>
            </tbody></table>
    </div>

</div>
