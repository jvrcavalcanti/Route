<?php

namespace Accolon\Route\Traits;

use Accolon\Route\IMiddleware;
use Accolon\Route\Request;
use Accolon\Route\Router;
use Closure;

trait Middlewares
{
    private \SplStack $stack;

    public function use($middlewares)
    {
        $next = $this->stack->top();

        if (!is_array($middlewares)) {
            $middlewares = [$middlewares];
        }

        foreach ($middlewares as $middleware) {
            if (is_string($middleware)) {
                $middleware = new $middleware;
            }
    
            if ($middleware instanceof IMiddleware) {
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

    public function runMiddlewares(Request $request)
    {
        $start = $this->stack->top();
        return $start($request);
    }
}
