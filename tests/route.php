<?php

use Accolon\Route\Dispatcher;
use Accolon\Route\Router;
use Accolon\Route\Routes\RouteCollection;

require './vendor/autoload.php';

function dd($var)
{
    ?>
    <pre>
    <?php
    var_dump($var);
    exit;
}

$router = new Router();
$router->get('/', fn() => '/');
$router->get('/user/{id:number}/{sla}/', fn() => '/user/{id:number}');
$router->group(prefix: 'api', callback: function (RouteCollection $router) {
    $router->post('/', fn() => '/api/');
});
$_SERVER['REQUEST_URI'] = '/api';
$_SERVER['REQUEST_METHOD'] = 'POST';
echo $router->dispatch() . PHP_EOL;
