<?php

use yii\db\Migration;
use app\models\SubmissionType;
/**
 * Class m191209_071144_add_agenda
 */
class m191209_071144_add_agenda extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $st = SubmissionType::findOne(4);
        $crec = new SubmissionType();
        $crec->attributes = $st->attributes;
        $crec->name = 'โครงการที่ได้รับการรับรองจาก CREC';
        $crec->name_eng = 'Expedited review (CREC)';
        $crec->save(false);
        
        $st = SubmissionType::findOne(10);
        $dsmb = new SubmissionType();
        $dsmb->attributes = $st->attributes;
        $dsmb->name = 'DSMB';
        $dsmb->name_eng = 'DSMB';
        $dsmb->save(false);
        
        $this->batchInsert('agenda_submission_type', ['agenda_id', 'submission_type_id'], [
            [18, 10],
            [18, 11],
            [10, 15],
            [9, $crec->id],
            [10, $dsmb->id],
            [18, $dsmb->id],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m191209_071144_add_agenda cannot be reverted.\n";

      return false;
      }
     */
}
