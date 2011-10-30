<?php

namespace DTL\MVC\Router;
use DTL\MVC\Logger;

class Router
{
    protected $routes;

    public function __construct($routes = array())
    {
        $this->routes = $routes;
    }

    public function getRoute($request)
    {
        $path = $request->getPathInfo();

        foreach ($this->routes as $i => $route) {
            if (preg_match($route->pattern, $path, $matches)) {
                array_shift($matches); // drop first preg_match element

                if (count($route->params) != count($matches)) {
                    throw new \Exception(sprintf('Route "%s" contains "%s" placeholders but only "%s" parameter names given.',
                        $route->pattern,
                        count($matches),
                        count($route->params)
                    ));
                }

                $params = array_combine($route->params, $matches);

                $request->mergeParams($params);

                return $route;
            }
        }

        throw new \Exception('Cannot find route for "'.$path.'"');
    }
}
