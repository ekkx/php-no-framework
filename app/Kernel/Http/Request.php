<?php

declare(strict_types=1);

namespace App\Kernel\Http;

use App\Kernel\Support\JSON;

class Request
{
    private string $method;
    private string $uri;
    private array $params = [];
    private array $query;
    private array $body;
    private array $headers;
    private array $cookies;
    private array $files;
    private string $ip;

    public function __construct(
        array $_get,
        array $_post,
        array $_cookies,
        array $_files,
        array $_server,
    )
    {
        $this->method = $_server["REQUEST_METHOD"];
        $this->uri = $_server["REQUEST_URI"];
        $this->query = $_get;
        $this->headers = getallheaders();
        $this->cookies = $_cookies;
        $this->files = $_files;
        $this->ip = $_server["REMOTE_ADDR"];

        $json = JSON::decode(file_get_contents("php://input")) ?? [];
        $this->body = empty($_post) ? $json : $_post;
    }

    public static function fromGlobals(): self
    {
        return new self($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
    }

    private function extract(array $data, ?string $key = null, mixed $default = null): mixed
    {
        return $key === null ? $data : ($data[$key] ?? $default);
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getPath(): string
    {
        return parse_url($this->uri, PHP_URL_PATH);
    }

    public function getParams(?string $key = null, mixed $default = ""): mixed
    {
        return $this->extract($this->params, $key, $default);
    }

    public function getQuery(?string $key = null, mixed $default = ""): mixed
    {
        return $this->extract($this->query, $key, $default);
    }

    public function getBody(?string $key = null, mixed $default = null): mixed
    {
        return $this->extract($this->body, $key, $default);
    }

    public function getHeaders(?string $key = null, mixed $default = ""): mixed
    {
        return $this->extract($this->headers, $key, $default);
    }

    public function getCookies(?string $key = null, mixed $default = ""): mixed
    {
        return $this->extract($this->cookies, $key, $default);
    }

    public function getFiles(?string $key = null, mixed $default = null): mixed
    {
        return $this->extract($this->files, $key, $default);
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function withMethod(string $method): self
    {
        $this->method = $method;
        return $this;
    }

    public function withUri(string $uri): self
    {
        $this->uri = $uri;
        return $this;
    }

    public function withParams(array $params): self
    {
        $this->params = $params;
        return $this;
    }

    public function withQuery(array $query): self
    {
        $this->query = $query;
        return $this;
    }

    public function withBody(array $body): self
    {
        $this->body = $body;
        return $this;
    }

    public function withHeaders(array $headers): self
    {
        $this->headers = $headers;
        return $this;
    }
}
