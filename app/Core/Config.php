<?php

namespace App\Core;

use Dotenv\Dotenv;

class Config
{
    private static array $configs = [];

    private static string $rootPath = '';

    public static function load(): void
    {
        self::$rootPath = dirname(__DIR__, 2);

        $dotenv = Dotenv::createImmutable(self::$rootPath);
        $dotenv->load();

        $configFiles = [
            'app' => 'config/app.php',
            'database' => 'config/database.php',
            'auth' => 'config/auth.php',
            'storage' => 'config/storage.php',
            'abonnement' => 'config/abonnement.php',
        ];

        foreach ($configFiles as $key => $file) {
            $path = self::$rootPath . '/' . $file;
            if (file_exists($path)) {
                self::$configs[$key] = require $path;
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
