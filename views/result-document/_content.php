<?php
if (!empty($submission->special_condition)) {
    $content = strip_tags($submission->special_condition, '<p><div><br><li><ol>');
    ?>

    <p><strong><?= isset($eng) ? "Special condition:" : "Special condition:"; ?></strong></p>
    <p><?= $content; ?></p>
<?php } ?>