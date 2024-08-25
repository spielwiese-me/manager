<?php

namespace Spielwiese\Manager\Container;

final readonly class Managed
{
    public function __construct(
        public string $name,
        public string $path,
        public bool $running,
        public bool $latest,
    ) {
    }
}
