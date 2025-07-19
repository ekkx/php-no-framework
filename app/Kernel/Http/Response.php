<?php

declare(strict_types=1);

namespace App\Kernel\Http;

use App\Kernel\RendererInterface;
use App\Kernel\ResponseWriter;
use App\Kernel\Support\JSON;

class Response
{
    private ?RendererInterface $renderer;
    private int $status = Status::OK;
    private array $headers = ["Content-Type" => ContentType::TEXT_HTML];
    private ?string $content = null;
    private ?string $redirectUri = null;

    public function __construct(?RendererInterface $renderer = null)
    {
        $this->renderer = $renderer;
    }

    public function getRenderer(): ?RendererInterface
    {
        return $this->renderer;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getHeaders(?string $key = null, mixed $default = ""): mixed
    {
        return $key === null ? $this->headers : ($this->headers[$key] ?? $default);
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getRedirectUri(): ?string
    {
        return $this->redirectUri;
    }

    public function withStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function withHeaders(array $headers): self
    {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }

    public function withContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function redirect(string $url, $status = 302): ResponseWriter
    {
        $this->redirectUri = $url;
        $this->withStatus($status);
        $this->withHeaders(["Location" => $url]);
        return new ResponseWriter($this);
    }

    public function json(array $data): ResponseWriter
    {
        $this->withHeaders(["Content-Type" => ContentType::APPLICATION_JSON]);
        $this->withContent(JSON::encode($data));
        return new ResponseWriter($this);
    }

    public function render(string $view, array $data = []): ResponseWriter
    {
        if (is_null($this->renderer)) {
            $this->withStatus(Status::INTERNAL_SERVER_ERROR);
            return $this->json([
                "error" => [
                    "status" => Status::INTERNAL_SERVER_ERROR,
                    "message" => "No renderer configured",
                ]
            ]);;
        }
        $content = $this->renderer->render($view, $data);
        $this->withContent($content);
        $this->withHeaders(["Content-Type" => ContentType::TEXT_HTML]);
        return new ResponseWriter($this);
    }
}
