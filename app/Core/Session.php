<?php

namespace App\Core;

class Session
{
    private static bool $started = false;

    public static function start(): void
    {
        if (!self::$started && session_status() === PHP_SESSION_NONE) {
            $sessionName = Config::get('auth.session_name', 'cado_me_session');
            session_name($sessionName);
            session_start([
                'cookie_lifetime' => Config::get('auth.lifetime', 7200),
                'cookie_httponly' => true,
                'cookie_secure' => isset($_SERVER['HTTPS']),
                'cookie_samesite' => 'Lax',
                'use_strict_mode' => true,
            ]);
            self::$started = true;
        }
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function has(string $key): bool
    {
        self::start();
        return isset($_SESSION[$key]);
    }

    public static function remove(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
    }

    public static function clear(): void
    {
        self::start();
        $_SESSION = [];
    }

    // Flash messages
    public static function flash(string $type, string $message): void
    {
        self::set("_flash.{$type}", $message);
    }

    public static function getFlash(string $type): ?string
    {
        $message = self::get("_flash.{$type}");
        if ($message !== null) {
            self::remove("_flash.{$type}");
        }
        return $message;
    }

    public static function hasFlash(string $type): bool
    {
        return self::get("_flash.{$type}") !== null;
    }

    // Regenerate session after login
    public static function regenerate(): void
    {
        self::start();
        session_regenerate_id(true);
    }

    public static function destroy(): void
    {
        self::start();
        session_destroy();
        self::$started = false;
    }
}
