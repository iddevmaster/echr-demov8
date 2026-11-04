<?php

use yii\db\Migration;
use app\models\ProjectQuestion;

/**
 * Class m191024_151139_insert_project_type
 */
class m191024_151139_insert_project_type extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->batchInsert('project_type', ['id', 'name', 'is_alert', 'min_occur'], [
            [1, 'clinical trial phase I', 0, null],
            [2, 'clinical trial phase II ไปจนถึง phase IV', 0, null],
            [3, 'Medical device study', 0, null],
            [4, 'Vaccine trial', 0, null],
            [5, 'Herbal medicine study', 0, null],
            [6, 'Food supplement study', 0, null],
            [7, 'Social and behavioral research', 0, null],
            [8, 'Retrospective study', 0, null],
            [9, 'Prospective study ที่ไม่ใช่ clinical trial', 0, null],
            [10, 'non compliance', 0, null],
            [11, 'minor protocol deviation', 0, null],
            [12, 'major protocol violation', 1, 2],
        ]);
        
        $this->batchInsert('project_question', ['id', 'name', 'answer_type'], [
            [1, 'รายละเอียดประเภทโครงการวิจัยใหม่', ProjectQuestion::TYPE_MULTI_CHOICES],
            [2, 'รายละเอียดประเภทโครงการวิจัยเบี่ยงเบน', ProjectQuestion::TYPE_SINGLE_CHOICE],
        ]);
        
        $this->batchInsert('project_question_choice', ['project_question_id', 'project_type_id'], [
            [1, 1],
            [1, 2],
            [1, 3],
            [1, 4],
            [1, 5],
            [1, 6],
            [1, 7],
            [1, 8],
            [1, 9],
            [2, 10],
            [2, 11],
            [2, 12],
        ]);
        
        $this->batchInsert('project_agenda_question', ['project_question_id', 'agenda_id'], [
            [1, 9],
            [1, 13],
            [1, 14],
            [2, 18],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->delete('project_agenda_question');
        $this->delete('project_question_choice');
        $this->delete('project_question');
        $this->delete('project_type');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m191024_151139_insert_project_type cannot be reverted.\n";

      return false;
      }
     */
}
