<?php

require_once '../vendor/Popcorn/src/Pop/Pop.php';

class Foo
{
    public static function factory()
    {
        return new Pop\Mvc\Model(array('title' => 'Hello, World!'));
    }

    public function bar($name)
    {
        return new Pop\Mvc\Model(array('name' => $name));
    }

    public function error()
    {
        return new Pop\Mvc\Model(array('error' => '404 Error: Page Not Found!'));
    }
}

try {
    $pop = new Pop\Pop();

    /**
     * Basic routing based on class/method calls
     */
    $pop->setViewPath('./view');
    $pop->get('/', 'Foo::factory');
    $pop->get('/hello/:name*', array(new Foo(), 'bar'));
    $pop->error(array(new Foo(), 'error'));

    // Run the app
    $pop->run();
} catch (Exception $e) {
    echo $e->getMessage();
}


