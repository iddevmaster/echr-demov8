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
            <?php
            $parentId = '';
            foreach ($agendas as $i => $a) :
                $groupTitle = '';
                if ($parentId != $a->parent_id) {
                    $parentId = $a->parent_id;
                    $c = $a->parent->getMeetingAgendas()->isDeleted(FALSE)->count();
                    $groupTitle = "โครงการที่ขอรับการพิจารณาจริยธรรมการวิจัยในมนุษย์<br>วาระการประชุมครั้งที่ {$model->yearNo} วาระที่ {$a->parent->sort_label}<br>“{$a->parent->title}” จำนวน {$c} โครงการ";
                }
                $desc = isset($a->description) ? $a->description : "";
                $desc .= isset($a->submission->issue1) ? $a->submission->issue1 : "";
                $desc .= isset($a->submission->issue2) ? $a->submission->issue2 : "";

                $summary = "มติที่ประชุม " . $a->submission->resolutionLabel;
                $summary .= isset($a->conclusion) ? $a->conclusion : "";
                $summary .= isset($a->summary) ? $a->summary : "";
            ?>
                <?php if (!empty($groupTitle)) : ?>
                    <tr>
                        <td colspan="2" style="background-color: darkgray; text-align: center;"><?= $groupTitle ?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td style="background-color: darkgray;">วาระที่ <?= $a->sort_label; ?></td>
                    <td style="background-color: darkgray;">เลขที่โครงการ <?= $a->submission->project->project_code; ?></td>
                </tr>
                <tr>
                    <td colspan="2">ชื่อโครงการ (ภาษาไทย) <?= $a->submission->project->name_thai; ?></td>
                </tr>
                <tr>
                    <td colspan="2">ชื่อโครงการ (ภาษาอังกฤษ) <?= $a->submission->project->name_eng; ?></td>
                </tr>
                <tr>
                    <td>ผู้วิจัย <?= $a->submission->projectLeader->person->fullName; ?></td>
                    <td>สังกัด <?= $a->submission->projectLeader->person->divisionThai; ?></td>
                </tr>
                <tr>
                    <td colspan="2"><?= $desc; ?></td>
                </tr>
                <tr>
                    <td colspan="2"><?= $summary; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>