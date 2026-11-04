<?php

use yii\db\Migration;

/**
 * Class m191112_031406_create_sae_form
 */
class m191112_031406_create_sae_form extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('sae_assess_form', [
            'id' => $this->primaryKey(),
            'submission_id' => $this->integer(),
            'submission_committee_id' => $this->integer(),
            'sae_total' => $this->integer(),
            'sae_for' => $this->integer(),
            'sae_for_fatal' => $this->integer(),
            'sae_dom' => $this->integer(),
            'sae_dom_fatal' => $this->integer(),
            'ec' => $this->integer(),
            'ec_fatal' => $this->integer(),
            'ec_cure' => $this->integer(),
            'ec_not_cure' => $this->integer(),
            'ec_unknown_cure' => $this->integer(),
            'ec_drug' => $this->integer(),
            'ec_not_drug' => $this->integer(),
            'ec_unknown_drug' => $this->integer(),
            'resolution_id' => $this->integer(),
            'suggestion' => $this->text(),
            'condition' => $this->text(),
            'addition' => $this->text(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('sae_assess_form', 'resolution_id', 'มติกรรมการ');
        $this->addCommentOnColumn('sae_assess_form', 'suggestion', 'ข้อคิดเห็นเพิ่มเติม');
        $this->addCommentOnColumn('sae_assess_form', 'sae_total', 'จำนวนอาสาสมัครที่เกิดเหตุการณ์ไม่พึงประสงค์');
        $this->addCommentOnColumn('sae_assess_form', 'sae_for', 'เกิดเหตุการณ์จากต่างประเทศ');
        $this->addCommentOnColumn('sae_assess_form', 'sae_for_fatal', 'เกิดเหตุการณ์จากต่างประเทศและเสียชีวิต');
        $this->addCommentOnColumn('sae_assess_form', 'sae_dom', 'เกิดเหตุการณ์ในประเทศ');
        $this->addCommentOnColumn('sae_assess_form', 'sae_dom_fatal', 'เกิดเหตุการณ์ในประเทศและเสียชีวิต');
        $this->addCommentOnColumn('sae_assess_form', 'ec', 'เกิดเหตุการณ์ในสถาบันที่รับรองจาก EC');
        $this->addCommentOnColumn('sae_assess_form', 'ec_fatal', 'เกิดเหตุการณ์ในสถาบันที่รับรองจาก EC และเสียชีวิต');
        $this->addCommentOnColumn('sae_assess_form', 'ec_cure', 'อาสาสมัครได้รับการรักษาจนเป็นปกติ');
        $this->addCommentOnColumn('sae_assess_form', 'ec_not_cure', 'อาสาสมัครไม่ได้รับการรักษา');
        $this->addCommentOnColumn('sae_assess_form', 'ec_unknown_cure', 'อาสาสมัครยังไม่ทราบผลการรักษา');
        $this->addCommentOnColumn('sae_assess_form', 'ec_drug', 'ผู้วิจัยประเมินเบื้องต้นสัมพันธ์กับยาวิจัย');
        $this->addCommentOnColumn('sae_assess_form', 'ec_not_drug', 'ผู้วิจัยประเมินเบื้องต้นไม่สัมพันธ์กับยาวิจัย');
        $this->addCommentOnColumn('sae_assess_form', 'ec_unknown_drug', 'ผู้วิจัยประเมินเบื้องต้นยังไม่ทราบผลสัมพันธ์กับยาวิจัย');
        $this->addCommentOnColumn('sae_assess_form', 'condition', 'รับทราบโดยมีเง่ื่อนไข');
        $this->addCommentOnColumn('sae_assess_form', 'addition', 'ชี้แจงเพิ่มเติม');
        $this->addCommentOnColumn('sae_assess_form', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('sae_assess_form', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('sae_assess_form', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('sae_assess_form', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('sae_assess_form', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->addForeignKey('fk_sae_assess_form_submission_id', 'sae_assess_form', 'submission_id', 'submission', 'id');
        $this->addForeignKey('fk_sae_assess_form_submission_committee_id', 'sae_assess_form', 'submission_committee_id', 'submission_committee', 'id');
        $this->addForeignKey('fk_sae_assess_form_resolution_id', 'sae_assess_form', 'resolution_id', 'resolution', 'id');
        $this->addForeignKey('fk_sae_assess_form_created_by', 'sae_assess_form', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_sae_assess_form_updated_by', 'sae_assess_form', 'updated_by', 'user', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('sae_assess_form');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m191112_031406_create_sae_form cannot be reverted.\n";

      return false;
      }
     */
}
