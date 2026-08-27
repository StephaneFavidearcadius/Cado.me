<?php

namespace App\Core;

abstract class Controller
{
    protected function view(string $view, array $data = [], int $statusCode = 200): Response
    {
        return View::renderWithLayout('main', $view, $data, $statusCode);
    }

    protected function viewAuth(string $view, array $data = [], int $statusCode = 200): Response
    {
        return View::renderWithLayout('auth', "auth.{$view}", $data, $statusCode);
    }

    protected function viewAdmin(string $view, array $data = [], int $statusCode = 200): Response
    {
        return View::renderWithLayout('admin', $view, $data, $statusCode);
    }

    protected function redirect(string $url): Response
    {
        return Response::redirect($url);
    }

    protected function json(mixed $data, int $statusCode = 200): Response
    {
        return Response::json($data, $statusCode);
    }

    protected function back(): Response
    {
        return Response::back();
    }

    protected function withSuccess(string $message): Response
    {
        Session::flash('success', $message);
        return $this->back();
    }

    protected function withError(string $message): Response
    {
        Session::flash('error', $message);
        return $this->back();
    }

    protected function authUserId(): ?int
    {
        return Session::get('utilisateur_id');
    }

    protected function communauteCourante(): ?array
    {
        return Session::get('communaute_courante');
    }
}
