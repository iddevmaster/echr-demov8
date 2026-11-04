<?php

use yii\db\Migration;
use app\models\Submission;
use app\models\AgendaSubmissionType;

/**
 * Class m180321_061903_insert_result_document
 */
class m180321_061903_insert_result_document extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->insert('result_document', ['id' => 1, 'name' => 'แจ้งผลมติ C', 'template_file' => 'AF06-0503.2 -แจ้งผลมติ C.docx', 'resolution' => Submission::RESOLUTION_C]);
        $this->insert('result_document', ['id' => 2, 'name' => 'แจ้งผลมติ R', 'template_file' => 'AF07-0503.2 -แจ้งผลมติ R.docx', 'resolution' => Submission::RESOLUTION_R]);
        $this->insert('result_document', ['id' => 3, 'name' => 'แจ้งผลมติ N', 'template_file' => 'AF08-0503.2 -แจ้งผลมติ N.docx', 'resolution' => Submission::RESOLUTION_N]);
        $this->insert('result_document', ['id' => 4, 'name' => 'แจ้งผลมติ W', 'template_file' => 'AF09-0503.2 -แจ้งผลมติ W.docx', 'resolution' => Submission::RESOLUTION_W]);
        $this->insert('result_document', ['id' => 5, 'name' => 'แจ้งผลมติ T', 'template_file' => 'AF10-0503.2 -แจ้งผลมติ T.docx', 'resolution' => Submission::RESOLUTION_T]);

        $this->insert('result_document', ['id' => 6, 'name' => 'หนังสือรับรองภาษาไทย', 'template_file' => 'AF19-0303.4 แบบฟอร์มหนังสือรับรอง-Thai.docx', 'resolution' => Submission::RESOLUTION_Y]);
        $this->insert('result_document', ['id' => 7, 'name' => 'หนังสือรับรองภาษาอังกฤษ', 'template_file' => 'AF20-0303.4 แบบฟอร์มหนังสือรับรอง-Eng.docx', 'resolution' => Submission::RESOLUTION_Y]);

        $this->insert('result_document', ['id' => 8, 'name' => 'หนังสือรับทราบ วาระ 3.1', 'template_file' => 'หนังสือรับทราบ วาระ 3.1.docx', 'resolution' => Submission::RESOLUTION_Y]);
        $this->insert('result_document', ['id' => 9, 'name' => 'หนังสือรับทราบ วาระ 3.2', 'template_file' => 'หนังสือรับทราบ วาระ 3.2.docx', 'resolution' => Submission::RESOLUTION_Y]);
        $this->insert('result_document', ['id' => 10, 'name' => 'หนังสือรับทราบ วาระ 3.3', 'template_file' => 'หนังสือรับทราบ วาระ 3.3.docx', 'resolution' => Submission::RESOLUTION_Y]);
        $this->insert('result_document', ['id' => 11, 'name' => 'หนังสือรับทราบ วาระ 3.5', 'template_file' => 'หนังสือรับทราบ วาระ 3.5.docx', 'resolution' => Submission::RESOLUTION_Y]);
        $this->insert('result_document', ['id' => 12, 'name' => 'หนังสือรับทราบ วาระ 4.7', 'template_file' => 'หนังสือรับทราบ วาระ 4.7.docx', 'resolution' => Submission::RESOLUTION_Y]);

        $this->insert('result_document', ['id' => 13, 'name' => 'หนังสือแจ้งแก้ไขเพิ่มเติม', 'template_file' => 'หนังสือแจ้งแก้ไขเพิ่มเติม.docx', 'committee_resolution' => Submission::RESOLUTION_C]);
        for ($j = 1; $j <= 5; $j++) {
            for ($i = 6; $i <= 18; $i++) {
                $this->insert('agenda_result_document', ['result_document_id' => $j, 'agenda_id' => $i]);
            }
        }
        
        $asts = AgendaSubmissionType::find()->joinWith(['submissionType'])->isDeleted(FALSE)->resolutionLabel(app\models\SubmissionType::RES_ENDORSE)->all();
        foreach ($asts as $ast) {
            $this->insert('agenda_result_document', ['result_document_id' => 6, 'agenda_id' => $ast->agenda_id]);
            $this->insert('agenda_result_document', ['result_document_id' => 7, 'agenda_id' => $ast->agenda_id]);
        }
        
        $this->insert('agenda_result_document', ['result_document_id' => 8, 'agenda_id' => 6]);
        $this->insert('agenda_result_document', ['result_document_id' => 9, 'agenda_id' => 7]);
        $this->insert('agenda_result_document', ['result_document_id' => 10, 'agenda_id' => 8]);
        $this->insert('agenda_result_document', ['result_document_id' => 11, 'agenda_id' => 10]);
        $this->insert('agenda_result_document', ['result_document_id' => 12, 'agenda_id' => 18]);
        
        $this->insert('agenda_result_document', ['result_document_id' => 13, 'agenda_id' => NULL]);
        
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->delete('agenda_result_document');
        $this->delete('result_document');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180321_061903_insert_result_document cannot be reverted.\n";

      return false;
      }
     */
}
