<?php
/**
 * Popcorn Micro-Framework
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
 * @category   Pop
 * @package    Pop
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2012 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/LICENSE.TXT     New BSD License
 */

/**
 * @namespace
 */
namespace Pop;

/**
 * This is the main Pop class for the Popcorn Micro-Framework.
 *
 * @category   Pop
 * @package    Pop
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2012 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/LICENSE.TXT     New BSD License
 * @version    0.9
 */
class Pop
{

    /**
     * Current version
     */
    const VERSION = '0.9';

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
        'post'    => array(),
        'put'     => array(),
        'delete'  => array(),
        'options' => array(),
        'head'    => array(),
        'error'   => null
    );

    /**
     * Project events
     * @var \Pop\Event\Manager
     */
    protected $events = null;

    /**
     * Result
     * @var mixed
     */
    protected $result = null;

    /**
     * Constructor
     *
     * Instantiate a Pop object
     *
     * @param  array   $config
     * @param  boolean $changes
     * @return \Pop\Pop
     */
    public function __construct(array $config = array(), $changes = false)
    {
        spl_autoload_register($this, true, true);
        $this->register('Pop', __DIR__ . '/../');
        $this->request = new \Pop\Http\Request();
        $this->response = new \Pop\Http\Response();
        $this->events = new \Pop\Event\Manager();
        $this->config = new \Pop\Config($config, $changes);
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
     * Add a URL to the 'get' route
     *
     * @param  string $uri
     * @param  mixed $action
     * @return \Pop\Pop
     */
    public function get($uri, $action)
    {
        $params = $this->getUriParams($uri);
        $uri = $this->getUriAction($uri);
        $this->routes['get'][$uri] = array(
            'action' => $action,
            'params' => $params
        );

        return $this;
    }

    /**
     * Add a URL to the 'post' route
     *
     * @param  string $uri
     * @param  mixed $action
     * @return \Pop\Pop
     */
    public function post($uri, $action)
    {
        $params = $this->getUriParams($uri);
        $uri = $this->getUriAction($uri);
        $this->routes['post'][$uri] = array(
            'action' => $action,
            'params' => $params
        );

        return $this;
    }

    /**
     * Add a URL to the 'put' route
     *
     * @param  string $uri
     * @param  mixed $action
     * @return \Pop\Pop
     */
    public function put($uri, $action)
    {
        $params = $this->getUriParams($uri);
        $uri = $this->getUriAction($uri);
        $this->routes['put'][$uri] = array(
            'action' => $action,
            'params' => $params
        );

        return $this;
    }

    /**
     * Add a URL to the 'delete' route
     *
     * @param  string $uri
     * @param  mixed $action
     * @return \Pop\Pop
     */
    public function delete($uri, $action)
    {
        $params = $this->getUriParams($uri);
        $uri = $this->getUriAction($uri);
        $this->routes['delete'][$uri] = array(
            'action' => $action,
            'params' => $params
        );

        return $this;
    }

    /**
     * Add a URL to the 'options' route
     *
     * @param  string $uri
     * @param  mixed $action
     * @return \Pop\Pop
     */
    public function options($uri, $action)
    {
        $params = $this->getUriParams($uri);
        $uri = $this->getUriAction($uri);
        $this->routes['options'][$uri] = array(
            'action' => $action,
            'params' => $params
        );

        return $this;
    }

    /**
     * Add a URL to the 'head' route
     *
     * @param  string $uri
     * @param  mixed $action
     * @return \Pop\Pop
     */
    public function head($uri, $action)
    {
        $params = $this->getUriParams($uri);
        $uri = $this->getUriAction($uri);
        $this->routes['head'][$uri] = array(
            'action' => $action,
            'params' => $params
        );

        return $this;
    }

    /**
     * Add an action to the 'error' route
     *
     * @param  mixed $action
     * @return \Pop\Pop
     */
    public function error($action)
    {
        $this->routes['error'] = $action;
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
     * Get the event Manager
     *
     * @return \Pop\Event\Manager
     */
    public function getEventManager()
    {
        return $this->events;
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
     * @throws Exception
     * @return void
     */
    public function run()
    {
        // Populate necessary variables
        $uri = $this->getUriAction($this->request->getRequestUri());
        $route = $this->routes[strtolower($this->request->getMethod())];
        $params = array();

        // Trigger any pre-route events
        $this->events->trigger('route.pre', array('project' => $this));

        // Get params for the route
        if (isset($route[$uri])) {
            $params = $this->getRequestParams($route[$uri]);
        }

        // If the route is valid, call the assigned action
        if ($this->isValidRequest($uri) && (count($params) == count($route[$uri]['params']))) {
            $params = $this->getRequestParams($route[$uri]);
            $this->result = call_user_func_array($route[$uri]['action'], $params);
        // Else, trigger the error action
        } else {
            if (null !== $this->routes['error']) {
                $this->result = call_user_func_array($this->routes['error'], array());
            } else {
                throw new Exception('Error: No error action has been defined to handle errors.');
            }
        }

        // Trigger any post-route events
        $this->events->trigger('route.post', array('project' => $this));

        // If the result is a model object, send it to the view object and send response
        if ((null !== $this->result) && ($this->result instanceof Mvc\Model)) {
            if ($this->response->getCode() == 200) {
                $viewFile = ($uri == '/') ? '/index.phtml' : $uri . '.phtml';
            } else {
                $viewFile = '/error.phtml';
            }

            // Create the view object
            $this->view = Mvc\View::factory($this->viewPath . $viewFile, $this->result);

            // Trigger any pre-dispatch events
            $this->events->trigger('dispatch.pre', array('project' => $this));

            // Set the response body and send the response
            $this->response->setBody($this->view->render(true));
            $this->response->send();

            // Trigger any post-dispatch events
            $this->events->trigger('dispatch.post', array('project' => $this));
        }
    }

    /**
     * Method to get the URI action
     *
     * @param  string $uri
     * @return string
     */
    protected function getUriAction($uri)
    {
        if (substr_count($uri, '/') > 1) {
            $uri = substr($uri, 1);
            $uri = '/' . substr($uri, 0, strpos($uri, '/'));
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

        return $params;
    }

    /**
     * Method to get the URI parameters
     *
     * @param  array $route
     * @return array
     */
    protected function getRequestParams($route)
    {
        $requestParams = array();
        $params = $this->request->getPath();

        unset($params[0]);

        foreach ($route['params'] as $key => $value) {
            if (substr($value, -1) == '*') {
                $requestParams[$value] = (count($params) > 0) ? $params : array();
            } else {
                $requestParams[$value] = (isset($params[$key])) ? $params[$key] : null;
            }
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
        $code = 404;

        if (($this->request->getMethod() == 'GET') &&
            array_key_exists($uri, $this->routes['get'])) {
            $code = 200;
        } else if (($this->request->getMethod() == 'POST') &&
            array_key_exists($uri, $this->routes['post'])) {
            $code = 200;
        } else if (($this->request->getMethod() == 'PUT') &&
            array_key_exists($uri, $this->routes['put'])) {
            $code = 200;
        } else if (($this->request->getMethod() == 'DELETE') &&
            array_key_exists($uri, $this->routes['delete'])) {
            $code = 200;
        } else if (($this->request->getMethod() == 'OPTIONS') &&
            array_key_exists($uri, $this->routes['options'])) {
            $code = 200;
        } else if (($this->request->getMethod() == 'HEAD') &&
            array_key_exists($uri, $this->routes['head'])) {
            $code = 200;
        }

        $this->response->setCode($code);

        return ($code == 200);
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