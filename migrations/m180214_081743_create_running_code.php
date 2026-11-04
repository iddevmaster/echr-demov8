<?php

use yii\db\Migration;

/**
 * Class m180214_081743_create_running_code
 */
class m180214_081743_create_running_code extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->createTable('running_code', [
            'id' => $this->primaryKey(),
            'prefix' => $this->string(),
            'number' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);
        
        $this->createIndex('idx_running_code_prefix', 'running_code', 'prefix');
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->dropTable('running_code');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180214_081743_create_running_code cannot be reverted.\n";

      return false;
      }
     */
}
