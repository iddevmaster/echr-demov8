<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SaeAssessForm */
?>
<div class="sae-assess-form-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'submission_id',
            'submission_committee_id',
            'sae_total',
            'sae_for',
            'sae_for_fatal',
            'sae_dom',
            'sae_dom_fatal',
            'ec',
            'ec_fatal',
            'ec_cure',
            'ec_not_cure',
            'ec_unknown_cure',
            'ec_drug',
            'ec_not_drug',
            'ec_unknown_drug',
            'resolution_id',
            'suggestion:ntext',
            'condition:ntext',
            'addition:ntext',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
