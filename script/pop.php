#!/usr/bin/php
<?php

require_once __DIR__ . '/../vendor/Popcorn/src/Pop/Pop.php';

try {
    $pop = new Pop\Pop();
    $pop->cli($argv);
} catch (Exception $e) {
    echo PHP_EOL . $e->getMessage() . PHP_EOL;
    echo 'Run \'./pop help\' for help.'. PHP_EOL . PHP_EOL;
}
