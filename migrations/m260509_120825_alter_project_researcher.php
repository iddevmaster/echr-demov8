<?php

use yii\db\Migration;

/**
 * Class m260509_120825_alter_project_researcher
 */
class m260509_120825_alter_project_researcher extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('project_researcher', 'position', $this->integer()->comment('ตําแหน่ง'));        
        $this->createIndex('idx_project_researcher_position', 'project_researcher', 'position');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx_project_researcher_position', 'project_researcher');
        $this->dropColumn('project_researcher', 'position');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m260509_120825_alter_project_researcher cannot be reverted.\n";

      return false;
      }
     */
}
