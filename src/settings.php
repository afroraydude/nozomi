<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],
        
        'nozomi' => [
            'pages_path' => __DIR__ . '/../nozomi/templates',
            'cache_path' => false,
            'data_path' => __DIR__ . '../nozomi/data',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'nozomi_site',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
    ],
];
