<?php

use yii\db\Migration;

/**
 * Class m180508_054940_edit_filed_text_question
 */
class m180508_054940_edit_filed_text_question extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('questionnaire_answer', 'text_answer');
        $this->addColumn('questionnaire_answer', 'text_answer', 'longtext');

        
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180508_054940_edit_filed_text_question cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180508_054940_edit_filed_text_question cannot be reverted.\n";

        return false;
    }
    */
}
