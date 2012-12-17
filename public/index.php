<?php

require_once '../vendor/Popcorn/src/Pop/Pop.php';

try {
    $pop = new Pop\Pop();

    /**
     * Basic routing based on closures
     */
    $pop->get('/', function() { echo 'Hello, World!' . PHP_EOL; });
    $pop->get('/hello/:name', function($name) { echo 'Hello, ' . ucfirst($name) . '!' . PHP_EOL; });
    $pop->post('/user/:id', function($id) { echo 'You are trying to edit user #' . $id . PHP_EOL; });
    $pop->error(function() { echo '404 Error: Page Not Found!'; });

    /**
     * Basic routing based on closures returning model objects to be used with view templates
     */
    //$pop->setViewPath('./view');
    //$pop->get('/', function() { return new Pop\Mvc\Model(array('title' => 'Hello, World!')); });
    //$pop->get('/hello/:name*', function($name) { return new Pop\Mvc\Model(array('name' => $name)); });
    //$pop->error(function() { return new Pop\Mvc\Model(array('error' => '404 Error: Page Not Found!')); });

    // Run the app
    $pop->run();
} catch (Exception $e) {
    echo $e->getMessage();
}


