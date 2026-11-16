<?php

abstract class BaseController
{
    /**
     * Rend une vue avec les variables passées en paramètres
     */
    protected function render(string $view, array $data = []): void
    {
        extract($data);
        $viewFile = __DIR__ . '/../view/' . $view . '.php';

        if (!file_exists($viewFile)) {
            throw new RuntimeException("Vue introuvable : {$viewFile}");
        }

        require $viewFile;
    }

    /**
     * Redirige vers une URL relative ou absolue
     */
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit();
    }

    /**
     * Stocke un message flash en session
     */
    protected function setFlash(string $type, string $message): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['flash'][$type] = $message;
    }

    /**
     * Récupère et supprime un message flash
     */
    protected function getFlash(string $type): ?string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['flash'][$type])) {
            $msg = $_SESSION['flash'][$type];
            unset($_SESSION['flash'][$type]);
            return $msg;
        }
        return null;
    }
}
