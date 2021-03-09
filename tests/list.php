<?php

use Accolon\Route\Utils\MatchList;

require_once './vendor/autoload.php';

$list = new MatchList();

$list['user/[0-9]'] = 'oi';

echo $list['user/1'];
