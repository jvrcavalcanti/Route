<?php

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
$route->get('/', fn() => 42);
$route->group(prefix: 'api', callback: function (RouteCollection $router) {
    $router->post('/', fn() => 42);
});
dd($route->getRoutes());
