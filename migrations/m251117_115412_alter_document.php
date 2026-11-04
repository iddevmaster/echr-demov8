<?php

use yii\db\Migration;

/**
 * Class m251117_115412_alter_document
 */
class m251117_115412_alter_document extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        
        $this->addColumn('document', 'remark_eng', $this->string());
        $this->addCommentOnColumn('document', 'remark_eng', 'รายละเอียดเพิ่มเติมเกี่ยวกับเอกสารภาษาอังกฤษ');        
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
      echo "m251117_115412_alter_document cannot be reverted.\n";

      return false;
      }
     */
}
