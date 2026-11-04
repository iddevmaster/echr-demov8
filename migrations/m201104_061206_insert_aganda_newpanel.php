<?php

use yii\db\Migration;

/**
 * Class m201104_061206_insert_aganda_newpanel
 */
class m201104_061206_insert_aganda_newpanel extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {

        $this->insert('panel', ['name' => 'กลุ่มของผู้ปฏิบัติงาน 4']);

        $this->insert('agenda', ['name' => 'โครงการวิจัยที่เข้าข่ายการพิจารณาแบบยกเว้น แต่มีการขอแก้ไขเพิ่มเติมการดำเนินการวิจัย',
            'sort' => 7, 'parent_id' => 3, 'label' => '3.7']);
        $this->insert('agenda', ['name' => 'โครงการวิจัยที่เข้าข่ายการพิจารณาแบบเร็วผ่านการรับรองแล้วครบ 1 ปี และขอรับการรับรองต่อ',
            'sort' => 8, 'parent_id' => 3, 'label' => '3.8']);
        $this->insert('agenda', ['name' => 'โครงการวิจัยใหม่ที่อยู่ภายใต้ MOU',
            'sort' => 9, 'parent_id' => 3, 'label' => '3.9']);

        $this->insert('agenda_submission_type', ['agenda_id' => 19, 'submission_type_id' => 9]);
        $this->insert('agenda_submission_type', ['agenda_id' => 20, 'submission_type_id' => 8]);
        $this->insert('agenda_submission_type', ['agenda_id' => 21, 'submission_type_id' => 3]);
        $this->insert('agenda_submission_type', ['agenda_id' => 21, 'submission_type_id' => 4]);
        $this->insert('agenda_submission_type', ['agenda_id' => 21, 'submission_type_id' => 17]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m201104_061206_insert_aganda_newpanel cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m201104_061206_insert_aganda_newpanel cannot be reverted.\n";

      return false;
      }
     */
}
