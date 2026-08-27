<?php

namespace App\Core;

class View
{
    private static string $viewsPath = __DIR__ . '/../../resources/views';

    public static function make(string $view, array $data = [], int $statusCode = 200): string
    {
        extract(array_merge($data, [
            'errors' => Session::get('errors', []),
            'old' => Session::get('old', []),
            'flash' => [
                'success' => Session::getFlash('success'),
                'error' => Session::getFlash('error'),
                'warning' => Session::getFlash('warning'),
                'info' => Session::getFlash('info'),
            ],
        ]));

        $viewFile = self::$viewsPath . '/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewFile)) {
            throw new \RuntimeException("Vue non trouvée: {$view}");
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        return $content;
    }

    public static function renderWithLayout(string $layout, string $view, array $data = [], int $statusCode = 200): Response
    {
        $content = self::make($view, $data, $statusCode);
        $html = self::make("layouts.{$layout}", array_merge($data, ['slot' => $content]));

        return Response::html($html, $statusCode);
    }

    public static function component(string $component, array $data = []): string
    {
        return self::make("composants.{$component}", $data);
    }

    /**
     * Helper pour afficher du HTML échappé
     */
    public static function e(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }

    /**
     * Helper pour l'ancienne valeur (old input)
     */
    public static function old(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }
}
