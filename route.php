<?php

use Spielwiese\Manager\Routing\RouterFactory;

require __DIR__ . '/vendor/autoload.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Buffering to prevent var_dump() from breaking headers
ob_start();
echo RouterFactory::getRouter()
    ->route($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
echo ob_get_clean();
