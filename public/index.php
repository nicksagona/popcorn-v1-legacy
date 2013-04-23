<?php

require_once __DIR__ . '/../vendor/Popcorn/src/Pop/Pop.php';

try {
    $pop = new Pop\Pop();

    // Set the URI mapping to strict
    //$pop->setStrict(true);

    /**
     * Basic routing using closures
     */
    $pop->get('/', function() { echo 'Hello, World!' . PHP_EOL; });

    // Direct variable mapping example, map values to variables $fname, $lname
    $pop->get('/hello/:fname/:lname', function($fname, $lname) {
        echo 'Hello, ' . ucfirst($fname) . ' ' . ucfirst($lname) . '!' . PHP_EOL;
    });

    // Associative array example, map to an associative array with keys 'name' and 'id'
    $pop->get('/user/:name/:id#', function($user) {
        print_r($user);
    });

    // Wildcard example, returns numeric array of URI segments
    $pop->get('/list/:name*', function($user) {
        print_r($user);
    });

    // POST example
    $pop->post('/edit/:id', function($id) {
        echo 'You are trying to edit user #' . $id . PHP_EOL;
    });

    // Error (not found) example
    $pop->error(function() {
        echo '404 Error: Page Not Found!' . PHP_EOL;
    });

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
