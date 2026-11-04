<?php

use yii\db\Migration;

/**
 * Class m180217_052905_insert_agenda
 */
class m180217_052905_insert_agenda extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->insert('submission_type_group', ['id' => 1, 'name' => 'โครงการใหม่']);
        $this->insert('submission_type_group', ['id' => 2, 'name' => 'โครงการวิจัยที่ผ่านการรับรองแล้ว']);
        
        $this->insert('submission_type', ['id' => 1, 'name' => 'โครงการทางคลินิก'
            , 'submission_type_group_id' => 1, 'is_fullboard' => TRUE, 'is_new' => TRUE, 'resolution_label' => 'รับรอง']);
        $this->insert('submission_type', ['id' => 2, 'name' => 'โครงการทางสังคมฯ'
            , 'submission_type_group_id' => 1, 'is_fullboard' => TRUE, 'is_new' => TRUE, 'resolution_label' => 'รับรอง']);
        $this->insert('submission_type', ['id' => 3, 'name' => 'เข้าข่ายขอยกเว้นการพิจารณา'
            , 'submission_type_group_id' => 1, 'is_fullboard' => FALSE, 'is_exemption' => TRUE, 'is_new' => FALSE, 'resolution_label' => 'รับทราบ']);
        $this->insert('submission_type', ['id' => 4, 'name' => 'เข้าข่ายการพิจารณาแบบเร็ว'
            , 'submission_type_group_id' => 1, 'is_fullboard' => FALSE, 'is_new' => TRUE, 'resolution_label' => 'รับรอง']);
//        $this->insert('submission_type', ['id' => 5, 'name' => 'โครงการวิจัยใหม่ที่เข้าข่ายการพิจารณาแบบเร็ว แต่ประธานขอให้ที่ประชุมพิจารณา (Request for Full Board Consideration)'
//            , 'submission_type_group_id' => 1, 'is_fullboard' => FALSE, 'is_new' => TRUE, 'internal' => TRUE, 'resolution_label' => 'รับรอง']);
        $this->insert('submission_type', ['id' => 5, 'name' => 'โครงการวิจัยใหม่ที่เคยเสนอเข้าพิจารณา และจะรับรองหลังจากการแก้ไขตามมติที่ประชุม'
            , 'submission_type_group_id' => 1, 'is_fullboard' => FALSE, 'is_new' => TRUE, 'internal' => TRUE, 'resolution_label' => 'รับรอง']);
        $this->insert('submission_type', ['id' => 6, 'name' => 'โครงการวิจัยใหม่ที่เคยเสนอเข้าพิจารณา และที่ประขุมมีมติให้ชี้แจงเพิ่มเติม (New Protocol, Previously tabled)'
            , 'submission_type_group_id' => 1, 'is_fullboard' => FALSE, 'is_new' => TRUE, 'internal' => TRUE, 'resolution_label' => 'รับรอง']);
        $this->insert('submission_type', ['id' => 7, 'name' => 'รายงานความก้าวหน้า (กรณีให้รายงาน 3/ 6 เดือน)'
            , 'submission_type_group_id' => 2, 'is_fullboard' => FALSE, 'is_new' => FALSE, 'resolution_label' => 'รับทราบ']);
        $this->insert('submission_type', ['id' => 8, 'name' => 'ขอต่ออายุการรับรอง (Renew)'
            , 'submission_type_group_id' => 2, 'is_fullboard' => FALSE, 'is_new' => FALSE, 'resolution_label' => 'รับรอง']);
        $this->insert('submission_type', ['id' => 9, 'name' => 'ขอปรับปรุงเอกสาร/เพิ่มเติมเอกสาร (Amendment)'
            , 'submission_type_group_id' => 2, 'is_fullboard' => FALSE, 'is_new' => FALSE, 'resolution_label' => 'รับรอง']);
        $this->insert('submission_type', ['id' => 10, 'name' => 'รายงานเหตุการณ์ไม่พึงประสงค์ร้ายแรง (SAE) ในสถาบัน'
            , 'submission_type_group_id' => 2, 'is_fullboard' => FALSE, 'is_new' => FALSE, 'resolution_label' => 'รับทราบ']);
        $this->insert('submission_type', ['id' => 11, 'name' => 'รายงานเหตุการณ์ไม่พึงประสงค์ร้ายแรง (SAE) นอกสถาบัน'
            , 'submission_type_group_id' => 2, 'is_fullboard' => FALSE, 'is_new' => FALSE, 'resolution_label' => 'รับทราบ']);
        $this->insert('submission_type', ['id' => 12, 'name' => 'แจ้งการดำเนินการเบี่ยงเบน (Deviation)'
            , 'agenda_title' => 'รายงานการดำเนินการวิจัยที่เบี่ยงเบนจากที่โครงการวิจัยที่คณะกรรมการให้การรับรอง (Protocol Deviation/Violation)'
            , 'submission_type_group_id' => 2, 'is_fullboard' => FALSE, 'is_new' => FALSE, 'resolution_label' => 'รับทราบ']);
        $this->insert('submission_type', ['id' => 13, 'name' => 'แจ้งปิดโครงการ (Closing)'
            , 'submission_type_group_id' => 2, 'is_fullboard' => FALSE, 'is_new' => FALSE, 'resolution_label' => 'รับทราบ']);
//        $this->insert('submission_type', ['id' => 1, 'name' => 'โครงการวิจัยใหม่ทางคลินิก (New Clinical Study Protocol)'
//            , 'submission_type_group_id' => 1, 'is_fullboard' => TRUE, 'is_new' => TRUE, 'resolution_label' => 'รับรอง']);
//        $this->insert('submission_type', ['id' => 2, 'name' => 'โครงการวิจัยใหม่ทางสังคมศาสตร์ และพฤติกรรมสุขภาพ (New  Social Science Study Protocol)'
//            , 'submission_type_group_id' => 1, 'is_fullboard' => TRUE, 'is_new' => TRUE, 'resolution_label' => 'รับรอง']);
//        $this->insert('submission_type', ['id' => 3, 'name' => 'โครงการวิจัยใหม่ที่เข้าข่ายไม่ต้องขอรับพิจารณาจริยธรรมการวิจัย Exemption Research'
//            , 'submission_type_group_id' => 1, 'is_fullboard' => FALSE, 'is_exemption' => TRUE, 'is_new' => FALSE, 'resolution_label' => 'รับทราบ']);
//        $this->insert('submission_type', ['id' => 4, 'name' => 'โครงการวิจัยใหม่ที่เข้าข่ายการขอรับพิจารณาแบบเร็ว (Expedited Research)'
//            , 'submission_type_group_id' => 1, 'is_fullboard' => FALSE, 'is_new' => FALSE, 'resolution_label' => 'รับทราบ']);
//        $this->insert('submission_type', ['id' => 5, 'name' => 'รายงานความก้าวหน้าในการดำเนินงาน (Continuing Report)'
//            , 'submission_type_group_id' => 2, 'is_fullboard' => FALSE, 'is_new' => FALSE, 'resolution_label' => 'รับทราบ']);
//        $this->insert('submission_type', ['id' => 6, 'name' => 'โครงการวิจัยที่ผ่านการรับรองแล้วครบ 1 ปี และขอรับการรับรองต่อ (Renew of Previously Approved Protocol)'
//            , 'submission_type_group_id' => 2, 'is_fullboard' => TRUE, 'is_new' => FALSE, 'resolution_label' => 'รับรอง']);
//        $this->insert('submission_type', ['id' => 7, 'name' => 'โครงการวิจัยที่ผ่านการรับรองแล้ว แต่ผู้วิจัยขอปรับแก้ไขการดำเนินการวิจัย หรือขอรับรองเอกสารเพิ่ม (Protocol Amendment or Subject’s materials)'
//            , 'submission_type_group_id' => 2, 'is_fullboard' => TRUE, 'is_new' => FALSE, 'resolution_label' => 'รับรอง']);
        
        $this->insert('agenda', ['id' => 1, 'name' => 'รับรองรายงานการประชุม', 'sort' => 1, 'label' => '1']);
        $this->insert('agenda', ['id' => 2, 'name' => 'เรื่องแจ้งเพื่อทราบ', 'sort' => 2, 'label' => '2']);
        $this->insert('agenda', ['id' => 3, 'name' => 'เรื่องแจ้งเพื่อทราบเกี่ยวกับโครงการวิจัย', 'sort' => 3, 'label' => '3']);
        $this->insert('agenda', ['id' => 4, 'name' => 'เรื่องเสนอเพื่อพิจารณาที่เกี่ยวข้องกับโครงการวิจัย', 'sort' => 4, 'label' => '4']);
        $this->insert('agenda', ['id' => 5, 'name' => 'เรื่องอื่นๆ', 'sort' => 5, 'label' => '5']);
        
        $this->insert('agenda', ['id' => 6, 'name' => 'รายงานการแจ้งปิดโครงการ การแจ้งยกเลิกโครงการ (Closing Protocol)', 'sort' => 1, 'parent_id' => 3, 'label' => '3.1']);
        $this->insert('agenda', ['id' => 7, 'name' => 'รายงานความก้าวหน้าในการดำเนินงาน (Continuing Report)', 'sort' => 2, 'parent_id' => 3, 'label' => '3.2']);
        $this->insert('agenda', ['id' => 8, 'name' => 'โครงการวิจัยใหม่ที่เข้าข่ายไม่ต้องขอรับพิจารณาจริยธรรมการวิจัย Exemption Research', 'sort' => 3, 'parent_id' => 3, 'label' => '3.3']);
        $this->insert('agenda', ['id' => 9, 'name' => 'โครงการวิจัยใหม่ที่เข้าข่ายการขอรับพิจารณาแบบเร็ว และประธานให้ความเห็นชอบ (Expedited Research)', 'sort' => 4, 'parent_id' => 3, 'label' => '3.4']);
        $this->insert('agenda', ['id' => 10, 'name' => 'รายงานผลข้างเคียงหรือเหตุการณ์ไม่พึงประสงค์ที่เกี่ยวข้องกับโครงการวิจัย (SAE Report)', 'sort' => 5, 'parent_id' => 3, 'label' => '3.5']);
        $this->insert('agenda', ['id' => 11, 'name' => 'โครงการที่ผ่านการรับรองแล้วแต่มีเอกสารเพิ่มเติมที่สามารถขอรับการรับรองแบบเร็ว', 'sort' => 6, 'parent_id' => 3, 'label' => '3.6']);
        
        $this->insert('agenda', ['id' => 12, 'name' => 'โครงการวิจัยใหม่ที่เข้าข่ายการพิจารณาแบบเร็ว แต่ประธานขอให้ที่ประชุมพิจารณา (Request for Full Board Consideration)', 
            'sort' => 1, 'parent_id' => 4, 'label' => '4.1']);
        $this->insert('agenda', ['id' => 13, 'name' => 'โครงการวิจัยใหม่ทางคลินิก (New Clinical Study Protocol)', 
            'sort' => 2, 'parent_id' => 4, 'label' => '4.2']);
        $this->insert('agenda', ['id' => 14, 'name' => 'โครงการวิจัยใหม่ทางสังคมศาสตร์ และพฤติกรรมสุขภาพ (New  Social Science Study Protocol)', 
            'sort' => 3, 'parent_id' => 4, 'label' => '4.3']);
        $this->insert('agenda', ['id' => 15, 'name' => 'โครงการวิจัยใหม่ที่เคยเสนอเข้าพิจารณา และที่ประขุมมีมติให้ชี้แจงเพิ่มเติม (New Protocol, Previously tabled)', 
            'sort' => 4, 'parent_id' => 4, 'label' => '4.4']);
        $this->insert('agenda', ['id' => 16, 'name' => 'โครงการวิจัยที่ผ่านการรับรองแล้วครบ 1 ปี และขอรับการรับรองต่อ (Renew of Previously Approved Protocol)', 
            'sort' => 5, 'parent_id' => 4, 'label' => '4.5']);
        $this->insert('agenda', ['id' => 17, 'name' => 'โครงการวิจัยที่ผ่านการรับรองแล้ว แต่ผู้วิจัยขอปรับแก้ไขการดำเนินการวิจัย หรือขอรับรองเอกสารเพิ่ม (Protocol Amendment or Subject’s materials)', 
            'sort' => 6, 'parent_id' => 4, 'label' => '4.6']);
        $this->insert('agenda', ['id' => 18, 'name' => 'รายงานอื่นๆ ที่ต้องการความเห็นจากที่ประชุม ', 
            'sort' => 7, 'parent_id' => 4, 'label' => '4.7']);
        
        $this->insert('agenda_submission_type', ['agenda_id' => 6, 'submission_type_id' => 13]);
        $this->insert('agenda_submission_type', ['agenda_id' => 7, 'submission_type_id' => 7]);
        $this->insert('agenda_submission_type', ['agenda_id' => 8, 'submission_type_id' => 3]);
        $this->insert('agenda_submission_type', ['agenda_id' => 9, 'submission_type_id' => 4]);
        $this->insert('agenda_submission_type', ['agenda_id' => 12, 'submission_type_id' => 4]);
//        $this->insert('agenda_submission_type', ['agenda_id' => 12, 'submission_type_id' => 5]);
        $this->insert('agenda_submission_type', ['agenda_id' => 10, 'submission_type_id' => 10]);
        $this->insert('agenda_submission_type', ['agenda_id' => 10, 'submission_type_id' => 11]);
        
        $this->insert('agenda_submission_type', ['agenda_id' => 11, 'submission_type_id' => 9]);
        $this->insert('agenda_submission_type', ['agenda_id' => 13, 'submission_type_id' => 1]);
        $this->insert('agenda_submission_type', ['agenda_id' => 14, 'submission_type_id' => 2]);
        $this->insert('agenda_submission_type', ['agenda_id' => 12, 'submission_type_id' => 5]);
        $this->insert('agenda_submission_type', ['agenda_id' => 15, 'submission_type_id' => 6]);
        $this->insert('agenda_submission_type', ['agenda_id' => 16, 'submission_type_id' => 8]);
        $this->insert('agenda_submission_type', ['agenda_id' => 17, 'submission_type_id' => 9]);
        $this->insert('agenda_submission_type', ['agenda_id' => 18, 'submission_type_id' => 12]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->delete('agenda_submission_type');
        $this->delete('agenda', ['not', ['parent_id' => NULL]]);
        $this->delete('agenda');
        $this->delete('submission_type');
        $this->delete('submission_type_group');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180217_052905_insert_agenda cannot be reverted.\n";

      return false;
      }
     */
}
