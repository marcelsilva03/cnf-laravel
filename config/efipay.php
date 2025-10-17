<?php
$sandbox = env('EFI_ENV') !== 'prod';
$debug = env('EFI_DEBUG', false) === 'true';

$clientId = $sandbox ? env('EFI_CLIENT_ID_HOMOL') : env('EFI_CLIENT_ID_PROD');
$clientSecret = $sandbox ? env('EFI_CLIENT_SECRET_HOMOL') : env('EFI_CLIENT_SECRET_PROD');

$certificatePath = storage_path(env('EFI_CERTIFICATE_PATH'));
$pwdCertificate = env('EFI_PWD_CERTIFICATE', '');

return [
    'options' => [
        "clientId" => $clientId,
        "clientSecret" => $clientSecret,
        "certificate" => $certificatePath,
        "pwdCertificate" => $pwdCertificate,
        "sandbox" => $sandbox,
        "debug" => $debug,
        "timeout" => 30,
    ],
    'clientId' => $clientId,
    'clientSecret' => $clientSecret,
];
