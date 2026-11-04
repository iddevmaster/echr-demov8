<?php

use yii\db\Migration;

/**
 * Class m260605_091709_alter_review_choices
 */
class m260605_091709_alter_review_choices extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('review_choice', 'type_ref', $this->integer());
        $this->addCommentOnColumn('review_choice', 'type_ref', 'type_ref');


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('review_choice', 'type_ref');

    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m260605_091709_alter_review_choices cannot be reverted.\n";

      return false;
      }
     */
}
