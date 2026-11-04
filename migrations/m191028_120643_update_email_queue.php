<?php

use yii\db\Migration;

/**
 * Class m191028_120643_update_email_queue
 */
class m191028_120643_update_email_queue extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createIndex('idx_email_queue_model_id', 'email_queue', 'model_id');
        $this->createIndex('idx_email_queue_type', 'email_queue', 'type');
        $this->createIndex('idx_email_queue_mail_at', 'email_queue', 'mail_at');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropIndex('idx_email_queue_mail_at', 'email_queue');
        $this->dropIndex('idx_email_queue_type', 'email_queue');
        $this->dropIndex('idx_email_queue_model_id', 'email_queue');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m191028_120643_update_email_queue cannot be reverted.\n";

      return false;
      }
     */
}
