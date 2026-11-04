<?php

use yii\db\Migration;

/**
 * Class m180224_035818_alter_submission_type
 */
class m180224_035818_alter_submission_type extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->createTable('risk', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);
        
        $this->addCommentOnColumn('risk', 'id', 'รหัสอัตโนมัติ');
        $this->addCommentOnColumn('risk', 'name', 'ชื่อความเสี่ยง');
        $this->addCommentOnColumn('risk', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('risk', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('risk', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('risk', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('risk', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->createIndex('idx_risk_name', 'risk', ['name']);
        $this->addForeignKey('fk_risk_created_by', 'risk', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_risk_updated_by', 'risk', 'updated_by', 'user', 'id');
        
        $this->insert('risk', ['id' => 1, 'name' => 'มีความเสี่ยงไม่เกินความเสี่ยงเล็กน้อย']);
        $this->insert('risk', ['id' => 2, 'name' => 'มีความเสี่ยงเกินกว่าความเสี่ยงเล็กน้อย แต่มีประโยชน์ต่อตัวอาสาสมัครโดยตรง']);
        $this->insert('risk', ['id' => 3, 'name' => 'มีความเสี่ยงเกินกว่าความเสี่ยงเล็กน้อย และไม่มีประโยชน์ต่อตัวอาสาสมัครโดยตรง แต่มีความเป็นไปได้ที่จะได้รับความรู้เกี่ยวกับโรคหรือสภาวะที่อาสาสมัครเป็น']);
        $this->insert('risk', ['id' => 4, 'name' => 'มีความเสี่ยงและประโยชน์ไม่ตรงกับที่กล่าวมาแล้วทั้งสามกลุ่ม แต่อาจมีโอกาสที่จะเข้าใจ หรือป้องกัน หรือบรรเทาปัญหาร้ายแรงที่กระทบสุขภาพและความเป็นอยู่ที่ดีของอาสาสมัคร']);
        
        $this->addColumn('submission_type', 'risk_assessment', $this->boolean()->notNull()->defaultValue(FALSE));
        $this->addColumn('submission_type', 'progress', $this->boolean()->notNull()->defaultValue(FALSE));
        $this->addColumn('submission_type', 'certify', $this->boolean()->notNull()->defaultValue(FALSE));
        
        $this->addCommentOnColumn('submission_type', 'risk_assessment', 'ประเมินความเสี่ยงหรือไม่');
        $this->addCommentOnColumn('submission_type', 'progress', 'ระบุระยะเวลารายงานความก้าวหน้า');
        $this->addCommentOnColumn('submission_type', 'certify', 'ออกการรับรอง');
        
        $this->addColumn('submission', 'certificate_no', $this->string());
        $this->addColumn('submission', 'expire_at', $this->dateTime());
        $this->addColumn('submission', 'risk_id', $this->integer());
        $this->addColumn('submission', 'progress_period', $this->integer());
        
        $this->addCommentOnColumn('submission', 'certificate_no', 'เลขที่การรับรอง');
        $this->addCommentOnColumn('submission', 'expire_at', 'วันที่หมดอายุรับรอง');
        $this->addCommentOnColumn('submission', 'risk_id', 'ความเสี่ยง');
        $this->addCommentOnColumn('submission', 'progress_period', 'ระยะเวลาในการติดตาม');
        
        $this->addForeignKey('fk_submission_risk_id', 'submission', 'risk_id', 'risk', 'id');
        
        $this->addColumn('project', 'certificate_no', $this->string());
        $this->addColumn('project', 'expire_at', $this->dateTime());
        $this->addCommentOnColumn('project', 'certificate_no', 'เลขที่การรับรอง');
        $this->addCommentOnColumn('project', 'expire_at', 'วันที่หมดอายุรับรอง');
        
        $this->update('submission_type', ['risk_assessment' => TRUE, 'progress' => TRUE, 'certify' => TRUE], ['id' => [1,2,6]]);
        $this->update('submission_type', ['certify' => TRUE], ['id' => [8]]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->dropColumn('project', 'expire_at');
        $this->dropColumn('project', 'certificate_no');
        
        $this->dropForeignKey('fk_submission_risk_id', 'submission');
        $this->dropColumn('submission', 'progress_period');
        $this->dropColumn('submission', 'risk_id');
        $this->dropColumn('submission', 'expire_at');
        $this->dropColumn('submission', 'certificate_no');
        $this->dropColumn('submission_type', 'certify');
        $this->dropColumn('submission_type', 'progress');
        $this->dropColumn('submission_type', 'risk_assessment');
        
        $this->dropTable('risk');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180224_035818_alter_submission_type cannot be reverted.\n";

      return false;
      }
     */
}
