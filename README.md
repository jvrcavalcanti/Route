```php
<?php

use Accolon\Route\Middlewares\Cors;
use Accolon\Route\Request;
use Accolon\Route\Response;
use Accolon\Route\Router;

require_once "../vendor/autoload.php";

class User
{
    //
}

class Controller
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function show(\stdClass $class)
    {
        dd($class);
    }
}

$router = new Router();

$router->add(Cors::class);

$router->get('/user/{id}', [Controller::class, 'show']);

$router->dispatch();
```