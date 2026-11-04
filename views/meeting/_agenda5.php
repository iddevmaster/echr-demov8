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
    <?php foreach ($agenda5s as $i => $a) :?>
        <div><?= $a->fullTitle ?> <?= $a->description ?> <?= $a->resolution ?></div>
    <?php endforeach; ?>
</body>

</html>