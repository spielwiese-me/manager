<?php

namespace Spielwiese\Manager\Container;

final class ContainerService
{
    private static array $configuration;

    private static function getConfiguration(): array
    {
        if (!isset(self::$configuration)) {
            self::$configuration = json_decode(file_get_contents(__DIR__ . '/../../managed.json'), true);
        }

        return self::$configuration;
    }

    private static function runShell(string $command, ...$args): string
    {
        return shell_exec(sprintf($command . ' 2>&1', ...$args)) ?? '';
    }

    private static function runShellInDirectory(string $command, string $directory, ...$args): string
    {
        return self::runShell('(cd %s ; ' . $command . ')', escapeshellarg($directory), ...$args);
    }

    private static function runCompose(string $command, string $path): string
    {
        return self::runShellInDirectory(
            'docker compose %s',
            $path,
            $command,
        );
    }

    public static function getManaged(): array
    {
        $managed = [];
        foreach (self::getConfiguration() as $name => $path) {
            if (!str_starts_with($path, DIRECTORY_SEPARATOR)) {
                $path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $path;
            }

            $managed[] = new Managed(
                $name,
                $path,
                !(trim(self::runCompose('ps -q', $path)) === ''),
                !str_contains(self::runShellInDirectory('git status -uno', $path), 'Your branch is behind'),
            );
        }

        return $managed;
    }

    public static function updateContainer(string $container): void
    {
        if (!isset(self::getConfiguration()[$container])) {
            return;
        }

        self::runShellInDirectory('git pull', self::getConfiguration()[$container]);
    }

    public static function restartContainer(string $container): void
    {
        if (!isset(self::getConfiguration()[$container])) {
            return;
        }

        $path = self::getConfiguration()[$container];
        echo self::runShellInDirectory('ls -la /opt/beispiel/docker/web/default.conf', $path);
        echo self::runCompose('down', $path);
        echo self::runCompose('up -d', $path);
    }
}
