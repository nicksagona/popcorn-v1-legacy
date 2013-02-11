Popcorn Micro-Framework v1.0.2
==============================

RELEASE INFORMATION
-------------------
Popcorn Micro-Framework 1.0.2 Release  
Released February 11, 2013

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
* Service management
* Basic web support, such as sessions, cookies, mobile detection, etc.
* Support for custom view/template management and rendering
* Autoloading and support for registering other libraries of code
* Package manager for installing or removing compatible components from the Pop PHP Framework
    - requires the TAR or ZIP programs to be installed

DOCUMENTATION
-------------
* [API Documentation](http://popcorn.popphp.org/docs/api/)
* [Unit Test Code Coverage](http://popcorn.popphp.org/docs/cc/)

GETTING STARTED
---------------
You'll need this at the top of your main script:

<pre>
require_once '../vendor/Popcorn/src/Pop/Pop.php';
</pre>

### A Simple Example
<pre>
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
$pop->get('/hello/:name*', 'Foo->bar');
$pop->error(array(new Foo(), 'error'));

$pop->run();
</pre>

### A Wildcard Example
<pre>
$pop = new Pop\Pop();

// The variable $name is populated with an array of values
// from the URI, such as: /hello/john/t/doe
$pop->get('/hello/:name*', function($name) {
    // Dumps array('john', 't', 'doe')
    print_r($name);
});

$pop->run();
</pre>

### A Multiple Routes Example
<pre>
$func = function($id) {
    // Some function that handles GET, POST, etc.
}

$pop = new Pop\Pop();
$pop->route('get,post', '/user/:id', $func($id));

$pop->run();
</pre>

USING THE PACKAGE MANAGER
-------------------------

From the command line, you can easily install or remove
compatible components from the Pop PHP Framework

### Via Linux/Unix Using the Bash Script

<pre>
// Display help
~/Popcorn/script$ ./pop help

// List available components
~/Popcorn/script$ ./pop list

// Install some components
~/Popcorn/script$ ./pop install Db Form

// Remove some components
~/Popcorn/script$ ./pop remove Auth Image
</pre>

### Via Windows Using the Batch Script

<pre>
// Display help
C:\Popcorn\script>pop help

// List available components
C:\Popcorn\script>pop list

// Install some components
C:\Popcorn\script>pop install Db Form

// Remove some components
C:\Popcorn\script>pop remove Auth Image
</pre>
