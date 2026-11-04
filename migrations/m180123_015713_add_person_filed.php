<?php

use yii\db\Migration;

/**
 * Class m180123_015713_add_person_filed
 */
class m180123_015713_add_person_filed extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->addColumn('person_role', 'effective_date', $this->dateTime());
        $this->addColumn('person_role', 'effective_number', $this->string());
        $this->addColumn('person_role', 'expire_date', $this->dateTime());
        $this->addColumn('person_role', 'status', $this->boolean()->notNull()->defaultValue(FALSE));
        $this->addColumn('person', 'bank_account', $this->string());
        $this->addColumn('person', 'bank_account_number', $this->integer());
        $this->addColumn('person', 'bank_branch', $this->string());
        $this->addColumn('submission_type', 'payment', $this->decimal(18, 2));
        
        $this->addCommentOnColumn('person_role', 'effective_date', 'วันที่แต่งตั้งตำแหน่ง');
        $this->addCommentOnColumn('person_role', 'effective_number', 'เลขที่แต่งตั้งตำแหน่ง');
        $this->addCommentOnColumn('person_role', 'expire_date', 'วันที่สิ้นว่าระการทำงาน');
        $this->addCommentOnColumn('person_role', 'status', 'สถานะดำรงตำแหน่ง');
        $this->addCommentOnColumn('person', 'bank_account', 'ธนาคาร');
        $this->addCommentOnColumn('person', 'bank_account_number', 'เลขที่บัญชี');
        $this->addCommentOnColumn('person', 'bank_branch', 'สาขาธนาคาร');
        $this->addCommentOnColumn('submission_type', 'payment', 'ค่าตอบแทน');
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        echo "m180123_015713_add_person_filed cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180123_015713_add_person_filed cannot be reverted.\n";

      return false;
      }
     */
}
