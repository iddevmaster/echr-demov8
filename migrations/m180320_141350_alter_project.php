<?php

use yii\db\Migration;

/**
 * Class m180320_141350_alter_project
 */
class m180320_141350_alter_project extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->addColumn('project', 'is_closed', $this->boolean()->notNull()->defaultValue(FALSE));
        $this->addColumn('project', 'closed_at', $this->dateTime());
        $this->addColumn('project', 'next_progress_at', $this->dateTime());

        $this->addCommentOnColumn('project', 'is_closed', 'ปิดโครงการหรือยัง');
        $this->addCommentOnColumn('project', 'closed_at', 'ปิดโครงการเมื่อ');
        $this->addCommentOnColumn('project', 'next_progress_at', 'ปิดโครงการเมื่อ');

        $this->addColumn('submission', 'next_progress_at', $this->dateTime());
        $this->addCommentOnColumn('submission', 'next_progress_at', 'ปิดโครงการเมื่อ');
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {

        $this->dropColumn('submission', 'next_progress_at');

        $this->dropColumn('project', 'next_progress_at');
        $this->dropColumn('project', 'closed_at');
        $this->dropColumn('project', 'is_closed');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180320_141350_alter_project cannot be reverted.\n";

      return false;
      }
     */
}
