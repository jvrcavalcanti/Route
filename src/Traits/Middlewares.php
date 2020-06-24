<?php

namespace Accolon\Route\Traits;

use Accolon\Route\Middleware;
use Accolon\Route\Request;
use Accolon\Route\Response;
use Accolon\Route\Router;
use Closure;
use SplDoublyLinkedList;
use SplStack;

trait Middlewares
{
    /** @var SplStack $stack */
    private $stack;

    public function useArray(array $middlewares): Router
    {
        foreach($middlewares as $middleware) {
            if ($middleware instanceof Middleware) {
                $this->use(Closure::fromCallable([$middleware, "handle"]));
            }
            if ($middleware instanceof Closure) {
                $this->use(Closure::fromCallable($middleware));
            }
        }

        return $this;
    }

    public function use(Closure $middleware): Router
    {
        $this->startMiddlewareStack();
        $next = $this->stack->top();

        $this->stack[] = function(Request $request, Response $response) use ($middleware, $next) {
            $result = $middleware($request, $response, $next);
            if ($result instanceof Response === false) {
                throw new UnexpectedValueException(
                    'Middleware must return instance of \Accolon\Route\Response'
                );
            }

            return $result;    
        };

        return $this;
    }

    public function startMiddlewareStack()
    {
        if (!is_null($this->stack)) {
            return;
        }

        $this->stack = new SplStack();
        $this->stack->setIteratorMode(
            SplDoublyLinkedList::IT_MODE_LIFO | SplDoublyLinkedList::IT_MODE_KEEP
        );

        /** @var Router $this */
        $this->stack[] = $this;
    }

    public function runMiddlewares(Request $request, Response $response)
    {
        $this->startMiddlewareStack();
        $start = $this->stack->top();
        $response = $start($request, $response);
        return $response;
    }
}