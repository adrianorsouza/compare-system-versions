#! /usr/bin/env php

<?php

// is installed via composer?
if (file_exists($f = __DIR__ . '../../vendor/autoload.php')) {
    require_once $f;
} else {
    require_once __DIR__ . '/vendor/autoload.php';
}

use Adrianorosa\Csv\Console\Application;

$app = new Application();

$app->run();
