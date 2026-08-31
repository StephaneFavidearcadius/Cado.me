<?php

namespace App\Core;

class Request
{
    private array $get;
    private array $post;
    private array $server;
    private array $files;
    private array $cookies;
    private ?string $body = null;

    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
        $this->files = $_FILES;
        $this->cookies = $_COOKIE;
    }

    public function method(): string
    {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }

    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    public function isGet(): bool
    {
        return $this->method() === 'GET';
    }

    public function uri(): string
    {
        $uri = $this->server['REQUEST_URI'] ?? '/';
        $uri = explode('?', $uri)[0];

        // Calculer le chemin de base depuis SCRIPT_NAME
        $scriptName = $this->server['SCRIPT_NAME'] ?? '';
        $basePath = rtrim(dirname($scriptName), '/');

        if ($basePath !== '' && str_starts_with($uri, $basePath)) {
            $uri = substr($uri, strlen($basePath));
        }

        return rtrim($uri, '/') ?: '/';
    }

    public function path(): string
    {
        return $this->uri();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->get[$key] ?? $default;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        // POST body
        $body = $this->getBody();
        $data = json_decode($body, true) ?? $this->post;
        return $data[$key] ?? $default;
    }

    public function all(): array
    {
        $body = $this->getBody();
        $data = json_decode($body, true) ?? $this->post;
        return array_merge($this->get, $data);
    }

    public function only(array $keys): array
    {
        $all = $this->all();
        return array_intersect_key($all, array_flip($keys));
    }

    public function file(string $key): ?array
    {
        return $this->files[$key] ?? null;
    }

    public function header(string $name, ?string $default = null): ?string
    {
        $key = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
        return $this->server[$key] ?? $default;
    }

    public function isAjax(): bool
    {
        return $this->header('X-Requested-With') === 'XMLHttpRequest'
            || str_contains($this->header('Accept', ''), 'application/json');
    }

    public function ip(): string
    {
        return $this->server['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    private function getBody(): string
    {
        if ($this->body === null) {
            // Ne pas lire php://input pour les requêtes multipart/form-data
            // (uploads de fichiers) car PHP a déjà parsé le body dans $_POST.
            // Lire le body brut doublerait la consommation mémoire.
            $contentType = $this->server['CONTENT_TYPE'] ?? '';
            if (str_starts_with($contentType, 'multipart/form-data')) {
                $this->body = '';
            } else {
                $this->body = file_get_contents('php://input') ?? '';
            }
        }
        return $this->body;
    }
}
