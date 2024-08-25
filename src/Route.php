<?php

namespace Spielwiese\Manager;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Spielwiese\Manager\Routing\RouterFactory;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

final class Route
{
    private static Environment $twig;

    private static function getTwig(): Environment
    {
        if (!isset(self::$twig)) {
            self::$twig = new Environment(
                new FilesystemLoader(__DIR__ . '/../views'),
            );
        }

        return self::$twig;
    }

    public static function get(string $uri, callable $controller): void
    {
        RouterFactory::getRouter()->register('GET', $uri, $controller);
    }

    public static function post(string $uri, callable $controller): void
    {
        RouterFactory::getRouter()->register('POST', $uri, $controller);
    }

    public static function response(string $data, int $status = 200, array $headers = []): ResponseInterface
    {
        return new Response($status, $headers, $data);
    }

    public static function json(array $data, int $status = 200): ResponseInterface
    {
        return self::response(json_encode($data), $status, [
            'Content-Type' => 'application/json',
        ]);
    }

    public static function render(string $template, array $arguments = [], int $status = 200): ResponseInterface
    {
        try {
            return self::response(
                self::getTwig()->render($template, $arguments),
                $status,
                [
                    'Content-Type' => 'text/html',
                ],
            );
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
            die($e);
        }
    }
}
