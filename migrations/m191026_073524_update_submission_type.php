<?php

use yii\db\Migration;

/**
 * Class m191026_073524_update_submission_type
 */
class m191026_073524_update_submission_type extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->update('submission_type', [
//            'risk_assessment' => 1,
            'progress' => 1,
            'certify' => 1,
        ], [
            'id' => [4, 5, 14, 8]
        ]);
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
      echo "m191026_073524_update_submission_type cannot be reverted.\n";

      return false;
      }
     */
}
