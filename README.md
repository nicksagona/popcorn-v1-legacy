Popcorn Micro-Framework v0.9
============================

OVERVIEW
--------
A REST-based micro-framework built from the Pop PHP Framework.
With it, you can easily map URLs to different routes via the
different HTTP methods, all on the fly.

REQUIREMENTS
------------
The only requirement is PHP 5.3 or greater and a web server
that supports URL-rewrites.

FEATURES
--------
* Routing URLs to functions or class/method combinations
* Support for standard HTTP methods
* Wildcard support
* Event handling
* Basic web support, such as sessions, cookies, mobile detection, etc.
* Support for custom view/template management and rendering
* Autoloading and support for registering other libraries of code.

GETTING STARTED
---------------
You'll need this at the top of your main script:

<pre>
require_once '../vendor/Popcorn/src/Pop/Pop.php';
</pre>

### A Simple Example
$pop = new Pop\Pop();
$pop->get('/hello/:name', function($name) {
    echo 'Hello, ' . ucfirst($name) . '!';
});
$pop->run();
</pre>

### A View/Template Example
<pre>
$pop = new Pop\Pop();

$pop->setViewPath('./view');

$pop->get('/hello/:name', function($name) {
    return new Pop\Mvc\Model(array('name' => $name));
});

// When the app runs, it will map to a PHTML view file and
// push the above model object to that view template, in
// this case, './view/hello.phtml'.
$pop->run();
</pre>

### A Class/Method Example
<pre>
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

$pop = new Pop\Pop();

$pop->setViewPath('./view');
$pop->get('/', 'Foo::factory');
$pop->get('/hello/:name*', array(new Foo(), 'bar'));
$pop->error(array(new Foo(), 'error'));

$pop->run();
</pre>