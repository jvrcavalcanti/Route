<?php

use Accolon\Route\App;
use Accolon\Route\Controller as RouteController;
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

class Controller extends RouteController
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

        return response()->text('success');
    }
}

$router = new App();

$router->middleware(Cors::class);

$router->get('/user/{id}', [Controller::class, 'show']);

$router->dispatch();