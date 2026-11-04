<html>

<head>
    <style>
        table,
        th,
        td {
            border: 1px solid;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
    </style>
</head>

<body style="font-family: 'TH Sarabun New' !important; font-size: 18px !important;">
    <table>
        <tbody>
            <?php foreach ($people as $i => $p) :
                $rts = $p->registerTransactions;
            ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= $p->person->fullName ?></td>
                    <td>
                        <?= $p->role_name ?>
                        <?= count($rts) > 0 ?: "(เข้าประชุม" . Yii::$app->formatter->asTime($rts[0]->registered_at) . " น.)" ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>