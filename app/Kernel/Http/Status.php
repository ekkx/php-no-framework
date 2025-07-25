<?php

declare(strict_types=1);

namespace App\Kernel\Http;

class Status
{
    public const int OK = 200;
    public const int CREATED = 201;
    public const int NO_CONTENT = 204;
    public const int MOVED_PERMANENTLY = 301;
    public const int TEMPORARY_REDIRECT = 307;
    public const int BAD_REQUEST = 400;
    public const int UNAUTHORIZED = 401;
    public const int FORBIDDEN = 403;
    public const int NOT_FOUND = 404;
    public const int METHOD_NOT_ALLOWED = 405;
    public const int TOO_MANY_REQUESTS = 429;
    public const int INTERNAL_SERVER_ERROR = 500;
}
