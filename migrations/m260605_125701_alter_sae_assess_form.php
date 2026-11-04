<?php

use yii\db\Migration;

/**
 * Class m260605_125701_alter_sae_assess_form
 */
class m260605_125701_alter_sae_assess_form extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('sae_assess_form', 'review_choice_id', $this->integer());
        $this->addColumn('sae_assess_form', 'review_choice_text', $this->string());
        $this->addCommentOnColumn('sae_assess_form', 'review_choice_id', 'ชนิดรายงาน');
        $this->addCommentOnColumn('sae_assess_form', 'review_choice_text', 'ชนิดรายงานอื่นๆ');

        $this->createTable('sae_assess_form_review', [
            'id' => $this->primaryKey(),
            'sae_assess_form_id' => $this->integer(),
            'review_choice_id' => $this->integer(),
            'review_choice_text' => $this->string(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnColumn('sae_assess_form_review', 'sae_assess_form_id', 'ฟอร์มประเมิน');
        $this->addCommentOnColumn('sae_assess_form_review', 'review_choice_id', 'ชนิดรายงาน');
        $this->addCommentOnColumn('sae_assess_form_review', 'review_choice_text', 'ชนิดรายงานอื่นๆ');
        $this->addCommentOnColumn('sae_assess_form_review', 'deleted', '0=ใช้งาน,1=ไม่ใช้งาน');
        $this->addCommentOnColumn('sae_assess_form_review', 'created_by', 'สร้างโดย');
        $this->addCommentOnColumn('sae_assess_form_review', 'created_at', 'สร้างเมื่อ');
        $this->addCommentOnColumn('sae_assess_form_review', 'updated_by', 'ปรับปรุงโดย');
        $this->addCommentOnColumn('sae_assess_form_review', 'updated_at', 'ปรับปรุงเมื่อ');

        $this->addForeignKey('fk_sae_assess_form_review_sae_assess_form_id', 'sae_assess_form_review', 'sae_assess_form_id', 'sae_assess_form', 'id');
        $this->addForeignKey('fk_sae_assess_form_review_review_choice_id', 'sae_assess_form_review', 'review_choice_id', 'review_choice', 'id');
        $this->addForeignKey('fk_sae_assess_form_review_created_by', 'sae_assess_form_review', 'created_by', 'user', 'id');
        $this->addForeignKey('fk_sae_assess_form_review_updated_by', 'sae_assess_form_review', 'updated_by', 'user', 'id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('sae_assess_form', 'review_choice_id');
        $this->dropColumn('sae_assess_form', 'review_choice_text');

    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m260605_125701_alter_sae_assess_form cannot be reverted.\n";

      return false;
      }
     */
}
