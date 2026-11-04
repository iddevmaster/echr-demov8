<?php

return [
    'adminEmail' => 'echr@kku.ac.th',
    'adminName' => 'ศูนย์จริยธรรมการวิจัยในมนุษย์ มหาวิทยาลัยขอนแก่น',
    'sendMailCmd' => 'php /app/web/echr/yii email-queue/send-mail',
//    'dateFormat' => 'd/m/Y',
//    'dateFormatJs' => 'DD/MM/YYYY',
    'alertCheckInterval' => 5 * 60 * 1000,
    'user.passwordResetTokenExpire' => 15 * 60 * 1000,
    'i18nSuffixes' => [
        'th' => '',
        'en' => '_eng',
    ]
];
