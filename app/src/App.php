<?php
/**
 * Created by PhpStorm.
 * User: Romuald
 * Date: 11/03/2019
 * Time: 11:10
 */

namespace app\src;

use app\src\response\Response;
use app\src\resquest\Request;
use app\src\route\route;
use app\src\ServiceContainer\Servicecontainer;


class App
{
    /**
     * @var array
     */
    private $route = array();

    /**
     * @var ServiceContainer
     */
    private $serviceContainer;

    /**
     * @var statusCode
     */
    private $statusCode;

    /**
     * App constructor.
     * @param ServiceContainer $serviceContainer
     */
    public function __construct(ServiceContainer $serviceContainer) {
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * Retrieve a service from the service container
     *
     * @param string $serviceName Name of the service to retrieve
     * @return mixed
     */
    public function getService(string $serviceName) {
        return $this->serviceContainer->get($serviceName);
    }

    /**
     * Set a service in  the service
     *
     * @param string $serviceName Name of the service set
     * @param $assigned Value of service to set
     */
    public function setService(string $serviceName, $assigned)
    {
        $this->serviceContainer->set($serviceName, $assigned);
    }

    /**
     * @param string $pattern
     * @param callable $callable
     * @return App $this
     */
    public function get(string $pattern, callable $callable) {
        $this->registerRoute(Request::GET, $pattern, $callable);
        return $this;
    }

    public function post(string $pattern, callable $callable) {
        $this->registerRoute(Request::POST, $pattern, $callable);
        return $this;
    }

    public function delete(string $pattern, callable $callable) {
        $this->registerRoute(Request::DELETE, $pattern, $callable);
        return $this;
    }

    public function put(string $pattern, callable $callable) {
        $this->registerRoute(Request::PUT, $pattern, $callable);
        return $this;
    }

    /**
     * Check which route to use inside the router
     *
     * @param Request|null $request
     * @throws \Exception
     */
    public function run(Request $request = null) {
        if ($request === null) {
            $request = Request::createFromGlobal();
        }

        $method = $request->getMethod();
        $uri = $request->getUri();

        foreach ($this->route as $route) {
            if ($route->match($method, $uri)) {
                return $this->process($route, $request);
            }
        }

        throw new \Exception('No routes available for this uri');
    }

    /**
     * Process route
     *
     * @param Route $route
     * @param Request $request
     * @throws \Exception
     */
    private function process(Route $route, Request $request) {
        try {
            $arguments = $route->getArguments();
            array_unshift($arguments, $request);
            $content = call_user_func_array($route->getCallable(), $arguments);

            if ($content instanceof Response) {
                $content->send();
                return;
            }

            $reponse = new Response($content, $this->statusCode ?? 200);
            $reponse->send();
        }
        catch (\Exception $e) {
            throw new Error('There was an error during the processing of your request.');
        }
    }

    /**
     * @param string $method
     * @param string $pattern
     * @param callable $callable
     */
    private function registerRoute(string $method, string $pattern, callable $callable) {
        $this->route[] = new Route($method, $pattern, $callable);
    }
}