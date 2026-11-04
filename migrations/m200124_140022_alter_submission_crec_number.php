<?php

use yii\db\Migration;

/**
 * Class m200124_140022_alter_submission_crec_number
 */
class m200124_140022_alter_submission_crec_number extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('project', 'crec_number', $this->string());
        $this->addColumn('project', 'crec_number_certificate', $this->string());
        $this->addCommentOnColumn('project', 'crec_number', 'หมายเลข CREC');
        $this->addCommentOnColumn('project', 'crec_number_certificate', 'หมายเลขรับรอง CREC');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m200124_140022_alter_submission_crec_number cannot be reverted.\n";

      return false;
      }
     */
}
