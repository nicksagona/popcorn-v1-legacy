<?php
/**
 * Popcorn Micro-Framework (http://popcorn.popphp.org/)
 *
 * @link       https://github.com/nicksagona/Popcorn
 * @category   Pop
 * @package    Pop
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2013 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    https://raw.github.com/nicksagona/Popcorn/master/LICENSE.TXT     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Project;

/**
 * This is the main Pop Project class for the Popcorn Micro-Framework.
 *
 * @category   Pop
 * @package    Pop
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2013 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    https://raw.github.com/nicksagona/Popcorn/master/LICENSE.TXT     New BSD License
 * @version    1.2.3
 */
class Project
{

    /**
     * Current version
     */
    const VERSION = '1.2.3';

    /**
     * Current URL
     */
    const URL = 'http://popcorn.popphp.org/version.txt';

    /**
     * Strict URI parameter mapping flag
     * @var boolean
     */
    protected $strict = false;

    /**
     * Array of available namespaces prefixes.
     * @var array
     */
    protected $prefixes = array();

    /**
     * Config object
     * @var \Pop\Config
     */
    protected $config = null;

    /**
     * Request object
     * @var \Pop\Http\Request
     */
    protected $request = null;

    /**
     * Response object
     * @var \Pop\Http\Response
     */
    protected $response = null;

    /**
     * View object
     * @var \Pop\Mvc\View
     */
    protected $view = null;

    /**
     * View path
     * @var string
     */
    protected $viewPath = null;

    /**
     * Routes array
     * @var array
     */
    protected $routes = array(
        'get'     => array(),
        'head'    => array(),
        'post'    => array(),
        'put'     => array(),
        'delete'  => array(),
        'trace'   => array(),
        'options' => array(),
        'connect' => array(),
        'patch'   => array(),
        'error'   => array()
    );

    /**
     * Project events
     * @var \Pop\Event\Manager
     */
    protected $events = null;

    /**
     * Project services
     * @var \Pop\Service\Locator
     */
    protected $services = null;

    /**
     * Result
     * @var mixed
     */
    protected $result = null;

    /**
     * Component URL
     * @var string
     */
    protected $url = 'http://popcorn.popphp.org/components/';

    /**
     * Array of available CLI commands
     * @var array
     */
    protected $commands = array(
        'help',
        'list',
        'install',
        'remove',
        'version'
    );

    /**
     * Array of default components
     * @var array
     */
    protected $defaults = array(
        'Event',
        'File',
        'Http',
        'Mvc',
        'Project',
        'Service',
        'Web'
    );

    /**
     * Constructor
     *
     * Instantiate a Pop object
     *
     * @param  array   $config
     * @param  boolean $changes
     * @return \Pop\Project\Project
     */
    public function __construct(array $config = array(), $changes = false)
    {
        // Register the autoloader
        spl_autoload_register($this, true, true);
        $this->register('Pop', __DIR__ . '/../../');

        // Create necessary project properties and objects
        $this->request = new \Pop\Http\Request();
        $this->response = new \Pop\Http\Response();
        $this->events = new \Pop\Event\Manager();
        $this->services = new \Pop\Service\Locator();
        $this->config = new \Pop\Config($config, $changes);
    }

    /**
     * Set strict mapping to URI parameters
     *
     * @param  boolean $strict
     * @return \Pop\Pop
     */
    public function setStrict($strict)
    {
        $this->strict = (boolean)$strict;
        return $this;
    }

    /**
     * Method to get whether or not strict mapping is set
     *
     * @return boolean
     */
    public function isStrict()
    {
        return $this->strict;
    }

    /**
     * Register a namespace and directory location with the autoloader
     *
     * @param  string $namespace
     * @param  string $directory
     * @return \Pop\Pop
     */
    public function register($namespace, $directory)
    {
        $this->prefixes[$namespace] = realpath($directory);
        return $this;
    }

    /**
     * Method to set the config object
     *
     * @param  array   $config
     * @param  boolean $changes
     * @return \Pop\Pop
     */
    public function setConfig(array $config = array(), $changes = false)
    {
        $this->config = new \Pop\Config($config, $changes);
        return $this;
    }

    /**
     * Method to set the request object
     *
     * @param  \Pop\Http\Request $request
     * @return \Pop\Pop
     */
    public function setRequest(\Pop\Http\Request $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * Method to set the response object
     *
     * @param  \Pop\Http\Response $response
     * @return \Pop\Pop
     */
    public function setResponse(\Pop\Http\Response $response)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * Method to set the view object
     *
     * @param  \Pop\Mvc\View $view
     * @return \Pop\Pop
     */
    public function setView($view)
    {
        $this->view = $view;
        return $this;
    }

    /**
     * Method to set the view path
     *
     * @param  string $path
     * @return \Pop\Pop
     */
    public function setViewPath($path)
    {
        $this->viewPath = $path;
        return $this;
    }

    /**
     * Add a URI to the GET route
     *
     * @param  string $uri
     * @param  mixed $action
     * @return \Pop\Pop
     */
    public function get($uri, $action)
    {
        $asArray = (strpos($uri, '#') !== false);

        $params = $this->getUriParams($uri);
        $uri = $this->getUri($uri);

        $this->routes['get'][$uri] = array(
            'action'  => $action,
            'params'  => $params,
            'asArray' => $asArray
        );

        return $this;
    }

    /**
     * Add a URI to the HEAD route
     *
     * @param  string $uri
     * @param  mixed $action
     * @return \Pop\Pop
     */
    public function head($uri, $action)
    {
        $asArray = (strpos($uri, '#') !== false);

        $params = $this->getUriParams($uri);
        $uri = $this->getUri($uri);

        $this->routes['head'][$uri] = array(
            'action' => $action,
            'params' => $params,
            'asArray' => $asArray
        );

        return $this;
    }

    /**
     * Add a URI to the POST route
     *
     * @param  string $uri
     * @param  mixed $action
     * @return \Pop\Pop
     */
    public function post($uri, $action)
    {
        $asArray = (strpos($uri, '#') !== false);

        $params = $this->getUriParams($uri);
        $uri = $this->getUri($uri);

        $this->routes['post'][$uri] = array(
            'action' => $action,
            'params' => $params,
            'asArray' => $asArray
        );

        return $this;
    }

    /**
     * Add a URI to the PUT route
     *
     * @param  string $uri
     * @param  mixed $action
     * @return \Pop\Pop
     */
    public function put($uri, $action)
    {
        $asArray = (strpos($uri, '#') !== false);

        $params = $this->getUriParams($uri);
        $uri = $this->getUri($uri);

        $this->routes['put'][$uri] = array(
            'action' => $action,
            'params' => $params,
            'asArray' => $asArray
        );

        return $this;
    }

    /**
     * Add a URI to the DELETE route
     *
     * @param  string $uri
     * @param  mixed $action
     * @return \Pop\Pop
     */
    public function delete($uri, $action)
    {
        $asArray = (strpos($uri, '#') !== false);

        $params = $this->getUriParams($uri);
        $uri = $this->getUri($uri);

        $this->routes['delete'][$uri] = array(
            'action' => $action,
            'params' => $params,
            'asArray' => $asArray
        );

        return $this;
    }

    /**
     * Add a URI to the TRACE route
     *
     * @param  string $uri
     * @param  mixed $action
     * @return \Pop\Pop
     */
    public function trace($uri, $action)
    {
        $asArray = (strpos($uri, '#') !== false);

        $params = $this->getUriParams($uri);
        $uri = $this->getUri($uri);

        $this->routes['trace'][$uri] = array(
            'action' => $action,
            'params' => $params,
            'asArray' => $asArray
        );

        return $this;
    }

    /**
     * Add a URI to the OPTIONS route
     *
     * @param  string $uri
     * @param  mixed $action
     * @return \Pop\Pop
     */
    public function options($uri, $action)
    {
        $asArray = (strpos($uri, '#') !== false);

        $params = $this->getUriParams($uri);
        $uri = $this->getUri($uri);

        $this->routes['options'][$uri] = array(
            'action' => $action,
            'params' => $params,
            'asArray' => $asArray
        );

        return $this;
    }

    /**
     * Add a URI to the CONNECT route
     *
     * @param  string $uri
     * @param  mixed $action
     * @return \Pop\Pop
     */
    public function connect($uri, $action)
    {
        $asArray = (strpos($uri, '#') !== false);

        $params = $this->getUriParams($uri);
        $uri = $this->getUri($uri);

        $this->routes['connect'][$uri] = array(
            'action' => $action,
            'params' => $params,
            'asArray' => $asArray
        );

        return $this;
    }

    /**
     * Add a URI to the PATCH route
     *
     * @param  string $uri
     * @param  mixed $action
     * @return \Pop\Pop
     */
    public function patch($uri, $action)
    {
        $asArray = (strpos($uri, '#') !== false);

        $params = $this->getUriParams($uri);
        $uri = $this->getUri($uri);

        $this->routes['patch'][$uri] = array(
            'action' => $action,
            'params' => $params,
            'asArray' => $asArray
        );

        return $this;
    }

    /**
     * Add an action to the ERROR route
     *
     * @param  mixed $action
     * @param  string $root
     * @return \Pop\Pop
     */
    public function error($action, $root = '/')
    {
        $this->routes['error'][$root] = $action;
        return $this;
    }

    /**
     * Add an action to multiple routes
     *
     * @param  string $methods
     * @param  string $uri
     * @param  mixed $action
     * @throws \Pop\Exception
     * @return \Pop\Pop
     */
    public function route($methods, $uri = null, $action)
    {
        // Get methods
        $methods = explode(',', str_replace(', ', ',', $methods));

        // Loop through the methods, validating and storing their URIs/actions
        foreach ($methods as $method) {
            $method = strtolower($method);
            if (!array_key_exists($method, $this->routes)) {
                throw new \Pop\Exception('Error: One or more of the methods are not valid.');
            }
            if ($method == 'error') {
                $this->error($action);
            } else {
                if (null === $uri) {
                    throw new \Pop\Exception('Error: You must assign a URI to an action routed to the ' . strtoupper($method) . ' method.');
                }
                $this->$method($uri, $action);
            }
        }

        return $this;
    }

    /**
     * Method to get the config object
     *
     * @return \Pop\Config
     */
    public function config()
    {
        return $this->config;
    }

    /**
     * Attach an event. Default project event name hook-points are:
     *
     *   route.pre
     *   route.post
     *   dispatch.pre
     *   dispatch.post
     *
     * @param  string $name
     * @param  mixed  $action
     * @param  int    $priority
     * @return \Pop\Pop
     */
    public function attachEvent($name, $action, $priority = 0)
    {
        $this->events->attach($name, $action, $priority);
        return $this;
    }

    /**
     * Detach an event. Default project event name hook-points are:
     *
     *   route.pre
     *   route.post
     *   dispatch.pre
     *   dispatch.post
     *
     * @param  string $name
     * @param  mixed  $action
     * @return \Pop\Pop
     */
    public function detachEvent($name, $action)
    {
        $this->events->detach($name, $action);
        return $this;
    }

    /**
     * Get the event Manager
     *
     * @return \Pop\Event\Manager
     */
    public function getEventManager()
    {
        return $this->events;
    }

    /**
     * Set a service
     *
     * @param  string $name
     * @param  mixed  $call
     * @param  mixed  $params
     * @return \Pop\Pop
     */
    public function setService($name, $call, $params = null)
    {
        $this->services->set($name, $call, $params);
        return $this;
    }

    /**
     * Get a service
     *
     * @param  string $name
     * @return mixed
     */
    public function getService($name)
    {
        return $this->services->get($name);
    }

    /**
     * Get the service Locator
     *
     * @return \Pop\Service\Locator
     */
    public function getServiceLocator()
    {
        return $this->services;
    }

    /**
     * Method to get the request
     *
     * @return \Pop\Http\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Method to get the response
     *
     * @return \Pop\Http\Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Method to get the view object
     *
     * @return \Pop\Mvc\View
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Method to get the view path
     *
     * @return string
     */
    public function getViewPath()
    {
        return $this->viewPath;
    }

    /**
     * Method to get routes
     *
     * @param  string $method
     * @return mixed
     */
    public function getRoutes($method = null)
    {
        if (null !== $method) {
            return (isset($this->routes[$method])) ? $this->routes[$method] : null;
        } else {
            return $this->routes;
        }
    }

    /**
     * Method to get the result
     *
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Add any project specific code to this method for run-time use here.
     *
     * @throws \Pop\Exception
     * @return void
     */
    public function run()
    {
        // Populate necessary variables
        $route = $this->routes[strtolower($this->request->getMethod())];
        $uri = $this->getUriMatch($this->request->getRequestUri(), $route);
        $params = array();

        // Trigger any pre-route events
        $this->events->trigger('route.pre', array('project' => $this));

        // If still alive after 'route.pre'
        if ($this->events->alive()) {
            // Get params for the route
            if (isset($route[$uri])) {
                $params = $this->getRequestParams($uri, $route[$uri]);
            }

            // If the request and parameters are valid, call the assigned action
            if ($this->isValidRequest($uri) &&
                $this->isValidParams($uri, $route[$uri]['params'], $params) &&
                (count($params) == count($route[$uri]['params']))) {
                $params = $this->getRequestParams($uri, $route[$uri], $route[$uri]['asArray']);
                $method = (substr($uri, -1) == '/') ? 'index' : substr($uri, strrpos($uri, '/'));
                if (substr($method, 0, 1) == '/') {
                    $method = substr($method, 1);
                }
                $this->result = call_user_func_array($this->getCallable($route[$uri]['action'], $method), $params);
            // Else, trigger the error action
            } else {
                $error = $this->getErrorMatch($this->request->getRequestUri());
                if (isset($this->routes['error'][$error])) {
                    if (!headers_sent()) {
                        $this->response->setCode(404);
                        $this->response->sendHeaders();
                    }
                    $this->result = call_user_func_array($this->getCallable($this->routes['error'][$error], 'error'), array());
                } else {
                    throw new \Pop\Exception('Error: No error action has been defined to handle errors.');
                }
            }

            // Trigger any post-route events
            $this->events->trigger('route.post', array('project' => $this));

            // If still alive after 'route.post'
            if ($this->events->alive()) {
                // If the result is a model object, send it to the view object and send response
                if ((null !== $this->result) && ($this->result instanceof \Pop\Mvc\Model)) {
                    if ($this->response->getCode() == 200) {
                        $viewFile = (substr($uri, -1) == '/') ? $uri . 'index.phtml' : $uri . '.phtml';
                    } else {
                        $viewFile = '/error.phtml';
                    }

                    // Create the view object
                    $this->view = \Pop\Mvc\View::factory($this->viewPath . $viewFile, $this->result);

                    // Trigger any pre-dispatch events
                    $this->events->trigger('dispatch.pre', array('project' => $this));

                    // If still alive after 'dispatch.pre'
                    if ($this->events->alive()) {
                        // Set the response body and send the response
                        $this->response->setBody($this->view->render(true));
                        $this->response->send();

                        // Trigger any post-dispatch events
                        $this->events->trigger('dispatch.post', array('project' => $this));
                    }
                }
            }
        }
    }

    /**
     * Run the CLI to manage additional components
     *
     * @param  array $argv
     * @throws \Pop\Exception
     * @return void
     */
    public function cli($argv)
    {
        $xmlObj = null;
        $xml = array(
            'base'       => null,
            'version'    => null,
            'required'   => null,
            'components' => array()
        );

        // Parse the XML file
        if (($xmlObj =@ new \SimpleXMLElement($this->url . 'popcorn.xml', LIBXML_NOWARNING, true)) !== false) {
            $xml['base'] = (string)$xmlObj->attributes()->base;
            $xml['version'] = (string)$xmlObj->attributes()->version;
            $xml['required'] = (string)$xmlObj->attributes()->required;

            foreach ($xmlObj->component as $item) {
                $comp = (string)$item->attributes()->name;
                $xml['components'][$comp] = array();
                if ($item->count() > 0) {
                    $children = $item->children();
                    foreach ($children as $child) {
                        $xml['components'][$comp][] = (string)$child->attributes()->name;
                    }
                }
            }

            // Validate command parameter
            if (!isset($argv[2])) {
                throw new \Pop\Exception('You must pass a command parameter, i.e. \'install\' or \'remove\'.');
            } else if (!in_array($argv[2], $this->commands)) {
                throw new \Pop\Exception(
                    'That is not a valid command. Available commands are \'' .
                    implode('\', \'', $this->commands) . '\'.'
                );
            }

            $ext = $argv[1];
            $command = $argv[2];
            $parameters = $argv;
            array_shift($parameters);
            array_shift($parameters);
            array_shift($parameters);

            // Validate component parameters
            if (($command == 'install') || ($command == 'remove')) {
                if (!isset($parameters[0])) {
                    throw new \Pop\Exception('You must pass at least one component to install or remove.');
                }
                if (strtolower($parameters[0]) != 'all') {
                    foreach ($parameters as $comp) {
                        if (!array_key_exists($comp, $xml['components'])) {
                            if (in_array($comp, $this->defaults)) {
                                $msg = 'One or more of the components is a default component. It cannot be installed or removed.' .
                                    PHP_EOL . 'Use \'./pop list\' to list the available components.';
                            } else {
                                $msg = 'One or more of the components is not available.' .
                                    PHP_EOL . 'Use \'./pop list\' to list the available components.';
                            }
                            throw new \Pop\Exception($msg);
                        }
                    }
                }
            }

            // Execute command
            switch ($command) {
                // Show the version
                case 'version':
                    $latest = null;
                    $handle = fopen(self::URL, 'r');
                    if ($handle !== false) {
                        $latest = stream_get_contents($handle);
                        fclose($handle);
                    }
                    echo PHP_EOL . 'Popcorn ' . self::VERSION . ' is installed and uses components from Pop PHP Framework ' . $xml['required'] . '.';
                    echo PHP_EOL . 'Popcorn ' . trim($latest) . ' is the latest available.' . PHP_EOL . PHP_EOL;
                    break;

                // Display help
                case 'help':
                    echo PHP_EOL . 'Help for Popcorn:';
                    echo PHP_EOL . '=================' . PHP_EOL;
                    echo PHP_EOL . wordwrap('The Popcorn CLI interface serves as a dependency manager and allows you to install or remove certain components from the Pop PHP Framework to use with Popcorn. It requires either the TAR or ZIP program to be installed.', 80, PHP_EOL) . PHP_EOL . PHP_EOL;
                    echo "  help\t\t\tDisplay this help" . PHP_EOL;
                    echo "  version\t\tDisplay the version" . PHP_EOL;
                    echo "  list\t\t\tList available components" . PHP_EOL;
                    echo "  install Comp1 Comp2\tInstall components" . PHP_EOL;
                    echo "  install all\t\tInstall all components" . PHP_EOL;
                    echo "  remove Comp1 Comp2\tRemove components" . PHP_EOL;
                    echo "  remove all\t\tRemove all components" . PHP_EOL;
                    echo PHP_EOL;
                    break;

                // List available components
                case 'list':
                    echo PHP_EOL . 'Available Components for Popcorn:';
                    echo PHP_EOL . '=================================' . PHP_EOL;
                    foreach ($xml['components'] as $comp => $value) {
                        $prefix = (file_exists(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $comp)) ? ' [i] ' : '     ';
                        echo $prefix . $comp . PHP_EOL;
                    }
                    echo PHP_EOL;
                    break;

                // Install components
                case 'install':
                    echo PHP_EOL;
                    $comps = array();
                    $deps = array();

                    // If 'all', then install all
                    if (strtolower($parameters[0]) == 'all') {
                        $comps = array_keys($xml['components']);
                    // Else, select which components and their dependencies to install
                    } else {
                        foreach ($parameters as $parameter) {
                            $comps[] = $parameter;
                            if (count($xml['components'][$parameter]) > 0) {
                                foreach ($xml['components'][$parameter] as $param) {
                                    if (!in_array($param, $comps)) {
                                        $comps[] = $param;
                                    }
                                }
                            }
                        }
                    }

                    // Get dependencies
                    foreach ($comps as $comp) {
                        if (count($xml['components'][$comp]) > 0) {
                            foreach ($xml['components'][$comp] as $com) {
                                if (!in_array($com, $comps)) {
                                    $deps[] = $com;
                                }
                            }
                        }
                    }

                    $comps = array_merge($comps, $deps);

                    // Download the component files
                    foreach ($comps as $comp) {
                        echo 'Downloading ' . $comp;
                        $this->download($comp, $ext);
                        echo PHP_EOL;
                    }

                    break;

                // Remove components
                case 'remove':
                    echo PHP_EOL;
                    $comps = array();
                    $deps = array();
                    $installed = array();
                    $skip = array();
                    $dir = new \Pop\File\Dir(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
                    $files = $dir->getFiles();

                    // Check which components are already installed
                    foreach ($files as $file) {
                        if (is_dir(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $file) && !in_array($file, $this->defaults)) {
                            $installed[] = $file;
                        }
                    }

                    // If 'all', then remove all
                    if (strtolower($parameters[0]) == 'all') {
                        $comps = array_keys($xml['components']);
                    // Else, select which components and their dependencies to remove
                    } else {
                        foreach ($installed as $parameter) {
                            // Get dependencies of installed components
                            if (count($xml['components'][$parameter]) > 0) {
                                foreach ($xml['components'][$parameter] as $param) {
                                    if (in_array($param, $installed)) {
                                        if (!isset($deps[$parameter])) {
                                            $deps[$parameter] = array($param);
                                        } else if (!in_array($param, $deps[$parameter])) {
                                            $deps[$parameter][] = $param;
                                        }
                                    }
                                    if (count($xml['components'][$param]) > 0) {
                                        foreach ($xml['components'][$param] as $par) {
                                            if (in_array($par, $installed)) {
                                                if (!isset($deps[$parameter])) {
                                                    $deps[$parameter] = array($par);
                                                } else if (!in_array($par, $deps[$parameter])) {
                                                    $deps[$parameter][] = $par;
                                                }
                                            }
                                        }
                                    }
                                }
                                if (isset($deps[$parameter])) {
                                    sort($deps[$parameter]);
                                }
                            } else {
                                $deps[$parameter] = array();
                            }
                        }
                    }

                    // Sort through the components (and their dependencies)
                    // that were flagged for removal
                    foreach ($parameters as $parameter) {
                        if (!in_array($parameter, $comps)) {
                            $comps[] = $parameter;
                        }
                        foreach ($deps as $key => $value) {
                            if (in_array($parameter, $value)) {
                                if (isset($skip[$parameter])) {
                                    $skip[$parameter][] = $key;
                                } else {
                                    $skip[$parameter] = array($key);
                                }
                            }
                        }
                        if (isset($deps[$parameter])) {
                            foreach ($deps[$parameter] as $param) {
                                if (!in_array($param, $comps)) {
                                    $comps[] = $param;
                                }
                                foreach ($deps as $key => $value) {
                                    if (($key != $parameter) && in_array($param, $value)) {
                                        if (!in_array($param, $deps[$parameter]) || !in_array($key, $deps[$parameter])) {
                                            if (isset($skip[$param])) {
                                                $skip[$param][] = $key;
                                            } else {
                                                $skip[$param] = array($key);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    sort($installed);
                    sort($comps);

                    // Begin to remove the components
                    foreach ($comps as $comp) {
                        if ($comp != 'all') {
                            // If require by other installed components
                            if (array_key_exists($comp, $skip)) {
                                echo 'Skipping ' . $comp . '. It is required by the following components: ' . implode(', ', $skip[$comp]) . '.';
                            // If a default component
                            } else if (in_array($comp, $this->defaults)) {
                                echo 'Skipping ' . $comp . '. It is a default component.';
                            // If component is not installed or does not exist
                            } else if (!file_exists(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $comp)) {
                                echo 'Skipping ' . $comp . '. ' . (array_key_exists($comp, $xml['components']) ? 'It is not installed.' : 'It does not exist');
                            // Else, remove it
                            } else {
                                echo 'Removing ' . $comp;
                                $dir = new \Pop\File\Dir(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $comp);
                                $dir->emptyDir(null, true);
                            }
                            echo PHP_EOL;
                        }
                    }

                    echo PHP_EOL . 'Complete!' . PHP_EOL;

                    break;
            }
        } else {
            throw new \Pop\Exception('The component URL cannot be read at this time.');
        }

    }

    /**
     * Method to get the URI action
     *
     * @param  string $uri
     * @return string
     */
    protected function getUri($uri)
    {
        // If URI has params
        if (strpos($uri, '/:') !== false) {
            $uri = substr($uri, 0, strpos($uri, '/:'));
        }
        return $uri;
    }

    /**
     * Method to get the URI parameters
     *
     * @param  string $uri
     * @return array
     */
    protected function getUriParams($uri)
    {
        $params = array();

        if (strpos($uri, '/:') !== false) {
            $params = explode('/:', $uri);
            unset($params[0]);
        }
        foreach ($params as $key => $value) {
            $params[$key] = str_replace('#', '', $value);
        }
        return $params;
    }

    /**
     * Method to match the requested URI to a routed URI
     *
     * @param  string $uri
     * @param  array $route
     * @return string
     */
    protected function getUriMatch($uri, $route)
    {
        $match = null;
        // If root, see if there's an exact match
        if ($uri == '/') {
            if (isset($route[$uri])) {
                $match = $uri;
            }
        // Else, scan for a match
        } else {
            foreach ($route as $key => $value) {
                if ($key != '/') {
                    if (substr($uri, 0, strlen($key)) == $key) {
                        $match = $key;
                    }
                }
            }
        }

        // If still no match, try with a trailing slash if there is none
        if ((null === $match) && (substr($uri, -1) != '/')) {
            if (isset($route[$uri . '/'])) {
                $match = $uri . '/';
            }
        }

        return $match;
    }

    /**
     * Method to match the requested URI to a error route
     *
     * @param  string $uri
     * @return string
     */
    protected function getErrorMatch($uri)
    {
        $match = '/';
        // If root, see if there's an exact match
        if ($uri == '/') {
            if (isset($this->routes['error'][$uri])) {
                $match = $uri;
            }
        // Else, scan for a match
        } else {
            foreach ($this->routes['error'] as $key => $value) {
                if ($key != '/') {
                    if (substr($uri, 0, strlen($key)) == $key) {
                        $match = $key;
                    }
                }
            }
        }

        // If still no match, try with a trailing slash if there is none
        if ((null === $match) && (substr($uri, -1) != '/')) {
            if (isset($this->routes[$uri . '/'])) {
                $match = $uri . '/';
            }
        }

        return $match;
    }

    /**
     * Method to get the URI parameters
     *
     * @param  string  $uri
     * @param  array   $route
     * @param  boolean $asArray
     * @return array
     */
    protected function getRequestParams($uri, $route, $asArray = false)
    {
        $requestParams = array();
        $params = $this->request->getPath();
        $stems = explode('/', $uri);

        foreach ($stems as $stem) {
            if (($stem != '') && (in_array($stem, $params))) {
                unset($params[array_search($stem, $params)]);
            }
        }

        // Handle trailing slash (last position empty)
        if (empty($params[count($params)])) {
            unset($params[count($params)]);
        }

        foreach ($params as $key => $value) {
            if ($value == '') {
                $params[$key] = null;
            }
        }

        foreach ($route['params'] as $key => $value) {
            if (substr($value, -1) == '*') {
                $requestParams[$value] = (count($params) > 0) ? $params : array();
            } else {
                $requestParams[$value] = (isset($params[$key])) ? $params[$key] : null;
            }
        }

        // If the returned parameter result should be an array
        if ($asArray) {
            $requestParams = array($requestParams);
        }

        return $requestParams;
    }

    /**
     * Method to determine a valid request
     *
     * @param  string $uri
     * @return boolean
     */
    protected function isValidRequest($uri)
    {
        $code = (array_key_exists($uri, $this->routes[strtolower($this->request->getMethod())])) ? 200 : 404;
        $this->response->setCode($code);
        return ($code == 200);
    }

    /**
     * Method to determine a valid parameters in strict mode
     *
     * @param  string  $uri
     * @param  array $routeParams
     * @param  array $uriParams
     * @return boolean
     */
    protected function isValidParams($uri, $routeParams, $uriParams)
    {
        $result = true;
        $isArray = false;

        if ($this->strict) {
            $requestParams = $this->request->getPath();
            $stems = explode('/', $uri);
            foreach ($stems as $stem) {
                if (($stem != '') && (in_array($stem, $requestParams))) {
                    unset($requestParams[array_search($stem, $requestParams)]);
                }
            }

            // Handle trailing slash (last position empty)
            if (empty($requestParams[count($requestParams)])) {
                unset($requestParams[count($requestParams)]);
            }


            // If any parameter value is not set, null or empty, set to false
            foreach ($routeParams as $param) {
                if (is_array($uriParams[$param])) {
                    $isArray = true;
                }
                if (is_array($uriParams[$param]) && (in_array(null, $uriParams[$param]))) {
                    $result = false;
                } else if (!isset($uriParams[$param]) || empty($uriParams[$param])) {
                    $result = false;
                }
            }

            // If the number of requested parameters do not match
            // the expected routed parameters, set to false
            if ((!$isArray) && (count($requestParams) != count($routeParams))) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * Get and validate the callable action
     *
     * @param  mixed  $callable
     * @param  string $method
     * @return mixed
     */
    protected function getCallable($callable, $method = null)
    {
        if (is_string($callable) && (strpos($callable, '::') === false)) {
            // If the string contains -> notation, get the method from that
            if (strpos($callable, '->') !== false) {
                $ary = explode('->', $callable);
                $class = $ary[0];
                $method = $ary[1];

                $obj = new $class();

                // If the object is a controller, set the project object
                if ($obj instanceof \Pop\Mvc\Controller) {
                    $obj->setProject($this);
                }

                $callable = array($obj, $method);
            // Else call using the method passed from the URI
            } else if (null !== $method) {
                $obj = new $callable();

                // If the object is a controller, set the project object
                if ($obj instanceof \Pop\Mvc\Controller) {
                    $obj->setProject($this);
                }
                $callable = array($obj, $method);
            }
        }

        return $callable;
    }

    /**
     * Method to download a component files
     *
     * @param  string $component
     * @param  string $ext
     * @return void
     */
    protected function download($component, $ext)
    {
        $archive = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $component . $ext;
        $file = fopen ($this->url . '/' . $component . $ext, "rb");
        if ($file) {
            $arc = fopen ($archive, "wb");
            if ($arc) {
                while(!feof($file)) {
                    echo '.';
                    fwrite($arc, fread($file, 1024 * 8 ), 1024 * 8 );
                }
            }
        }
        if ($file) {
            fclose($file);
        }
        if ($arc) {
            fclose($arc);
        }
    }

    /**
     * Invoke the class
     *
     * @param  string $class
     * @return void
     */
    public function __invoke($class)
    {
        $sep = (strpos($class, '\\') !== false) ? '\\' : '_';
        $classFile = str_replace($sep, DIRECTORY_SEPARATOR, $class) . '.php';

        // Check to see if the prefix is registered with the autoloader
        $prefix = null;
        foreach ($this->prefixes as $key => $value) {
            if (strpos($class, $key) !== false) {
                $prefix = $key;
            }
        }

        // If the prefix was found, append the correct directory
        if (null !== $prefix) {
            $classFile = $this->prefixes[$prefix] . DIRECTORY_SEPARATOR . $classFile;
        }

        if (!@include_once($classFile)) {
            return;
        }
    }

}
