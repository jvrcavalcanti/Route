<?php

use Accolon\Route\Router;

require_once "../vendor/autoload.php";

function dd($var)
{
    ?>
    <pre>
    <?php
    var_dump($var);
    exit;
}

$router = new Router();

$router->get('/', fn() => 'oi');

$router->prefix('/api');

$router->addPrefix('/user');

// $router->get('/', fn() => response()->json(['message' => 'Welcome']));

$router->get('/', function () {
    throw new \InvalidArgumentException('oi');
});

// dd($router->getRoutes());

$router->dispatch();
