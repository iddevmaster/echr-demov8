<?php
echo $this->render('@bedezign/yii2/audit/views/_audit_trails', [
    // model to display audit trais for, must have a getAuditTrails() method
    'query' => $model->getAuditTrails(),
    // params for the AuditTrailSearch::search() (optional)
//    'params' => [
//        'AuditTrailSearch' => [
//            // can either be a field or an array of fields
//            // in this case we only want to show trails for the "status" field
//            'field' => 'status',
//        ]
//    ],
//    // which columns to show
//    'columns' => ['user_id', 'entry_id', 'action', 'model', 'model_id', 'old_value', 'new_value', 'diff', 'created'],
//    // set to false to hide filter
//    'filter' => false,
]);