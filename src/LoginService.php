<?php

namespace Spielwiese\Manager;

final class LoginService
{
    public static function isLoggedIn(): bool
    {
        return $_SESSION['is_logged_in'] ?? false;
    }

    /**
     * @return bool|null NULL if auth.sha256file does not exist
     */
    public static function logIn(string $password): ?bool
    {
        $hashFile = __DIR__ . '/../auth.sha256';
        if (!file_exists($hashFile)) {
            return null;
        }

        return $_SESSION['is_logged_in'] = hash('sha256', $password) === file_get_contents($hashFile);
    }

    public static function logOut(): void
    {
        $_SESSION['is_logged_in'] = false;
    }
}
