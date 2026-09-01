<?php

namespace App\Core;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Level;

class Logger
{
    private static ?MonologLogger $instance = null;

    public static function getInstance(): MonologLogger
    {
        if (self::$instance === null) {
            self::$instance = new MonologLogger('cado_me');

            $logLevel = Config::get('app.env') === 'production' ? Level::Warning : Level::Debug;
            $logDir = dirname(__DIR__, 2) . '/storage/logs';

            if (!is_dir($logDir)) {
                mkdir($logDir, 0755, true);
            }

            // Fichier rotatif (un par jour)
            $handler = new RotatingFileHandler(
                $logDir . '/app_%Y-%m-%d.log',
                $logLevel
            );

            // En production, logger aussi les erreurs dans un fichier dédié
            if (Config::get('app.env') === 'production') {
                $errorHandler = new RotatingFileHandler(
                    $logDir . '/error_%Y-%m-%d.log',
                    Level::Error
                );
                $                self::$instance->pushHandler($errorHandler);
            }

            self::$instance->pushHandler($handler);
        }

        return self::$instance;
    }

    /**
     * Raccourcis
     */
    public static function info(string $message, array $context = []): void
    {
        self::getInstance()->info($message, $context);
    }

    public static function warning(string $message, array $context = []): void
    {
        self::getInstance()->warning($message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::getInstance()->error($message, $context);
    }

    public static function debug(string $message, array $context = []): void
    {
        self::getInstance()->debug($message, $context);
    }

    /**
     * Logger une action d'audit
     */
    public static function audit(string $action, string $entite, ?int $userId = null, ?int $communauteId = null, array $data = []): void
    {
        self::getInstance()->info("AUDIT: {$action} {$entite}", array_merge($data, [
            'user_id' => $userId,
            'communaute_id' => $communauteId,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
        ]));
    }
}
