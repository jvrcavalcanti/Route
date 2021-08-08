<?php

use Accolon\Route\Dispatcher;
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

$route = new RouteCollection();
$route->get('/', fn() => '/');
$route->get('/user/{id:number}', fn() => '/user/{id:number}');
$route->group(prefix: 'api', callback: function (RouteCollection $router) {
    $router->post('/', fn() => '/api/');
});
// dd($route);
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['REQUEST_METHOD'] = 'GET';
var_dump((new Dispatcher($route))->dispatch(request()));
