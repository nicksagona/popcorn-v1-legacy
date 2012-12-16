<?php

require_once '../vendor/PopPHPFramework/src/Pop/Pop.php';

$pop = new Pop\Pop();

$pop->get('/', function() { echo 'Hello, World!' . PHP_EOL; });
$pop->get('/hello/:name', function($name) { echo 'Hello, ' . ucfirst($name) . '!' . PHP_EOL; });
$pop->post('/user/:id', function($id) { echo 'You are trying to edit user #' . $id . PHP_EOL; });
$pop->error(function() { echo 'Error'; });

$pop->run();


