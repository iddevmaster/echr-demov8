<?php
//$person = $submission->presidentPerson->person;

if (isset($submission->president_person)) {
    $person = $submission->presidentPerson->person;
} else {
    $person = $submission->project->panel->chairman;
}


$signType = ($type == 'thai') ? 'thai' : 'eng';
$signPath = ($type == 'thai') ? $person->templatePathAliasSignatureThai : $person->templatePathAliasSignature;
?>
<?php if (!empty($person->signature) && file_exists($signPath)) { ?>
    <?php
    $rawData = $person->getDecryptedSignatureData($signType);
    if ($rawData !== false):
        $imageData = base64_encode($rawData);
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($rawData) ?: 'image/png';
    ?>
        <p style="padding-left: 250px; padding-bottom: -50px;"><img src="data:<?= $mimeType ?>;base64,<?= $imageData ?>" /></p>
    <?php endif; ?>
<?php } ?>
