<?php

use yii\db\Migration;

/**
 * Class m180613_023545_add_table_coi_person
 */
class m180613_023545_add_table_coi_person extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('submission_coi_person', [
            'id' => $this->primaryKey(),
            'submission_id' => $this->integer(),
            'person_id' => $this->integer(),
            'deleted' => $this->boolean()->notNull()->defaultValue(FALSE),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('submission_coi_person', 'id', 'รหัสอัตโนมัติ');
        $this->addCommentOnColumn('submission_coi_person', 'submission_id', 'submission');
        $this->addCommentOnColumn('submission_coi_person', 'person_id', 'บุคลากรที่เป็น COI');
        $this->addCommentOnColumn('submission_coi_person', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('submission_coi_person', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('submission_coi_person', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('submission_coi_person', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('submission_coi_person', 'updated_at', 'ปรับปรุงเมื่อ');


        $this->addForeignKey('fk_submission_coi_person_submission_id', 'submission_coi_person', 'submission_id', 'submission', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_coi_person_person_id', 'submission_coi_person', 'person_id', 'person', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_coi_person_user1', 'submission_coi_person', 'created_by', 'user', 'id', 'NO ACTION');
        $this->addForeignKey('fk_submission_coi_person_user2', 'submission_coi_person', 'updated_by', 'user', 'id', 'NO ACTION');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180613_023545_add_table_coi_person cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180613_023545_add_table_coi_person cannot be reverted.\n";

        return false;
    }
    */
}
