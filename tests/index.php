<?php

use Accolon\Route\Router;
use Accolon\Route\Middlewares\Cors;

require_once "../vendor/autoload.php";

function load_files($path)
{
    $files = scandir($path);
    $files = array_splice($files, 2);
    $files = array_filter($files, fn($file) => str_ends_with($file, '.php'));

    foreach ($files as $file) {
        $tmpPath = $path . '/' . $file;
        if (is_dir($tmpPath)) {
            load_files($tmpPath);
            continue;
        }

        require_once($tmpPath);
    }
}

function dd($var)
{
    ?>
    <pre>
    <?php
    var_dump($var);
    exit;
}

load_files('./Controllers');
load_files('./Models');

$router = new Router();
$router->get('/', fn() => '/');
$router->get('/user/{id:number}/{sla}/', fn() => '/user/{id:number}');
// $router->group(prefix: 'api', callback: function (RouteCollection $router) {
//     $router->post('/', fn() => '/api/');
// });

$router->dispatch();
