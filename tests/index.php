<?php

use Accolon\Route\App;
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

$app = new App();

$app->get('/', fn() => 'oi');

$app->middleware(Cors::class);

$app->attributeRoutes('./Controllers', 'Tests\\Controllers');

// dd($app->getRoutes());

$app->dispatch();