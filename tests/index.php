<?php

use Accolon\Route\App;

require_once "../vendor/autoload.php";

function dd($var)
{
    ?>
    <pre>
    <?php
    var_dump($var);
    exit;
}

$app = new App();

$routerApi = $app->newRouter();

$routerApi->prefix('api');

$routerApi->post('/', fn() => 'Welcome Api');

$app->router($routerApi);

$app->get('/', fn() => 'oi');

$app->prefix('/user');

$app->get('/', fn() => response()->json(['message' => 'Welcome']));

// dd($app->getRoutes());

$app->dispatch();
