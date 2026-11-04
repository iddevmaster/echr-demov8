<?php

use yii\db\Migration;

/**
 * Class m250424_040706_add_table_committee_qualification
 */
class m250424_040706_add_table_committee_qualification extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('committee_qualification', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'name_eng' => $this->string(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('committee_qualification', 'id', 'รหัสอัตโนมัติ');
        $this->addCommentOnColumn('committee_qualification', 'name', 'ชื่อคุณสมบัติ');
        $this->addCommentOnColumn('committee_qualification', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('committee_qualification', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('committee_qualification', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('committee_qualification', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('committee_qualification', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_committee_qualification_name', 'committee_qualification', ['name']);
        $this->addForeignKey('fk_committee_qualification_user_created_by', 'committee_qualification', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_committee_qualification_user_updated_by', 'committee_qualification', 'updated_by', 'user', 'id');

        $this->createTable('committee_qualification_panel', [
            'id' => $this->primaryKey(),
            'committee_qualification_id' => $this->integer(),
            'panel_id' => $this->integer(),
            'name' => $this->string()->notNull(),
            'name_eng' => $this->string(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('committee_qualification_panel', 'id', 'รหัสอัตโนมัติ');
        $this->addCommentOnColumn('committee_qualification_panel', 'committee_qualification_id', 'คุณสมบัติ');
        $this->addCommentOnColumn('committee_qualification_panel', 'panel_id', 'panel');
        $this->addCommentOnColumn('committee_qualification_panel', 'name', 'คุณสมบัติตาม panel');
        $this->addCommentOnColumn('committee_qualification_panel', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('committee_qualification_panel', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('committee_qualification_panel', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('committee_qualification_panel', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('committee_qualification_panel', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_committee_qualification_panel_name', 'committee_qualification_panel', ['name']);
        $this->addForeignKey('fk_committee_qualification_panel_committee_qualification_id', 'committee_qualification_panel', 'committee_qualification_id', 'committee_qualification', 'id');
        $this->addForeignKey('fk_committee_qualification_panel_panel_id', 'committee_qualification_panel', 'panel_id', 'panel', 'id');
        $this->addForeignKey('fk_committee_qualification_panel_user_created_by', 'committee_qualification_panel', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_committee_qualification_panel_user_updated_by', 'committee_qualification_panel', 'updated_by', 'user', 'id');

        $this->addColumn('person', 'committee_qualification_id', $this->integer());
        $this->addCommentOnColumn('person', 'committee_qualification_id', 'คุณสมบัติกรรมการ');

        $this->addColumn('submission_committee', 'committee_qualification_id', $this->integer());
        $this->addColumn('submission_committee', 'gender', $this->string());

        $this->addCommentOnColumn('submission_committee', 'committee_qualification_id', 'คุณสมบัติกรรมการ');
        $this->addCommentOnColumn('submission_committee', 'gender', 'เพศ');
        $this->addForeignKey('fk_person_committee_qualification_id', 'person', 'committee_qualification_id', 'committee_qualification', 'id');
        $this->addForeignKey('fk_submission_committee_committee_qualification_id', 'submission_committee', 'committee_qualification_id', 'committee_qualification', 'id');

        $this->insert('committee_qualification', ['id' => 1, 'name' => 'กรรมการที่เป็นแพทย์']);
        $this->insert('committee_qualification', ['id' => 2, 'name' => 'กรรมการที่เป็นทันตแพทย์']);
        $this->insert('committee_qualification', ['id' => 3, 'name' => 'กรรมการที่เป็นเภสัชกร/เภสัชศาสตร์']);
        $this->insert('committee_qualification', ['id' => 4, 'name' => 'กรรมการที่เป็นเทคนิคการแพทย์']);
        $this->insert('committee_qualification', ['id' => 5, 'name' => 'กรรมการที่เป็นนักกายภาพบำบัด']);
        $this->insert('committee_qualification', ['id' => 6, 'name' => 'กรรมการที่เป็นพยาบาล']);
        $this->insert('committee_qualification', ['id' => 7, 'name' => 'กรรมการที่เชี่ยวชาญด้านวิทยาศาสตร์']);
        $this->insert('committee_qualification', ['id' => 8, 'name' => 'กรรมการที่เชี่ยวชาญด้านสาธารณสุขศาสตร์']);
        $this->insert('committee_qualification', ['id' => 9, 'name' => 'กรรมการที่เชี่ยวชาญด้านวิศวกร']);
        $this->insert('committee_qualification', ['id' => 10, 'name' => 'กรรมการที่เชี่ยวชาญด้านสถาปนิก']);
        $this->insert('committee_qualification', ['id' => 11, 'name' => 'กรรมการที่เชี่ยวชาญด้านสังคมศาสตร์ นักมานุษยวิทยา']);
        $this->insert('committee_qualification', ['id' => 12, 'name' => 'กรรมการที่เป็นนักกฏหมาย']);
        $this->insert('committee_qualification', ['id' => 13, 'name' => 'บุคคลที่เป็นตัวแทนภาคประชาชนซึ่งเป็นตัวแทนของชุมชน (Lay person)']);

        $this->insert('committee_qualification_panel', ['id' => 1, 'committee_qualification_id' => 1, 'panel_id' => 1, 'name' => 'กรรมการที่เป็นแพทย์']);
        $this->insert('committee_qualification_panel', ['id' => 2, 'committee_qualification_id' => 1, 'panel_id' => 2, 'name' => 'กรรมการที่เป็นแพทย์']);
        $this->insert('committee_qualification_panel', ['id' => 3, 'committee_qualification_id' => 1, 'panel_id' => 3, 'name' => 'กรรมการที่มีความเชี่ยวชาญด้านวิทยาศาสตร์การแพทย์']);
        $this->insert('committee_qualification_panel', ['id' => 4, 'committee_qualification_id' => 1, 'panel_id' => 4, 'name' => 'กรรมการที่เป็นแพทย์']);

        $this->insert('committee_qualification_panel', ['id' => 5, 'committee_qualification_id' => 2, 'panel_id' => 1, 'name' => 'กรรมการที่เป็นผู้เชี่ยวชาญงานวิจัยด้านวิทยาศาสตร์สุขภาพและการวิจัยทางการแพทย์']);
        $this->insert('committee_qualification_panel', ['id' => 6, 'committee_qualification_id' => 2, 'panel_id' => 2, 'name' => 'กรรมการที่เป็นผู้เชี่ยวชาญงานวิจัยด้านวิทยาศาสตร์สุขภาพพฤติกรรมสุขภาพสังคมศาสตร์การแพทย์']);
        $this->insert('committee_qualification_panel', ['id' => 7, 'committee_qualification_id' => 2, 'panel_id' => 3, 'name' => 'กรรมการที่เป็นผู้เชี่ยวชาญทางด้านวิทยาศาสตร์การแพทย์']);
        $this->insert('committee_qualification_panel', ['id' => 8, 'committee_qualification_id' => 2, 'panel_id' => 4, 'name' => 'กรรมการที่เป็นทันตแพทย์ หรือนักเทคนิคการแพทย์หรือนักกายภาพบำบัด']);

        $this->insert('committee_qualification_panel', ['id' => 9, 'committee_qualification_id' => 3, 'panel_id' => 1, 'name' => 'กรรมการที่เป็นผู้เชี่ยวชาญงานวิจัยด้านวิทยาศาสตร์สุขภาพและการวิจัยทางการแพทย์']);
        $this->insert('committee_qualification_panel', ['id' => 10, 'committee_qualification_id' => 3, 'panel_id' => 2, 'name' => 'กรรมการที่เป็นผู้เชี่ยวชาญงานวิจัยด้านวิทยาศาสตร์สุขภาพพฤติกรรมสุขภาพสังคมศาสตร์การแพทย์']);
        $this->insert('committee_qualification_panel', ['id' => 11, 'committee_qualification_id' => 3, 'panel_id' => 3, 'name' => 'กรรมการที่เป็นผู้เชี่ยวชาญทางด้านวิทยาศาสตร์การแพทย์']);
        $this->insert('committee_qualification_panel', ['id' => 12, 'committee_qualification_id' => 3, 'panel_id' => 4, 'name' => 'กรรมการที่เป็นเภสัชกร/เภสัชศาสตร์']);

        $this->insert('committee_qualification_panel', ['id' => 13, 'committee_qualification_id' => 4, 'panel_id' => 1, 'name' => 'กรรมการที่เป็นผู้เชี่ยวชาญงานวิจัยด้านวิทยาศาสตร์สุขภาพและการวิจัยทางการแพทย์']);
        $this->insert('committee_qualification_panel', ['id' => 14, 'committee_qualification_id' => 4, 'panel_id' => 2, 'name' => 'กรรมการที่เป็นผู้เชี่ยวชาญงานวิจัยด้านวิทยาศาสตร์สุขภาพพฤติกรรมสุขภาพสังคมศาสตร์การแพทย์']);
        $this->insert('committee_qualification_panel', ['id' => 15, 'committee_qualification_id' => 4, 'panel_id' => 3, 'name' => 'กรรมการที่เป็นผู้เชี่ยวชาญทางด้านวิทยาศาสตร์การแพทย์']);
        $this->insert('committee_qualification_panel', ['id' => 16, 'committee_qualification_id' => 4, 'panel_id' => 4, 'name' => 'กรรมการที่เป็นทันตแพทย์ หรือนักเทคนิคการแพทย์หรือนักกายภาพบำบัด']);

        $this->insert('committee_qualification_panel', ['id' => 17, 'committee_qualification_id' => 5, 'panel_id' => 1, 'name' => 'กรรมการที่เป็นผู้เชี่ยวชาญงานวิจัยด้านวิทยาศาสตร์สุขภาพและการวิจัยทางการแพทย์']);
        $this->insert('committee_qualification_panel', ['id' => 18, 'committee_qualification_id' => 5, 'panel_id' => 2, 'name' => 'กรรมการที่เป็นผู้เชี่ยวชาญงานวิจัยด้านวิทยาศาสตร์สุขภาพพฤติกรรมสุขภาพสังคมศาสตร์การแพทย์']);
        $this->insert('committee_qualification_panel', ['id' => 19, 'committee_qualification_id' => 5, 'panel_id' => 3, 'name' => 'กรรมการที่เป็นผู้เชี่ยวชาญทางด้านวิทยาศาสตร์การแพทย์']);
        $this->insert('committee_qualification_panel', ['id' => 20, 'committee_qualification_id' => 5, 'panel_id' => 4, 'name' => 'กรรมการที่เป็นทันตแพทย์ หรือนักเทคนิคการแพทย์หรือนักกายภาพบำบัด']);

        $this->insert('committee_qualification_panel', ['id' => 21, 'committee_qualification_id' => 6, 'panel_id' => 1, 'name' => 'กรรมการที่เป็นผู้เชี่ยวชาญงานวิจัยด้านวิทยาศาสตร์สุขภาพและการวิจัยทางการแพทย์']);
        $this->insert('committee_qualification_panel', ['id' => 22, 'committee_qualification_id' => 6, 'panel_id' => 2, 'name' => 'กรรมการที่เป็นผู้เชี่ยวชาญงานวิจัยด้านวิทยาศาสตร์สุขภาพพฤติกรรมสุขภาพสังคมศาสตร์การแพทย์']);
        $this->insert('committee_qualification_panel', ['id' => 23, 'committee_qualification_id' => 6, 'panel_id' => 3, 'name' => 'กรรมการที่เป็นผู้เชี่ยวชาญทางด้านวิทยาศาสตร์การแพทย์']);
        $this->insert('committee_qualification_panel', ['id' => 24, 'committee_qualification_id' => 6, 'panel_id' => 4, 'name' => 'กรรมการที่เป็นพยาบาล']);

        $this->insert('committee_qualification_panel', ['id' => 25, 'committee_qualification_id' => 7, 'panel_id' => 1, 'name' => 'กรรมการที่เป็นผู้เชี่ยวชาญงานวิจัยด้านวิทยาศาสตร์สุขภาพและการวิจัยทางการแพทย์']);
        $this->insert('committee_qualification_panel', ['id' => 26, 'committee_qualification_id' => 7, 'panel_id' => 2, 'name' => 'กรรมการที่เชี่ยวชาญด้านวิทยาศาสตร์']);
        $this->insert('committee_qualification_panel', ['id' => 27, 'committee_qualification_id' => 7, 'panel_id' => 3, 'name' => 'กรรมการที่เชี่ยวชาญด้านวิทยาศาสตร์']);
        $this->insert('committee_qualification_panel', ['id' => 28, 'committee_qualification_id' => 7, 'panel_id' => 4, 'name' => 'กรรมการที่เชี่ยวชาญด้านวิทยาศาสตร์']);

        $this->insert('committee_qualification_panel', ['id' => 29, 'committee_qualification_id' => 8, 'panel_id' => 1, 'name' => 'กรรมการที่เป็นผู้เชี่ยวชาญงานวิจัยด้านวิทยาศาสตร์สุขภาพและการวิจัยทางการแพทย์']);
        $this->insert('committee_qualification_panel', ['id' => 30, 'committee_qualification_id' => 8, 'panel_id' => 2, 'name' => 'กรรมการที่เป็นผู้เชี่ยวชาญงานวิจัยด้านวิทยาศาสตร์สุขภาพพฤติกรรมสุขภาพสังคมศาสตร์การแพทย์']);
        $this->insert('committee_qualification_panel', ['id' => 31, 'committee_qualification_id' => 8, 'panel_id' => 3, 'name' => 'กรรมการที่เป็นผู้เชี่ยวชาญทางด้านวิทยาศาสตร์การแพทย์']);
        $this->insert('committee_qualification_panel', ['id' => 32, 'committee_qualification_id' => 8, 'panel_id' => 4, 'name' => 'กรรมการที่เชี่ยวชาญด้านสาธารณสุขศาสตร์']);

        $this->insert('committee_qualification_panel', ['id' => 33, 'committee_qualification_id' => 9, 'panel_id' => 1, 'name' => 'กรรมการที่เชี่ยวชาญด้านวิศวกร']);
        $this->insert('committee_qualification_panel', ['id' => 34, 'committee_qualification_id' => 9, 'panel_id' => 2, 'name' => 'กรรมการที่เชี่ยวชาญด้านวิศวกร']);
        $this->insert('committee_qualification_panel', ['id' => 35, 'committee_qualification_id' => 9, 'panel_id' => 3, 'name' => 'กรรมการที่เชี่ยวชาญด้านวิศวกร']);
        $this->insert('committee_qualification_panel', ['id' => 36, 'committee_qualification_id' => 9, 'panel_id' => 4, 'name' => 'กรรมการผู้เชี่ยวชาญทางด้านวิศวกรรมการแพทย์หรือวัสดุศาสตร์หรือวิศวกรรมเครื่องกลหรือวิศวกรรมไฟฟ้าหรือวิศวกรรมคอมพิวเตอร์ หรือสาขาอื่นที่เกี่ยวข้อง']);

        $this->insert('committee_qualification_panel', ['id' => 37, 'committee_qualification_id' => 10, 'panel_id' => 1, 'name' => 'กรรมการที่ไม่ใช่วิชาชีพแพทย์หรือเกี่ยวข้องทางการแพทย์หรือวิทยาศาสตร์']);
        $this->insert('committee_qualification_panel', ['id' => 38, 'committee_qualification_id' => 10, 'panel_id' => 2, 'name' => 'กรรมการที่ไม่ใช่วิชาชีพแพทย์หรือเกี่ยวข้องทางการแพทย์หรือวิทยาศาสตร์']);
        $this->insert('committee_qualification_panel', ['id' => 39, 'committee_qualification_id' => 10, 'panel_id' => 3, 'name' => 'กรรมการที่เป็นผู้มีความเชียวชาญด้านสังคมศาสตร์พฤติกรรมศาสตร์ นักกฏหมาย นักมานุษยวิทยา']);
        $this->insert('committee_qualification_panel', ['id' => 40, 'committee_qualification_id' => 10, 'panel_id' => 4, 'name' => 'กรรมการที่ไม่ใช่วิชาชีพแพทย์หรือเกี่ยวข้องทางการแพทย์หรือวิทยาศาสตร์']);

        $this->insert('committee_qualification_panel', ['id' => 41, 'committee_qualification_id' => 11, 'panel_id' => 1, 'name' => 'กรรมการที่ไม่ใช่วิชาชีพแพทย์หรือเกี่ยวข้องทางการแพทย์หรือวิทยาศาสตร์']);
        $this->insert('committee_qualification_panel', ['id' => 42, 'committee_qualification_id' => 11, 'panel_id' => 2, 'name' => 'กรรมการที่ไม่ใช่วิชาชีพแพทย์หรือเกี่ยวข้องทางการแพทย์หรือวิทยาศาสตร์']);
        $this->insert('committee_qualification_panel', ['id' => 43, 'committee_qualification_id' => 11, 'panel_id' => 3, 'name' => 'กรรมการที่เป็นผู้มีความเชียวชาญด้านสังคมศาสตร์พฤติกรรมศาสตร์ นักกฏหมาย นักมานุษยวิทย']);
        $this->insert('committee_qualification_panel', ['id' => 44, 'committee_qualification_id' => 11, 'panel_id' => 4, 'name' => 'กรรมการที่ไม่ใช่วิชาชีพแพทย์หรือเกี่ยวข้องทางการแพทย์หรือวิทยาศาสตร์']);

        $this->insert('committee_qualification_panel', ['id' => 45, 'committee_qualification_id' => 12, 'panel_id' => 1, 'name' => 'กรรมการที่ไม่ใช่วิชาชีพแพทย์หรือเกี่ยวข้องทางการแพทย์หรือวิทยาศาสตร์']);
        $this->insert('committee_qualification_panel', ['id' => 46, 'committee_qualification_id' => 12, 'panel_id' => 2, 'name' => 'กรรมการที่ไม่ใช่วิชาชีพแพทย์หรือเกี่ยวข้องทางการแพทย์หรือวิทยาศาสตร์']);
        $this->insert('committee_qualification_panel', ['id' => 47, 'committee_qualification_id' => 12, 'panel_id' => 3, 'name' => 'กรรมการที่เป็นผู้มีความเชียวชาญด้านสังคมศาสตร์พฤติกรรมศาสตร์ นักกฏหมาย นักมานุษยวิทย']);
        $this->insert('committee_qualification_panel', ['id' => 48, 'committee_qualification_id' => 12, 'panel_id' => 4, 'name' => 'กรรมการที่ไม่ใช่วิชาชีพแพทย์หรือเกี่ยวข้องทางการแพทย์หรือวิทยาศาสตร์']);

        $this->insert('committee_qualification_panel', ['id' => 49, 'committee_qualification_id' => 13, 'panel_id' => 1, 'name' => 'บุคคลที่เป็นผู้แทนภาคประชาชนซึ่งเป็นตัวแทนของชุมชน']);
        $this->insert('committee_qualification_panel', ['id' => 50, 'committee_qualification_id' => 13, 'panel_id' => 2, 'name' => 'บุคคลที่เป็นผู้แทนภาคประชาชนซึ่งเป็นตัวแทนของชุมชน']);
        $this->insert('committee_qualification_panel', ['id' => 51, 'committee_qualification_id' => 13, 'panel_id' => 3, 'name' => 'บุคคลที่เป็นผู้แทนภาคประชาชนซึ่งเป็นตัวแทนของชุมชน']);
        $this->insert('committee_qualification_panel', ['id' => 52, 'committee_qualification_id' => 13, 'panel_id' => 4, 'name' => 'บุคคลที่เป็นผู้แทนภาคประชาชนซึ่งเป็นตัวแทนของชุมชน']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m250424_040706_add_table_committee_qualification cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m250424_040706_add_table_committee_qualification cannot be reverted.\n";

      return false;
      }
     */
}
