<?php

use app\models\Project;
use yii\db\Migration;

/**
 * Class m221025_132543_alter_project
 */
class m221025_132543_alter_project extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('agenda', 'project_active', $this->boolean());
        $this->addCommentOnColumn('agenda', 'project_active', 'สถานะโครงการเมื่อรับรอง');
        $this->createIndex('idx_agenda_project_active', 'agenda', 'project_active');

        $this->addColumn('project', 'is_active', $this->boolean());
        $this->addCommentOnColumn('project', 'is_active', 'สถานะโครงการ');
        $this->createIndex('idx_project_is_active', 'project', 'is_active');

        $this->update('agenda', ['project_active' => false], ['label' => '3.1']);
        $this->update('agenda', ['project_active' => true], ['label' => ['3.3', '3.4', '4.1', '4.2', '4.3', '4.4']]);

        $sql = <<<q
            SELECT ma.project_id, MAX(ma.id) AS id
            FROM meeting_agenda ma
                INNER JOIN agenda a ON ma.agenda_id = a.id
            WHERE ma.deleted = 0 AND a.project_active IS NOT NULL AND ma.resolution = 'Y' AND a.project_active = 0
            GROUP BY project_id
q;
        $rows = Yii::$app->db->createCommand($sql)->queryAll();
        foreach ($rows as $r) {
            $this->update('project', ['is_active' => false], ['id' => $r['project_id']]);
        }
        $sql = <<<q
            SELECT ma.project_id, MAX(ma.id) AS id
            FROM meeting_agenda ma
                INNER JOIN agenda a ON ma.agenda_id = a.id
            WHERE ma.deleted = 0 AND a.project_active IS NOT NULL AND ma.resolution = 'Y' AND a.project_active = 1
            GROUP BY project_id
q;
        $rows = Yii::$app->db->createCommand($sql)->queryAll();
        foreach ($rows as $r) {
            $this->update('project', ['is_active' => true], ['id' => $r['project_id']]);
        }

        $sql = <<<q
            UPDATE project
            SET is_active = false
            WHERE is_active = true AND (TIMESTAMPDIFF(DAY, expire_at, NOW()) > 365 OR TIMESTAMPDIFF(DAY, certified_date, NOW()) > 1095)
q;
        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropIndex('idx_project_is_active', 'project');
        $this->dropColumn('project', 'is_active');

        $this->dropIndex('idx_agenda_project_active', 'agenda');
        $this->dropColumn('agenda', 'project_active');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m221025_132543_alter_project cannot be reverted.\n";

      return false;
      }
     */
}
