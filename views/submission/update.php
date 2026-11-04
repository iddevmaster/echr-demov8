<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Submission */
$action = isset($action) ? $action : NULL;
?>
<?php if ($mode == \app\models\Submission::MODE_SETAGENDA) { ?>
    <div class="submission-update">
        <?=
        $this->render('_form', [
            'model' => $model,
            'mode' => $mode,
            'panelId' => $panelId,
            'isNow' => $isNow,
            'project' => $project
        ])
        ?>

    </div>
<?php } else { ?>
    <div class="submission-update">
        <?=
        $this->render('_form', [
            'model' => $model,
            'mode' => $mode,
            'action' => $action,
            'project' => $project,
            'submission' => $model,
        ])
        ?>

    </div>
<?php } ?>

