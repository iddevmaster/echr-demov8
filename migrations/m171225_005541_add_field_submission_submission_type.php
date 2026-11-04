<?php

use yii\db\Migration;

/**
 * Class m171225_005541_add_field_submission_submission_type
 */
class m171225_005541_add_field_submission_submission_type extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('submission', 'submission_type_id', $this->integer());
        $this->addCommentOnColumn('submission', 'submission_type_id', 'ประเภทของโครงการวิจัย');
        $this->addForeignKey('fk_submission_submission_type_id', 'submission', 'submission_type_id', 'submission_type', 'id');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m171225_005541_add_field_submission_submission_type cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171225_005541_add_field_submission_submission_type cannot be reverted.\n";

        return false;
    }
    */
}
