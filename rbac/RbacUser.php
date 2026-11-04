<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\rbac;

use yii\web\User;

/**
 * Description of RbacUser
 *
 * @author PATIPAT
 */
class RbacUser extends User {

    public $superUserRole = 'ผู้ดูแลระบบ';

    //put your code here
    public function afterLogin($identity, $cookieBased, $duration) {
        parent::afterLogin($identity, $cookieBased, $duration);

        // Mark the user as a superuser if necessary.
        $authManager = \Yii::$app->getAuthManager();
        $session = \Yii::$app->session;
        $session->set('isSuperAdmin', $authManager->getAssignment($this->superUserRole, $this->id) !== NULL);
//        $assignments = $authManager->getAssignments($this->id);
//        \yii\helpers\VarDumper::dump($session->get('isSuperAdmin'), 10, TRUE);
//        \yii\helpers\VarDumper::dump($assignments, 10, TRUE);
        //$session->set('userRole', $assignments[0]->role_name);
//        $session->set('employee', )
//        \yii\helpers\VarDumper::dump($this, 10, TRUE);
    }

    public function can($permissionName, $params = array(), $allowCaching = true) {
//        $this->superUser = \Yii::$app->getAuthManager()->getAssignment($this->superUserRole, $this->id) !== NULL;
//        \yii\helpers\VarDumper::dump($this, 10, TRUE);
//        \yii\helpers\VarDumper::dump(\Yii::$app->session->get('isSuperAdmin'), 10, TRUE);
//        exit();
        return (\Yii::$app->session->get('isSuperAdmin') ? true : parent::can($permissionName, $params, $allowCaching));
    }

}
