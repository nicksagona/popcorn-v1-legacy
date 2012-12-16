<?php
/**
 * Pop PHP Framework
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
 * This is the Pop class.
 *
 * @category   Pop
 * @package    Pop
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2012 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/LICENSE.TXT     New BSD License
 * @version    1.1.0
 */
class Pop
{

    /**
     * Current version
     */
    const VERSION = '0.9';

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
        'get'    => array(),
        'post'   => array(),
        'put'    => array(),
        'delete' => array(),
        'error'  => null
    );

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
     * @return \Pop\Pop
     */
    public function __construct()
    {
        spl_autoload_register($this, true, true);
        $this->request = new \Pop\Http\Request();
        $this->response = new \Pop\Http\Response();
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
     * @return void
     */
    public function get($uri, $action)
    {
        $params = $this->getUriParams($uri);
        $uri = $this->getUriAction($uri);
        $this->routes['get'][$uri] = array(
            'action' => $action,
            'params' => $params
        );
    }

    /**
     * Add a URL to the 'post' route
     *
     * @param  string $uri
     * @param  mixed $action
     * @return void
     */
    public function post($uri, $action)
    {
        $params = $this->getUriParams($uri);
        $uri = $this->getUriAction($uri);
        $this->routes['post'][$uri] = array(
            'action' => $action,
            'params' => $params
        );
    }

    /**
     * Add a URL to the 'put' route
     *
     * @param  string $uri
     * @param  mixed $action
     * @return void
     */
    public function put($uri, $action)
    {
        $params = $this->getUriParams($uri);
        $uri = $this->getUriAction($uri);
        $this->routes['put'][$uri] = array(
            'action' => $action,
            'params' => $params
        );
    }

    /**
     * Add a URL to the 'delete' route
     *
     * @param  string $uri
     * @param  mixed $action
     * @return void
     */
    public function delete($uri, $action)
    {
        $params = $this->getUriParams($uri);
        $uri = $this->getUriAction($uri);
        $this->routes['delete'][$uri] = array(
            'action' => $action,
            'params' => $params
        );
    }

    /**
     * Add an action to the 'error' route
     *
     * @param  mixed $action
     * @return void
     */
    public function error($action)
    {
        $this->routes['error'] = $action;
    }

    /**
     * Add any project specific code to this method for run-time use here.
     *
     * @throws Exception
     * @return void
     */
    public function run()
    {
        $uri = $this->getUriAction($this->request->getRequestUri());
        $route = $this->routes[strtolower($this->request->getMethod())];
        $params = array();

        if (isset($route[$uri])) {
            $params = $this->getRequestParams($route[$uri]);
        }

        if ($this->isValidRequest($uri) && (count($params) == count($route[$uri]['params'])) && (array_search('', $params) === false)) {
            $this->result = call_user_func_array($route[$uri]['action'], $params);
        } else {
            if (null !== $this->routes['error']) {
                $this->result = call_user_func_array($this->routes['error'], array());
            } else {
                throw new Exception('Error: No error action has been defined.');
            }
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
            if (isset($params[$key])) {
                $requestParams[$value] = $params[$key];
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

        if ($this->request->getMethod() == 'GET') {
            if (array_key_exists($uri, $this->routes['get'])) {
                $code = 200;
            } else {
                if (array_key_exists($uri, $this->routes['post']) ||
                    array_key_exists($uri, $this->routes['put']) ||
                    array_key_exists($uri, $this->routes['delete'])) {
                    $code = 405;
                }
            }
        } else if ($this->request->getMethod() == 'POST') {
            if (array_key_exists($uri, $this->routes['post'])) {
                $code = 200;
            } else {
                if (array_key_exists($uri, $this->routes['get']) ||
                    array_key_exists($uri, $this->routes['put']) ||
                    array_key_exists($uri, $this->routes['delete'])) {
                    $code = 405;
                }
            }
        } else if ($this->request->getMethod() == 'PUT') {
            if (array_key_exists($uri, $this->routes['put'])) {
                $code = 200;
            } else {
                if (array_key_exists($uri, $this->routes['get']) ||
                    array_key_exists($uri, $this->routes['post']) ||
                    array_key_exists($uri, $this->routes['delete'])) {
                    $code = 405;
                }
            }
        } else if ($this->request->getMethod() == 'DELETE') {
            if (array_key_exists($uri, $this->routes['delete'])) {
                $code = 200;
            } else {
                if (array_key_exists($uri, $this->routes['get']) ||
                    array_key_exists($uri, $this->routes['post']) ||
                    array_key_exists($uri, $this->routes['put'])) {
                    $code = 405;
                }
            }
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

        if (strpos($class, 'Pop') !== false) {
            $classFile = realpath(__DIR__ . '/../' . $classFile);
        }

        if (!@include_once($classFile)) {
            return;
        }
    }

}