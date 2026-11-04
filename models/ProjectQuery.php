<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Project]].
 *
 * @see Project
 */
class ProjectQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return Project[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Project|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function leader($leader) {
        return $this->andWhere([
                    'project_researcher.deleted' => FALSE,
                    'project_researcher.is_leader' => TRUE,
                    'project_researcher.person_id' => $leader
        ]);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['project.deleted' => $deleted]);
    }

    public function isActive($active = TRUE) {
        return $this->andWhere(['project.is_active' => $active]);
    }

    public function coordinator($coordinatorId) {
       return  $this->andWhere(['or', ['=', 'project.project_coordinator_id', $coordinatorId], ['=', 'project.project_coordinator_2nd_id', $coordinatorId], ['=', 'project.project_coordinator_3rd_id', $coordinatorId]]);

//        return $this->andWhere(['project.project_coordinator_id' => $coordinatorId]);
    }

    public function isClosed($closed = TRUE) {
        return $this->andWhere(['project.is_closed' => $closed]);
    }

    public function nextProgressDay($day) {
        return $this->andWhere(['DATEDIFF(project.next_progress_at, CURDATE())' => $day]);
    }

    public function nextRenewDay($day) {
        return $this->andWhere(['DATEDIFF(project.expire_at, CURDATE())' => $day]);
    }

    public function ableToContinue() {
        $subQuery = (new \yii\db\Query())
                ->select('id')
                ->from('submission s')
                ->andWhere('s.project_id = project.id')
                ->andFilterCompare('s.status', Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, '>=')
                ->andWhere(['s.deleted' => FALSE, 's.resolution' => Submission::RESOLUTION_Y]);
        return $this->andWhere(['exists', $subQuery]);
    }

    public function progressPeriod($period) {
        return $this->andWhere(['project.progress_period' => $period]);
    }

    public function crecNumber($crecNumber)
    {
        return $this->andWhere(['project.crec_number' => $crecNumber]);
    }

}
