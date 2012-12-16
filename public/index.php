<?php

require_once '../vendor/Popcorn/src/Pop/Pop.php';

try {
    $pop = new Pop\Pop();

    //$pop->get('/', function() { echo 'Hello, World!' . PHP_EOL; });
    //$pop->get('/hello/:name', function($name) { echo 'Hello, ' . ucfirst($name) . '!' . PHP_EOL; });
    //$pop->get('/hello/:name*', function($name) {
    //    echo 'Hello,';
    //    if (is_array($name)) {
    //        foreach ($name as $value) {
    //            echo ' ' . ucfirst($value);
    //        }
    //    } else {
    //        echo ' ' . ucfirst($name);
    //    }
    //    echo '!' . PHP_EOL;
    //});
    //$pop->post('/user/:id', function($id) { echo 'You are trying to edit user #' . $id . PHP_EOL; });
    //$pop->error(function() { echo '404 Error: Page Not Found!'; });

    $pop->setViewPath('./view');
    $pop->get('/', function() { return new Pop\Mvc\Model(array('title' => 'Hello, World!')); });
    $pop->get('/hello/:name*', function($name) { return new Pop\Mvc\Model(array('name' => $name)); });
    $pop->error(function() { return new Pop\Mvc\Model(array('error' => '404 Error: Page Not Found!')); });

    $pop->run();
} catch (Exception $e) {
    echo $e->getMessage();
}


