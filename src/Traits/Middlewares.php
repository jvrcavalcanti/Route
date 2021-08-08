<?php

namespace Accolon\Route\Traits;

use Accolon\Route\MiddlewareInterface;
use Accolon\Route\Request;
use Accolon\Route\Responses\Response;
use Accolon\Route\Router;
use Closure;

trait Middlewares
{
    protected \SplStack $stack;

    public function middleware($middlewares)
    {
        $next = $this->stack->top();

        if (!is_array($middlewares)) {
            $middlewares = [$middlewares];
        }

        foreach ($middlewares as $middleware) {
            if (is_string($middleware)) {
                $middleware = resolve($middleware);
            }
    
            if ($middleware instanceof MiddlewareInterface) {
                $middleware = Closure::fromCallable([$middleware, 'handle']);
            }
    
            $this->stack[] = fn(
                Request $request
            ) => $middleware($request, $next);
        }

        return $this;
    }

    public function startMiddlewareStack()
    {
        $this->stack = new \SplStack();
        $this->stack->setIteratorMode(
            \SplDoublyLinkedList::IT_MODE_LIFO | \SplDoublyLinkedList::IT_MODE_KEEP
        );

        /** @var Router $this */
        $this->stack[] = $this;
    }

    public function runMiddlewares(Request $request): Response
    {
        $start = $this->stack->top();
        $response = $start($request);
        if (!$response instanceof Response) {
            return is_array($response) || is_object($response)
            ? response()->json($response)
            : response()->text($response);
        }
        return $response;
    }
}
