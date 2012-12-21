<?php
/**
 * Popcorn Micro-Framework Unit Tests
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.TXT.
 * It is also available through the world-wide-web at this URL:
 * http://www.popphp.org/LICENSE.TXT
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@popphp.org so we can send you a copy immediately.
 *
 */

namespace PopcornTest;


// Require the library's autoloader.
require_once __DIR__ . '/../../src/Pop/Pop.php';

// Set test SERVER data
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/hello/nick';

class PopcornTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructor()
    {
        $pop = new \Pop\Pop();
        $this->assertInstanceOf('Pop\Pop', $pop);
    }

    public function testRegister()
    {
        $pop = new \Pop\Pop();
        $pop->register('MyApp', __DIR__);
        $this->assertInstanceOf('Pop\Pop', $pop);
    }

    public function testConfig()
    {
        $pop = new \Pop\Pop();
        $pop->setConfig(array('someValue' => 123));
        $this->assertEquals(123, $pop->config()->someValue);
    }

    public function testRequest()
    {
        $pop = new \Pop\Pop();
        $pop->setRequest(new \Pop\Http\Request());
        $this->assertInstanceOf('Pop\Http\Request', $pop->getRequest());
    }

    public function testResponse()
    {
        $pop = new \Pop\Pop();
        $pop->setResponse(new \Pop\Http\Response());
        $this->assertInstanceOf('Pop\Http\Response', $pop->getResponse());
    }

    public function testView()
    {
        $pop = new \Pop\Pop();
        $pop->setView(new \Pop\Mvc\View());
        $this->assertInstanceOf('Pop\Mvc\View', $pop->getView());
    }

    public function testViewPath()
    {
        $pop = new \Pop\Pop();
        $pop->setViewPath(__DIR__);
        $this->assertEquals(realpath(__DIR__), $pop->getViewPath());
    }

    public function testEvent()
    {
        $func = function() { echo 123; };
        $pop = new \Pop\Pop();
        $pop->attachEvent('route.pre', $func, 2);
        $this->assertEquals(1, count($pop->getEventManager()->get('route.pre')));
        $pop->detachEvent('route.pre', $func);
        $this->assertEquals(0, count($pop->getEventManager()->get('route.pre')));
    }

    public function testGetRoute()
    {
        $pop = new \Pop\Pop();
        $pop->get('/hello/:name', function($name) { echo $name; });
        $this->assertEquals(1, count($pop->getRoutes('get')));
    }

    public function testPostRoute()
    {
        $pop = new \Pop\Pop();
        $pop->post('/hello/:name', function($name) { echo $name; });
        $this->assertEquals(1, count($pop->getRoutes('post')));
    }

    public function testPutRoute()
    {
        $pop = new \Pop\Pop();
        $pop->put('/hello/:name', function($name) { echo $name; });
        $this->assertEquals(1, count($pop->getRoutes('put')));
    }

    public function testDeleteRoute()
    {
        $pop = new \Pop\Pop();
        $pop->delete('/hello/:name', function($name) { echo $name; });
        $this->assertEquals(1, count($pop->getRoutes('delete')));
    }

    public function testOptionsRoute()
    {
        $pop = new \Pop\Pop();
        $pop->options('/hello/:name', function($name) { echo $name; });
        $this->assertEquals(1, count($pop->getRoutes('options')));
    }

    public function testHeadRoute()
    {
        $pop = new \Pop\Pop();
        $pop->head('/hello/:name', function($name) { echo $name; });
        $this->assertEquals(1, count($pop->getRoutes('head')));
    }

    public function testErrorRoute()
    {
        $pop = new \Pop\Pop();
        $pop->error(function() { echo 'Error'; });
        $this->assertEquals(1, count($pop->getRoutes('error')));
    }

    public function testGetRoutes()
    {
        $pop = new \Pop\Pop();
        $pop->route('get,post', '/hello/:name', function($name) { echo $name; });
        $routes = $pop->getRoutes();
        $this->assertEquals(1, count($routes['get']));
        $this->assertEquals(1, count($routes['post']));
    }

    public function testMultipleRoutes()
    {
        $pop = new \Pop\Pop();
        $pop->route('get,post,error', '/hello/:name', function($name) { echo $name; });
        $this->assertEquals(1, count($pop->getRoutes('get')));
        $this->assertEquals(1, count($pop->getRoutes('post')));
    }

    public function testMultipleRoutesException()
    {
        $this->setExpectedException('Pop\Exception');
        $pop = new \Pop\Pop();
        $pop->route('get,badmethod', '/hello/:name', function($name) { echo $name; });
    }

    public function testMultipleRoutesErrorException()
    {
        $this->setExpectedException('Pop\Exception');
        $pop = new \Pop\Pop();
        $pop->route('error,get', null, function($name) { echo $name; });
    }

    public function testGetResult()
    {
        $pop = new \Pop\Pop();
        $this->assertNull($pop->getResult());
    }

    public function testPostMethod()
    {
        global $_SERVER;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $pop = new \Pop\Pop();
        $pop->post('/hello/:name', function($name) { return ucfirst($name); });
        $pop->run();
        $this->assertEquals(200, $pop->getResponse()->getCode());
    }

    public function testPutMethod()
    {
        global $_SERVER;
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $pop = new \Pop\Pop();
        $pop->put('/hello/:name', function($name) { return ucfirst($name); });
        $pop->run();
        $this->assertEquals(200, $pop->getResponse()->getCode());
    }

    public function testDeleteMethod()
    {
        global $_SERVER;
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $pop = new \Pop\Pop();
        $pop->delete('/hello/:name', function($name) { return ucfirst($name); });
        $pop->run();
        $this->assertEquals(200, $pop->getResponse()->getCode());
    }

    public function testOptionsMethod()
    {
        global $_SERVER;
        $_SERVER['REQUEST_METHOD'] = 'OPTIONS';
        $pop = new \Pop\Pop();
        $pop->options('/hello/:name', function($name) { return ucfirst($name); });
        $pop->run();
        $this->assertEquals(200, $pop->getResponse()->getCode());
    }

    public function testHeadMethod()
    {
        global $_SERVER;
        $_SERVER['REQUEST_METHOD'] = 'HEAD';
        $pop = new \Pop\Pop();
        $pop->head('/hello/:name', function($name) { return ucfirst($name); });
        $pop->run();
        $this->assertEquals(200, $pop->getResponse()->getCode());
    }

    public function testRun()
    {
        global $_SERVER;
        $pop = new \Pop\Pop();
        $pop->get('/hello/:name', function($name) { return ucfirst($name); });
        $pop->run();
        $this->assertEquals('Nick', $pop->getResult());
    }

    public function testWildcard()
    {
        global $_SERVER;
        $_SERVER['REQUEST_URI'] = '/hello/john/t/doe';
        $pop = new \Pop\Pop();
        $pop->get('/hello/:name*', function($name) { return ($name); });
        $pop->run();
        $this->assertEquals(3, count($pop->getResult()));
    }

    public function testRunError()
    {
        global $_SERVER;
        $pop = new \Pop\Pop();
        $pop->get('/', function() { return 'Hello World'; });
        $pop->error(function() { return 'Error'; });
        $pop->run();
        $this->assertEquals('Error', $pop->getResult());
    }

    public function testRunErrorException()
    {
        $this->setExpectedException('Pop\Exception');
        global $_SERVER;
        $pop = new \Pop\Pop();
        $pop->get('/', function() { return 'Hello World'; });
        $pop->run();
    }

}
