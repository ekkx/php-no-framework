<?php

declare(strict_types=1);

namespace App\Kernel\Support;

use stdClass;

class JSON
{
    public static function encode(array|stdClass $json): string
    {
        return json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?? "";
    }

    public static function decode(string $json, bool $associative = true): array|stdClass
    {
        return json_decode($json, $associative) ?? [];
    }
}
