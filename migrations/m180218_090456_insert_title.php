<?php

use yii\db\Migration;

/**
 * Class m180218_090456_insert_title
 */
class m180218_090456_insert_title extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->update('title', ['name_eng' => 'Mr.'], ['id' => 1]);
        $this->update('title', ['name_eng' => 'Ms.'], ['id' => 2]);
        $this->update('title', ['name_eng' => 'Mrs.'], ['id' => 3]);

        $this->insert('title', ['id' => 4, 'name' => 'อ.', 'name_eng' => 'Teacher']);
        $this->insert('title', ['id' => 5, 'name' => 'ผศ.', 'name_eng' => 'Asst. Prof.']);
        $this->insert('title', ['id' => 6, 'name' => 'รศ.', 'name_eng' => 'Assoc. Prof.']);
        $this->insert('title', ['id' => 7, 'name' => 'ศ.', 'name_eng' => 'Prof.']);
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->delete('title', ['>', 'id', 3]);
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180218_090456_insert_title cannot be reverted.\n";

      return false;
      }
     */
}
