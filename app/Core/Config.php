<?php

namespace App\Core;

use Dotenv\Dotenv;

class Config
{
    private static array $configs = [];

    public static function load(): void
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();

        $configFiles = [
            'app' => 'config/app.php',
            'database' => 'config/database.php',
            'auth' => 'config/auth.php',
            'storage' => 'config/storage.php',
            'abonnement' => 'config/abonnement.php',
        ];

        foreach ($configFiles as $key => $file) {
            if (file_exists($file)) {
                self::$configs[$key] = require $file;
            }
        }
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $keys = explode('.', $key);
        $config = self::$configs;

        foreach ($keys as $k) {
            if (!isset($config[$k])) {
                return $default;
            }
            $config = $config[$k];
        }

        return $config;
    }
}
