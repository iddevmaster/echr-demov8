<?php

use yii\db\Migration;

/**
 * Class m260620_100005_alter_notification_history
 *
 * Adds columns to track training-expiry notifications so each (training,
 * notify_type, notify_days) reminder is sent only once.
 */
class m260620_100005_alter_notification_history extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('notification_history', 'person_training_id', $this->integer()->null()->comment('การอบรมที่แจ้งเตือน'));
        $this->addColumn('notification_history', 'notify_type', $this->smallInteger()->null()->comment('ประเภทการแจ้งเตือน 1=TRAINING_EXPIRE'));
        $this->addColumn('notification_history', 'notify_days', $this->integer()->null()->comment('เตือนล่วงหน้า (วัน)'));
        $this->createIndex(
            'idx_notification_history_training_expire',
            'notification_history',
            ['person_training_id', 'notify_type', 'notify_days']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropIndex('idx_notification_history_training_expire', 'notification_history');
        $this->dropColumn('notification_history', 'notify_days');
        $this->dropColumn('notification_history', 'notify_type');
        $this->dropColumn('notification_history', 'person_training_id');
    }

}
