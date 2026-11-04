<?php

use yii\db\Migration;

/**
 * Class m250816_143109_alter_funding_source
 */
class m250816_143109_alter_funding_source extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
//        $this->addColumn('funding_source', 'crec_id', $this->integer()->comment('รหัสประเภททุนในระบบ CREC'));
//        $this->createIndex('idx_funding_source-crec_id', 'funding_source', 'crec_id');
//
//        $this->update('funding_source', ['crec_id' => 1], ['id' => 1]);
//        $this->update('funding_source', ['crec_id' => 2], ['id' => 5]);
//        $this->update('funding_source', ['crec_id' => 3], ['id' => 3]);
//        $this->update('funding_source', ['crec_id' => 4], ['id' => 4]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
//        $this->alterColumn('funding_source', 'crec_id', $this->string());
//        $this->update('funding_source', ['crec_id' => '2,5'], ['id' => 5]);
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m250816_143109_alter_funding_source cannot be reverted.\n";

      return false;
      }
     */
}
