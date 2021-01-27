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

$router->prefix('api');

$router->get('/', fn() => response()->json(['message' => 'Welcome']));

// dd($router->getRoutes());

$router->dispatch();
