<?php

use yii\db\Migration;

/**
 * Class m260626_100001_alter_submission
 *
 * Keeps the original (un-stamped) CV / training file so the e-signature flow can
 * serve the stamped file when "ประทับชื่อ" is chosen, or revert to the original
 * when it is not — without losing the raw upload.
 */
class m260626_100001_alter_submission extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('submission', 'issue1_eng', $this->text());
                $this->addCommentOnColumn('submission', 'issue1_eng', 'ผลการพิจารณาสำหรับผู้วิจัย (ภาษาอังกฤษ)');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropColumn('submission', 'issue1_eng');
    }

}
