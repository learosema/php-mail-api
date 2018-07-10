<?php

$appSettingsPath = "../data/appsettings.json";
if (file_exists($appSettingsPath)) {
    $appSettings = json_decode(file_get_contents($appSettingsPath), true);
} else {
    $appSettings = [
        'mailto' => 'email@example.com',
        'recaptcha_secret' => 'secret key',
        'recaptcha_key' => 'public key',
        'allowed_origins' => array('terabaud.de', 'terabaud.github.io'),
        'db_connection' => 'sqlite:../data/database.sqlite',
        'db_user' => NULL,
        'db_password' => NULL
    ];
    @file_put_contents($appSettingsPath, json_encode($appSettings, JSON_PRETTY_PRINT));
}

return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header
        
        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],
        // App settings 
        'appSettings' => $appSettings,
        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
    ],
];
