<?php

use Accolon\Route\Router;

require_once "../vendor/autoload.php";

$router = new Router();

$router->get('/', fn() => 'oi');

$router->dispatch();
