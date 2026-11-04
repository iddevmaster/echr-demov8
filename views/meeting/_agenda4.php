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

        td {
            padding-left: 5px;
            padding-right: 5px;
        }
    </style>
</head>

<body style="font-family: 'TH Sarabun New' !important; font-size: 18px !important;">
    <table>
        <tbody>
            <?php foreach ($agenda4s as $i => $a) :
            ?>
                <tr>
                    <td><?= $a->fullTitle ?></td>
                    <td style="text-align: center;">จำนวน</td>
                    <td style="text-align: center;"><?= $a->getMeetingAgendas()->isDeleted(FALSE)->count() ?></td>
                    <td style="text-align: center;">โครงการ</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>