<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use app\models\UserQuery;
use yii\filters\auth\HttpHeaderAuth;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $password write-only password
 * @property string $verify_token รหัสการยืนยันอีเมล์
 * @property string $verify_at ยืนยันอีเมล์เมื่อ
 *
 */
class User extends ActiveRecord implements IdentityInterface {

    const STATUS_DELETED = 0;
    const STATUS_PENDING_VERIFY = 5;
    const STATUS_ACTIVE = 10;

    public $language_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => function() {
                    return date('Y-m-d H:i:s');
                },
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['username', 'unique', 'filter' => ['status' => 10]],
//            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['status'], 'integer'],
            [['created_at', 'updated_at', 'verify_at'], 'safe'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'verify_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'email' => Yii::t('app', 'Email'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'verify_token' => Yii::t('app', 'รหัสการยืนยันอีเมล์'),
            'verify_at' => Yii::t('app', 'ยืนยันอีเมล์เมื่อ'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        //return static::findOne(['SHA2(CONCAT(username, CURDATE()), 256)' => $token, 'status' => self::STATUS_ACTIVE]);
        if ($type == HttpHeaderAuth::class || $type == QueryParamAuth::class) {
            return static::findOne(['auth_key' => $token, 'status' => self::STATUS_ACTIVE]);
        }
        return static::findOne(['id' => (string) $token->claims()->get('uid'), 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username) {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token) {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
                    'password_reset_token' => $token,
                    'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token) {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() {
        $this->password_reset_token = null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson() {
        return $this->hasOne(Person::className(), ['user_id' => 'id']);
    }

//
//    public function getEmployees() {
//        return $this->hasMany(Employee::className(), ['user_id' => 'id']);
//    }

    public function getAccessToken() {
        return hash('SHA256', Yii::$app->user->identity->username . date('Y-m-d'));
    }

    public function generateVerifyToken() {
        return Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function verifyToken($token) {
        return $this->verify_token == $token;
    }

    public function sendVerifyMail() {
        $this->verify_token = $this->generateVerifyToken();
        $this->save(FALSE);
        $msg = Yii::$app->mailer->compose('registration-verification', [
            'user' => $this,
        ]);
        $msg->setSubject(\Yii::t('app', 'ยืนยันการลงทะเบียน'))
                ->setFrom([\Yii::$app->params['adminEmail'] => \Yii::$app->params['adminName']])
                ->setTo($this->person->email)
                ->send();
    }

    public function getSubmissionCount($submissionTypeGroup, $status = NULL, $resolution = NULL, $panelId = NULL, $user = NULL, $committeeStataus = NULL, $secretary = NULL, $isLegacy = NULL, $accept = NULL, $crecResult = NULL) {

        $currentRole = Yii::$app->session->get('currentRole');
        $sub = Submission::find()->isDeleted(FALSE)->submissionTypeGroup($submissionTypeGroup)->noResubmit();
        $join = ['project', 'submissionType', 'submissionType.submissionTypeGroup'];
        if ($currentRole['role_id'] != Role::RESEARCHER && $currentRole['role_id'] != Role::COORDINATOR) {
            $sub->coiPerson(\Yii::$app->user->identity->person->id);
        }
        if ($currentRole['role_id'] == Role::RESEARCHER) {
            if (isset($accept)) {
                if ($accept == 1) {
                    $sub->researcherNoAccept(\Yii::$app->user->identity->person->id);
                } elseif ($accept == 2) {
                    $sub->consultantNoAccept(\Yii::$app->user->identity->person->id);
                }
            } else {
                if ($status == Submission::STATUS_PENDING_SUBMISSION) {
                    $sub->createdBy(\Yii::$app->user->id);
                } else {
                    $join[] = 'project.projectResearchers';
                    $sub->leader(\Yii::$app->user->identity->person->id);
                    $sub->distinct();
                }
            }
        }
        if ($currentRole['role_id'] == Role::COMMITTEE) {
            $join[] = 'submissionCommittees';
            $sub->committee(\Yii::$app->user->identity->person->id, $committeeStataus)->isDeleted(FALSE);
        }
        if ($currentRole['role_id'] == Role::COORDINATOR) {
            if ($status == Submission::STATUS_PENDING_SUBMISSION) {
                if ($submissionTypeGroup == 2) {
                    $sub->projectCoordinator(\Yii::$app->user->identity->id);
                } else {
                    $sub->createdBy(\Yii::$app->user->id);
                }
            } else {
                $sub->projectCoordinator(\Yii::$app->user->identity->id);
            }
        }
        if ($currentRole['role_id'] == Role::STAFF) {
            $sub->staff(\Yii::$app->user->identity->id);
        }
//        if ($currentRole['role_id'] == Role::SECRETARY) {
//            $sub->secretary(\Yii::$app->user->identity->id);
//        }
        if (isset($crecResult)) {
            $sub->crecResolution($crecResult);
        }
        if (isset($isLegacy)) {
            $sub->isLegacy($isLegacy);
        }
        if (isset($status)) {
            $sub->status($status);
        }
        if (isset($resolution)) {
            $sub->resolution($resolution);
        }
//        if ($currentRole['role_id'] == Role::COMMITTEE) {
//            $sub->committee(\Yii::$app->user->identity->person->id);
//        }
        if (isset($secretary)) {
            $sub->secretary(\Yii::$app->user->identity->id);
        }
        if (isset($panelId)) {
            $sub->panel($panelId);
        }

        $sub->joinWith($join);
//        $sub->andWhere(['xx'=>'x']);
//        $sub->groupBy('submission.status');
        return $sub->count();
    }
    public function getSubmissionAlert($submissionTypeGroup, $status = NULL, $resolution = NULL, $panelId = NULL, $user = NULL, $committeeStataus = NULL, $secretary = NULL, $isLegacy = NULL, $accept = NULL, $ecStatus = NULL) {

        $currentRole = Yii::$app->session->get('currentRole');
        $sub = Submission::find()->isDeleted(FALSE)->submissionTypeGroup($submissionTypeGroup)->noResubmit();
        $join = ['project', 'submissionType', 'submissionType.submissionTypeGroup'];
        if ($currentRole['role_id'] != Role::RESEARCHER && $currentRole['role_id'] != Role::COORDINATOR) {
            $sub->coiPerson(\Yii::$app->user->identity->person->id);
        }
        if ($currentRole['role_id'] == Role::RESEARCHER) {
            if (isset($accept)) {
                if ($accept == 1) {
                    $sub->researcherNoAccept(\Yii::$app->user->identity->person->id);
                } elseif ($accept == 2) {
                    $sub->consultantNoAccept(\Yii::$app->user->identity->person->id);
                }
            } else {
                if ($status == Submission::STATUS_PENDING_SUBMISSION) {
                    $sub->createdBy(\Yii::$app->user->id);
                } else {
                    $join[] = 'project.projectResearchers';
                    $sub->leader(\Yii::$app->user->identity->person->id);
                    $sub->distinct();
                }
            }
        }
        if ($currentRole['role_id'] == Role::COMMITTEE) {
            $join[] = 'submissionCommittees';
            $sub->committee(\Yii::$app->user->identity->person->id, $committeeStataus)->isDeleted(FALSE);
        }
        if ($currentRole['role_id'] == Role::COORDINATOR) {
            if ($status == Submission::STATUS_PENDING_SUBMISSION) {
                if ($submissionTypeGroup == 2) {
                    $sub->projectCoordinator(\Yii::$app->user->identity->id);
                } else {
                    $sub->createdBy(\Yii::$app->user->id);
                }
            } else {
                $sub->projectCoordinator(\Yii::$app->user->identity->id);
            }
        }
        if ($currentRole['role_id'] == Role::STAFF) {
            $sub->staff(\Yii::$app->user->identity->id);
        }
//        if ($currentRole['role_id'] == Role::SECRETARY) {
//            $sub->secretary(\Yii::$app->user->identity->id);
//        }
        if (isset($crecResult)) {
            $sub->crecResolution($crecResult);
        }
        if (isset($isLegacy)) {
            $sub->isLegacy($isLegacy);
        }
        if (isset($status)) {
            $sub->status($status);
        }
        if (isset($resolution)) {
            $sub->resolution($resolution);
        }
//        if ($currentRole['role_id'] == Role::COMMITTEE) {
//            $sub->committee(\Yii::$app->user->identity->person->id);
//        }
        if (isset($secretary)) {
            $sub->secretary(\Yii::$app->user->identity->id);
        }
        if (isset($panelId)) {
            $sub->panel($panelId);
        }
        $sub->joinWith($join);

        $sub->isCrec();

        if ($sub->count() > 0) {
            return '<i class="icon wb-warning" aria-hidden="true" style = "margin-left: 10px;margin-right: 0px;"></i>';
        } else {
            return NULL;
        }
    }
    public function getSubmissionCountReport($submissionTypeGroup, $panelId = NULL, $startDate = NULL, $endDate = NULL, $submissionTypeId = NULL) {

        $currentRole = Yii::$app->session->get('currentRole');
        $sub = Submission::find()->isDeleted(FALSE)->submissionTypeGroup($submissionTypeGroup)->noRef();
        $join = ['project', 'submissionType', 'submissionType.submissionTypeGroup'];

        if (!empty($panelId)) {
            $sub->panel($panelId);
        }
        if (!empty($submissionTypeId)) {
            $sub->submissionType($submissionTypeId);
        }
        if (!empty($startDate) && !empty($endDate)) {
            $sub->dateStatusBetween($startDate, $endDate, Submission::STATUS_CODE_GENERATED);
        }
        $sub->joinWith($join);

        return $sub->count();
    }

    public function getProjectCount($submissionTypeGroup, $startDate = NULL, $endDate = NULL, $submissionTypeId = NULL, $status = NULL, $researcherOrg = NULL, $researcherDep = NULL, $researcherDiv = NULL, $resolution = NULL, $panelId = null) {

        $researcher = <<<q
            SELECT pr.id
            FROM project_researcher pr
                INNER JOIN person p ON pr.person_id = p.id
            WHERE pr.deleted = 0 AND pr.project_id = pj.id AND pr.is_leader = 1
q;
        if (!empty($researcherOrg)) {
            $researcher .= " AND p.organization_id = {$researcherOrg}";
        }
        if (!empty($researcherDep)) {
            $researcher .= " AND p.department_id = {$researcherDep}";
        }
        if (!empty($researcherDiv)) {
            $researcher .= " AND p.division_id = {$researcherDiv}";
        }

        $panelCond = '';
        if (!empty($panelId)) {
            $panelCond = " AND pj.panel_id = {$panelId}";
        }

        $submission = <<<q
            SELECT s.project_id
            FROM submission s
                INNER JOIN submission_type st ON s.submission_type_id = st.id
            WHERE s.deleted = 0 AND s.submission_type_id = '{$submissionTypeId}'
                AND st.submission_type_group_id = {$submissionTypeGroup}
            GROUP BY s.project_id
q;

        $submission2 = $submission3 = <<<q
            SELECT s.project_id
            FROM submission s
                INNER JOIN submission_type st ON s.submission_type_id = st.id
            WHERE s.deleted = 0 AND st.submission_type_group_id = {$submissionTypeGroup}
                AND s.project_id = pj.id 
q;
        if (!empty($resolution)) {
            $submission2 .= " AND s.resolution = '{$resolution}'";
        }
        if (!empty($startDate)) {
            $d = new \DateTime($startDate);
            $dateStatus = <<<q
                SELECT sh.id
                FROM submission_status_history sh
                WHERE sh.submission_id = s.id AND sh.status = '{$status}'
q;
            if ($d !== FALSE) {
                $dateStatus .= " AND sh.created_at >= '{$d->format('Y-m-d')}'";
            }
            if (!empty($endDate)) {
                $d = new \DateTime($endDate);
                if ($d !== FALSE) {
                    $d->add(new \DateInterval("P1D"));
                    if (!isset($resolution)) {
                        $dateStatus .= " AND sh.created_at < '{$d->format('Y-m-d')}'";
                    } else {
                        $dateStatus2 = $dateStatus . " AND sh.created_at <= '{$d->format('Y-m-d')}'";
                        $submission3 .= " AND EXISTS ({$dateStatus2})";
                    }
                }
            }

            $submission2 .= " AND EXISTS ({$dateStatus})";
        }

        $query = <<<q
            SELECT COUNT(*)
            FROM project pj
            WHERE pj.deleted = 0
                AND EXISTS ($researcher)
                AND pj.id IN ($submission)
                AND EXISTS ($submission2)
                {$panelCond}
q;
        if (isset($resolution)) {
            $query .= " AND EXISTS ($submission3)";
        }
        return Yii::$app->db->createCommand($query)->queryScalar();
    }

    public function getSubmissionCountReportNew($submissionTypeGroup, $startDate = NULL, $endDate = NULL, $submissionTypeId = NULL, $status = NULL, $researcherOrg = NULL, $researcherDep = NULL, $researcherDiv = NULL, $resolution = NULL) {

        $currentRole = Yii::$app->session->get('currentRole');
        $sub = Submission::find()->isDeleted(FALSE)->submissionTypeGroup($submissionTypeGroup)->noRef();
        $join = ['project', 'submissionType', 'submissionType.submissionTypeGroup'];
        if (!empty($researcherOrg)) {
            $sub->researcherOrg($researcherOrg);
        }
        if (!empty($researcherDep)) {
            $sub->researcherDep($researcherDep);
        }
        if (!empty($researcherDiv)) {
            $sub->researcherDiv($researcherDiv);
        }
        if (!empty($status)) {
            $sub->status($status);
        }
        if (!empty($resolution)) {
            $sub->resolution($resolution);
        }
        if (!empty($submissionTypeId)) {
            $sub->submissionType($submissionTypeId);
        }
        if (!empty($startDate) && !empty($endDate)) {
            $sub->dateStatusBetween($startDate, $endDate, $status);
        }

        $sub->joinWith($join);

        return $sub->count();
    }

    public function submissionReportNew($submissionTypeGroup, $startDate = NULL, $endDate = NULL, $submissionTypeId = NULL, $status = NULL, $researcherOrg = NULL, $researcherDep = NULL, $researcherDiv = NULL, $resolution = NULL) {

        $currentRole = Yii::$app->session->get('currentRole');
        $sub = Submission::find()->isDeleted(FALSE)->refSubmissionTypeGroup($submissionTypeGroup, $submissionTypeId, $resolution);
        $join = ['project', 'submissionType', 'submissionType.submissionTypeGroup'];
        if (!empty($researcherOrg)) {
            $sub->researcherOrg($researcherOrg);
        }
        if (!empty($researcherDep)) {
            $sub->researcherDep($researcherDep);
        }
        if (!empty($researcherDiv)) {
            $sub->researcherDiv($researcherDiv);
        }
        if (!empty($status)) {
            $sub->status($status);
        }
//        if (!empty($resolution)) {
//            $sub->resolution($resolution);
//        }
//        if (!empty($submissionTypeId)) {
//            $sub->submissionType($submissionTypeId);
//        }
        if (!empty($startDate) && !empty($endDate)) {
            $sub->dateStatusBetween($startDate, $endDate, $status);
        }

        $sub->joinWith($join);

        return $sub->count();
    }

    public function getMonthlySubmissionCountReport($year, $month, $panelId = NULL, $submissionTypeId = NULL) {

        $currentRole = Yii::$app->session->get('currentRole');
        $sub = Submission::find()->isDeleted(FALSE)->noRef();
        $join = ['project', 'submissionType', 'submissionType.submissionTypeGroup'];
        $status = Submission::STATUS_CODE_GENERATED;
        if (!empty($panelId)) {
            $sub->panel($panelId);
        }
        if (!empty($submissionTypeId)) {
            $sub->submissionType($submissionTypeId);
        }
        if (!empty($year)) {
            $sub->yearMonthStatus($year, $month, $status);
        }
        $sub->joinWith($join);

        return $sub->count();
    }

    public function getSubmissionNewCount($submissionTypeGroup, $status = NULL, $isLegacy = NULL) {
//        $currentRole = \Yii::$app->session->get('currentRole');
//        $user = NULL;
//        if ($currentRole['role_id'] == Role::RESEARCHER) {
//            $user = \Yii::$app->user->identity->person->id;
//        }
//        \yii\helpers\VarDumper::dump([$group, $status, $resolution, $panelId]);
        $currentRole = Yii::$app->session->get('currentRole');
        $sub = Submission::find()->isDeleted(FALSE)->submissionTypeGroup($submissionTypeGroup);
        $join = ['project', 'submissionType', 'submissionType.submissionTypeGroup'];

        if (isset($status)) {
            $sub->status($status);
        }
        if ($isLegacy == 1) {
            $sub->isLegacy($isLegacy);
        } elseif ($isLegacy == 2) {
            $sub->isLegacy(0);
        }
        $sub->joinWith($join);
        if ($isLegacy != 1) {
            $sub->andWhere(['project.project_code' => NULL]);
        }
//        $sub->andWhere(['project.project_code' => NULL]);
//        $sub->groupBy('submission.status');
        return $sub->count();
    }

    public function getSubmissionContinueCount($submissionTypeGroup, $status = NULL) {
//        $currentRole = \Yii::$app->session->get('currentRole');
//        $user = NULL;
//        if ($currentRole['role_id'] == Role::RESEARCHER) {
//            $user = \Yii::$app->user->identity->person->id;
//        }
//        \yii\helpers\VarDumper::dump([$group, $status, $resolution, $panelId]);
        $currentRole = Yii::$app->session->get('currentRole');
        $sub = Submission::find()->isDeleted(FALSE)->submissionTypeGroup($submissionTypeGroup);
        $join = ['project', 'submissionType', 'submissionType.submissionTypeGroup'];

        if (isset($status)) {
            $sub->status($status);
        }

        $sub->joinWith($join);
        $sub->andWhere(['submission.responsible_person' => NULL]);
//        $sub->groupBy('submission.status');
        return $sub->count();
    }

    public function getMeetingCount($status = NULL) {
        $currentRole = Yii::$app->session->get('currentRole');
        $sub = Meeting::find()->isDeleted(FALSE); //->cSstatus($status);

        if ($currentRole['role_id'] == Role::STAFF) {
            $sub->staffCheck(\Yii::$app->user->identity->id);
        }
        if ($currentRole['role_id'] == Role::SECRETARY) {
            $sub->secretary(\Yii::$app->user->identity->id);
        }
        if ($currentRole['role_id'] == Role::PRESIDENT) {
            $sub->president(\Yii::$app->user->identity->id);
        }
        if ($currentRole['role_id'] == Role::COPRESIDENT) {
            $sub->president(\Yii::$app->user->identity->id);
        }
        if ($currentRole['role_id'] == Role::ADMIN) {
            $sub->adminCheck();
        }

        return $sub->count();
    }

    public function getSubmissionNewPanelCount($submissionTypeGroup, $status = NULL, $staff = NULL) {
//        $currentRole = \Yii::$app->session->get('currentRole');
//        $user = NULL;
//        if ($currentRole['role_id'] == Role::RESEARCHER) {
//            $user = \Yii::$app->user->identity->person->id;
//        }
//        \yii\helpers\VarDumper::dump([$group, $status, $resolution, $panelId]);
        $currentRole = Yii::$app->session->get('currentRole');
        $sub = Submission::find()->isDeleted(FALSE)->submissionTypeGroup($submissionTypeGroup);
        $join = ['project', 'submissionType', 'submissionType.submissionTypeGroup'];

        if (isset($status)) {
            $sub->status($status);
        }
        if (isset($staff)) {
            $sub->staff($staff);
            $sub->isRef();
        }

        $sub->joinWith($join);
//        $sub->andWhere(['project.project_code'=>NULL]);
//        $sub->groupBy('submission.status');
        return $sub->count();
    }

    public function getSubmissionIsConsideration($status) {
//        $currentRole = \Yii::$app->session->get('currentRole');
//        $user = NULL;
//        if ($currentRole['role_id'] == Role::RESEARCHER) {
//            $user = \Yii::$app->user->identity->person->id;
//        }
//        \yii\helpers\VarDumper::dump([$group, $status, $resolution, $panelId]);
        $currentRole = Yii::$app->session->get('currentRole');
        $sub = Submission::find()->isDeleted(FALSE)->submissionTypeCon($status);
        $join = ['project', 'submissionType'];

        $sub->joinWith($join);
//        $sub->andWhere(['xx'=>'x']);
//        $sub->groupBy('submission.status');
        return $sub->count();
    }

    public function canCheckDocument($document) {
        return PersonRole::find()->andWhere(['deleted' => 0, 'role_id' => $roleId])->exists();
    }

    public function getAbleToContinueProjects() {
        $currentRole = \Yii::$app->session->get('currentRole');
        if ($currentRole['role_id'] == Role::COORDINATOR) {
            return Project::find()->isDeleted(FALSE)->ableToContinue()->coordinator(\Yii::$app->user->id)->orderBy('CONVERT(project.project_code USING TIS620) DESC')->all();
        } elseif ($currentRole['role_id'] == Role::RESEARCHER) {
            return Project::find()->joinWith(['projectResearchers'])->isDeleted(FALSE)->ableToContinue()->leader(\Yii::$app->user->identity->person->id)->orderBy('CONVERT(project.project_code USING TIS620) DESC')->all();
        }
    }

    public function getLatestAlerts() {
        return Alert::find()->isAcknowledged(FALSE)->user($this->id)->orderBy('created_at DESC')->all();
    }

    public function getUnshownAlerts() {
        return Alert::find()->isAlerted(FALSE)->user($this->id)->orderBy('created_at DESC')->all();
    }

    public function getHasApprovePendingSubmissionFromCo() {
        $status = Submission::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER;
        $query = <<<q
                SELECT COUNT(*)
                FROM project_researcher pr
                    INNER JOIN submission s ON pr.submission_id = s.id
                WHERE pr.deleted = 0 AND s.deleted = 0 AND pr.is_leader = 1
                    AND pr.person_id = {$this->person->id} AND s.project_coordinator_id IS NOT NULL
                    AND s.status = {$status}
q;
        $c = \Yii::$app->db->createCommand($query)->queryScalar();
        return $c > 0;
    }

    /**
     * @inheritdoc
     * @return DataSyncQuery the active query used by this AR class.
     */
    public static function find() {
        return new UserQuery(get_called_class());
    }

}
