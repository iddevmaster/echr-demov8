<?php

namespace app\rbac;

use Yii;
use yii\web\Controller;

class RbacController extends Controller {

    protected $allowedActions = array();

    public function beforeAction($action) {
        if (!parent::beforeAction($action)) {
            return false;
        }

        $allow = true;
        $user = Yii::$app->getUser();
//        \yii\helpers\VarDumper::dump($user, 10, TRUE);
        $authItem = '';
        if ($this->allowedActions !== '*' && in_array($action->id, $this->allowedActions) === false) {
            // Initialize the authorization item as an empty string
            // Append the module id to the authorization item name
            // in case the controller called belongs to a module
            if (($module = $this->module) !== null)
                $authItem .= $module->id . '.';
            // Append the controller id to the authorization item name
            $authItem .= $this->id;
//            echo "auth1:" . $authItem;
            // Check if user has access to the controller
            if ($user->can($authItem . '.*') !== true) {
                // Append the action id to the authorization item name
                $authItem .= '.' . $action->id;
//                echo "auth2:" . $authItem;
                // Check if the user has access to the controller action
                if ($user->can($authItem) !== true)
                    $allow = false;
            }
        }
        if (!$allow) {
            if ($user->isGuest === true)
                $user->loginRequired();
            else
                throw new \yii\web\UnauthorizedHttpException('ไม่มีสิทธิ์เข้าใช้งาน ' . $authItem);
        }
//        echo $this->getRoute();
//        exit;
        if (!in_array($this->getRoute(), ['site/login', 'site/select-role']) && !Yii::$app->user->isGuest && !Yii::$app->session->has('currentRole')) {
//            $this->redirect(['site/select-role']);
//            Yii::$app->user->logout();
            $this->redirect(['site/login']);
//            $roles = Yii::$app->user->identity->person->getPersonRoles()->isDeleted(FALSE)->all();
            
            return false;
//            Yii::$app->end();
        }

        return $allow; // or false to not run the action
    }

}
