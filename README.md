Popcorn Micro-Framework v1.2.2
==============================

RELEASE INFORMATION
-------------------
Popcorn Micro-Framework 1.2.2 Release  
Released July 15, 2013

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
* URL segment to parameter mapping 
    - via direct variables
    - via associative array of values
    - via numeric array of values
* Wildcard support
* Error handling
* Event handling
* Service management
* Basic web support, such as sessions, cookies, mobile detection, etc.
* Support for custom view/template management and rendering
* Autoloading and support for registering other libraries of code
* Package manager for installing or removing compatible components from the Pop PHP Framework
    - requires the TAR or ZIP programs to be installed

DOCUMENTATION
-------------
* [Main Website](http://popcorn.popphp.org/)
* [API Documentation](http://popcorn.popphp.org/docs/api/)
* [Unit Test Code Coverage](http://popcorn.popphp.org/docs/cc/)

GETTING STARTED
---------------
You'll need this at the top of your main script:

    require_once '../vendor/Popcorn/src/Pop/Pop.php';

### A Simple Example
    $pop = new Pop\Pop();

    // Direct variable mapping example, map values to variables $fname, $lname
    $pop->get('/hello/:fname/:lname', function($fname, $lname) {
        echo 'Hello, ' . ucfirst($fname) . ' ' . ucfirst($lname) . '!' . PHP_EOL;
    });

    $pop->run();

### An Array Example
    $pop = new Pop\Pop();

    // Associative array example, map to an associative array with keys 'name' and 'id'
    $pop->get('/user/:name/:id#', function($user) {
        // Dumps array('name' => 'John', 'id' => 1001)
        print_r($user);
    });

    $pop->run();


### A View/Template Example
    $pop = new Pop\Pop();

    $pop->setViewPath('./view');

    $pop->get('/hello/:name', function($name) {
        return new Pop\Mvc\Model(array('name' => $name));
    });

    // When the app runs, it will map to a PHTML view file and
    // push the above model object to that view template, in
    // this case, './view/hello.phtml'.
    $pop->run();

### A Class/Method Example

    class Foo
    {
        public static function factory()
        {
            return new Pop\Mvc\Model(array('title' => 'Hello, World!'));
        }

        public function hello($name)
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
    $pop->get('/hello/:name*', 'Foo->hello');
    $pop->error(array(new Foo(), 'error'));

    $pop->run();

##### Auto-Routing

An additional feature is "auto-routing" in which the URL is mapped to the method within the
controller class. So, in the above example, this will work for the URL "/hello" as well:

    $pop = new Pop\Pop();

    // Auto-routes to the "hello()" method in the class "Foo"
    $pop->get('/hello/:name*', 'Foo');

### A Wildcard Example
    $pop = new Pop\Pop();

    // The variable $name is populated with an array of values
    // from the URL, such as: /hello/john/t/doe
    $pop->get('/hello/:name*', function($name) {
        // Dumps array('john', 't', 'doe')
        print_r($name);
    });

    $pop->run();

### A Multiple Routes Example
    $func = function($id) {
        // Some function that handles GET, POST, etc.
    }

    $pop = new Pop\Pop();
    $pop->route('get,post', '/user/:id', $func($id));

    $pop->run();

### Using Strict Mode

Using strict mode simply enforces the correct parameter mapping dervied from the
URL request. That way, it can take some burden off of your application from having
to detect if the correct parameters have been passed.

    $pop = new Pop\Pop();
    $pop->setStrict(true);

    $pop->get('/hello/:fname/:lname', function($fname, $lname) {
        echo 'Hello, ' . ucfirst($fname) . ' ' . ucfirst($lname) . '!' . PHP_EOL;
    });

    // In this case, with strict mode, the only valid URLs to the above route are:
    /hello/john/doe
    /hello/john/doe/

    // Anything else will fail, for example:
    /hello
    /hello/
    /hello/john
    /hello/john/
    /hello/john/doe/extra

USING THE PACKAGE MANAGER
-------------------------

From the command line, you can easily install or remove
compatible components from the Pop PHP Framework

### Via Linux/Unix Using the Bash Script

    // Display help
    ~/Popcorn/script$ ./pop help

    // List available components
    ~/Popcorn/script$ ./pop list

    // Install some components
    ~/Popcorn/script$ ./pop install Db Form

    // Remove some components
    ~/Popcorn/script$ ./pop remove Auth Image


### Via Windows Using the Batch Script

    // Display help
    C:\Popcorn\script>pop help

    // List available components
    C:\Popcorn\script>pop list

    // Install some components
    C:\Popcorn\script>pop install Db Form

    // Remove some components
    C:\Popcorn\script>pop remove Auth Image
