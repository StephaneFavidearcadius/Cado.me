<?php

return [
    'driver' => $_ENV['STORAGE_DRIVER'] ?? 'local',
    'local_path' => $_ENV['STORAGE_PATH'] ?? 'uploads',
    's3' => [
        'endpoint' => $_ENV['S3_ENDPOINT'] ?? '',
        'bucket' => $_ENV['S3_BUCKET'] ?? '',
        'access_key' => $_ENV['S3_ACCESS_KEY'] ?? '',
        'secret_key' => $_ENV['S3_SECRET_KEY'] ?? '',
        'region' => $_ENV['S3_REGION'] ?? '',
    ],
];
