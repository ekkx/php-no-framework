<?php

declare(strict_types=1);

namespace App\Kernel;

interface RendererInterface
{
    public function getEngine(): mixed;

    public function render(string $template, array $data = []): string;
}
