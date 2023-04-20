<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/debug.php';
require_once __DIR__ . '/../config/config.php';

use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();

$framework = new App\Framework();

$framework->handle($request)->send();
