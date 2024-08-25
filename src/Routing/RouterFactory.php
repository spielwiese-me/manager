<?php

namespace Spielwiese\Manager\Routing;

final class RouterFactory
{
    private static Router $router;

    public static function getRouter(): Router
    {
        if (!isset(self::$router)) {
            self::$router = new Router();
            self::$router->setUp(__DIR__ . '/../../routes');
        }

        return self::$router;
    }
}
