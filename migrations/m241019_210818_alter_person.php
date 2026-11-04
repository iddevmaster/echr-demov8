<?php

use yii\db\Migration;

/**
 * Class m241019_210818_alter_person
 */
class m241019_210818_alter_person extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('person', 'accept_policy', $this->integer());
        $this->addCommentOnColumn('person', 'accept_policy', 'เงื่อนไขการให้บริการและนโยบายความเป็นส่วนตัว');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('person', 'accept_policy');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241019_210818_alter_person cannot be reverted.\n";

        return false;
    }
    */
}
