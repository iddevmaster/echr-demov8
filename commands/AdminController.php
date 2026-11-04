<?php

namespace app\commands;

use app\models\Person;
use app\models\Project;
use app\models\User;
use Yii;
use yii\console\Controller;

class AdminController extends Controller {
  public function actionGenCrecUser($username, $password)
  {
    $user = new User();
    $user->username = $username;
    $user->setPassword($password);
    $user->generateAuthKey();
    $user->save(FALSE);

    $person = new Person();
    $person->first_name = $username;
    $person->last_name = '-';
    $person->first_name_eng = $username;
    $person->last_name_eng = '-';
    $person->title_id = 1;
    $person->idcard_no = '-';
    $person->email = '-';
    $person->mobile_no = '-';
    $person->user_id = $user->id;
    $person->save(false);
  }

  public function actionUpdateCrecProjectLeader() {
    $sql =<<<q
      SELECT DISTINCT s.project_id, s.crec_leader_name, s.crec_leader_name_eng
        , s.crec_leader_site_name, s.crec_leader_site_name_eng
        , s.crec_leader_org_name, s.crec_leader_org_name_eng
      FROM submission s
      WHERE s.deleted = 0 AND s.crec_leader_name IS NOT NULL
      
q;
    $results =  Yii::$app->db->createCommand($sql)->queryAll();
    foreach ($results as $r) {
      $p = Project::findOne($r['project_id']);
      $p->crec_leader_name = $r['crec_leader_name'];
      $p->crec_leader_name_eng = $r['crec_leader_name_eng'];
      $p->crec_leader_site_name = $r['crec_leader_site_name'];
      $p->crec_leader_site_name_eng = $r['crec_leader_site_name_eng'];
      $p->crec_leader_org_name = $r['crec_leader_org_name'];
      $p->crec_leader_org_name_eng = $r['crec_leader_org_name_eng'];
      $p->save(false);
    }
  }
}