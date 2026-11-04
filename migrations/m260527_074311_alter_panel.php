<?php

use yii\db\Migration;

/**
 * Class m260527_074311_alter_panel
 */
class m260527_074311_alter_panel extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('panel', 'ref_letter', $this->string());
        $this->addCommentOnColumn('panel', 'ref_letter', 'เลขที่อ้างอิงในใบรับรอง');

        $this->addColumn('submission', 'crec_staff', $this->string());
        $this->addCommentOnColumn('submission', 'crec_staff', 'เจ้าหน้าที่  CREC');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropColumn('panel', 'ref_letter');
        $this->dropColumn('submission', 'crec_staff');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m260527_074311_alter_panel cannot be reverted.\n";

      return false;
      }
     */
}
