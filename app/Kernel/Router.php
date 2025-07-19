<?php

declare(strict_types=1);

namespace App\Kernel;

use App\Kernel\Http\Status;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Throwable;

use function FastRoute\simpleDispatcher;

class Router
{
    private array $routes = [];

    private function add(string $method, string $path, ControllerInterface $controller): void
    {
        if ($path !== '/') {
            $path = rtrim($path, '/');
        }
        $this->routes[] = [$method, $path, $controller];
    }

    public function get(string $path, ControllerInterface $controller): void
    {
        $this->add('GET', $path, $controller);
    }

    public function post(string $path, ControllerInterface $controller): void
    {
        $this->add('POST', $path, $controller);
    }

    public function put(string $path, ControllerInterface $controller): void
    {
        $this->add('PUT', $path, $controller);
    }

    public function delete(string $path, ControllerInterface $controller): void
    {
        $this->add('DELETE', $path, $controller);
    }

    public function dispatch(Context $ctx): void
    {
        try {
            $dispatcher = simpleDispatcher(function (RouteCollector $r) {
                foreach ($this->routes as $route) {
                    [$method, $path, $controller] = $route;
                    $r->addRoute($method, $path, $controller);
                }
            });

            $uri = $ctx->request->getUri();
            if (false !== $pos = strpos($uri, '?')) {
                $uri = substr($uri, 0, $pos);
            }
            $uri = rawurldecode($uri);

            if ($uri !== '/' && substr($uri, -1) === '/') {
                $uri = rtrim($uri, '/');
            }

            $routeInfo = $dispatcher->dispatch(
                $ctx->request->getMethod(),
                $uri
            );
            switch ($routeInfo[0]) {
                case Dispatcher::NOT_FOUND:
                    $ctx->response->withStatus(Status::NOT_FOUND)->json([
                        'error' => [
                            'status' => Status::NOT_FOUND,
                            'message' => 'not found',
                        ],
                    ])->write();
                    break;
                case Dispatcher::METHOD_NOT_ALLOWED:
                    $ctx->response->withStatus(Status::METHOD_NOT_ALLOWED)->json([
                        'error' => [
                            'status' => Status::METHOD_NOT_ALLOWED,
                            'message' => 'method not allowed',
                        ],
                    ])->write();
                    break;
                case Dispatcher::FOUND:
                    /** @var ControllerInterface $controller */
                    $controller = $routeInfo[1];
                    /** @var ResponseWriter $responseWriter */
                    $responseWriter = $controller->handle($ctx);
                    $responseWriter->write();
                    break;
            }
        } catch (Throwable $e) {
            $ctx->response->withStatus(Status::INTERNAL_SERVER_ERROR)->json([
                'error' => [
                    'status' => Status::INTERNAL_SERVER_ERROR,
                    'message' => 'internal server error',
                ],
            ])->write();
        }
    }
}
