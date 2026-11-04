<?php

use yii\db\Migration;

/**
 * Class m180120_123920_alter_person
 */
class m180120_123920_alter_person extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->createIndex('idx_person_first_name', 'person', 'first_name');
        $this->createIndex('idx_person_last_name', 'person', 'last_name');
        $this->createIndex('idx_person_first_name_eng', 'person', 'first_name_eng');
        $this->createIndex('idx_person_last_name_eng', 'person', 'last_name_eng');
        
        $this->addColumn('title', 'name_eng', $this->string());
        $this->addCommentOnColumn('title', 'name_eng', 'คำนำหน้าชื่อภาษาอังกฤษ');
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->dropColumn('title', 'name_eng');
        
        $this->dropIndex('idx_person_first_name', 'person', 'first_name');
        $this->dropIndex('idx_person_last_name', 'person', 'last_name');
        $this->dropIndex('idx_person_first_name_eng', 'person', 'first_name_eng');
        $this->dropIndex('idx_person_last_name_eng', 'person', 'last_name_eng');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180120_123920_alter_person cannot be reverted.\n";

      return false;
      }
     */
}
