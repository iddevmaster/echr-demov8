<?php

use yii\db\Migration;

/**
 * Class m191113_073049_note_submission
 */
class m191113_073049_note_submission extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('submission', 'note', $this->text());
        $this->addCommentOnColumn('submission', 'note', 'note');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191113_073049_note_submission cannot be reverted.\n";

        return false;
    }
    */
}
