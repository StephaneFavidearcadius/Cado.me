<?php

return [
    'session_name' => $_ENV['SESSION_NAME'] ?? 'cado_me_session',
    'lifetime' => 7200, // 2 heures
    'password_algo' => PASSWORD_BCRYPT,
    'password_options' => [
        'cost' => 12,
    ],
];
