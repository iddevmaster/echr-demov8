<?php

use yii\db\Migration;

/**
 * Class m180306_034856_add_filed_person
 */
class m180306_034856_add_filed_person extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('person', 'expertise', $this->string());
        $this->addCommentOnColumn('person', 'expertise', 'ความชำนาญ');

        $this->addColumn('submission_document', 'name_eng', $this->string());
        $this->addCommentOnColumn('submission_document', 'name_eng', 'ชื่อไฟล์ภาษาอังกฤษ');

        $this->addColumn('document', 'name_eng', $this->string());
        $this->addCommentOnColumn('document', 'name_eng', 'ชื่อไฟล์ภาษาอังกฤษ');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropColumn('document', 'name_eng');
        $this->dropColumn('submission_document', 'name_eng');
        $this->dropColumn('person', 'expertise');

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180306_034856_add_filed_person cannot be reverted.\n";

      return false;
      }
     */
}
