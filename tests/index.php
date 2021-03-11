<?php

use Accolon\Route\App;
use Accolon\Route\Router;
use Accolon\Route\Controller;
use Accolon\Route\Middlewares\Cors;
use Accolon\Route\Request;
use Accolon\Route\Response;

require_once "../vendor/autoload.php";

function dd($var)
{
    ?>
    <pre>
    <?php
    var_dump($var);
    exit;
}

class User
{
    //
}

class UserController extends Controller
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function show(Request $request)
    {
        $this->validate([
            'id' => 'int'
        ]);

        return response()->text("id: {$request->get('id')}");
    }
}

$app = new App();

$app->get('/', fn() => 'oi');

$app->middleware(Cors::class);

$router = new Router();

$router->pushPrefix('/api');

$router->pushPrefix('/user');

$router->popPrefix();

$router->get('/{id}', [UserController::class, 'show']);

$app->router($router);

// dd($app->getRoutes());

$app->dispatch();