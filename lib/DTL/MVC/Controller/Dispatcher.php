<?php

namespace DTL\MVC\Controller;
use DTL\MVC\Controller\Request;
use DTL\MVC\Controller\Response;
use DTL\MVC\Logger;
use DTL\MVC\Util;
use DTL\MVC\Service\ServiceContainer;

class Dispatcher
{
    protected $sc;

    public function __construct(ServiceContainer $serviceContainer)
    {
        $this->sc = $serviceContainer;
    }

    public function dispatch(Request $request)
    {
        $route = $this->sc->get('router')->getRoute($request);
        $this->sc->get('logger')->info('Matched route "'.$route->pattern.'" to "'.$route->target.'"', $request->params);

        $controller = Util::newClass($route->controller);
        $this->sc->get('logger')->info('Instantiated controller "'.$route->controller.'"');

        $action = $route->action;
        if (!method_exists($controller, $action)) {
            throw new \Exception(sprintf('Action "%s" does not exist in controller "%s"', $route->action, $route->controller));
        }

        $this->sc->get('logger')->info('Passing control to "'.$route->controller.'"::"'.$route->action.'"');

        if (!$response = $controller->$action($this->sc, $request)) {
            throw new \Exception('Target "'.$route->target.'" has not returned a response.');
        }

        if (!$response instanceOf Response) {
            throw new \Exception('Target "'.$route->target.'" has not returned a Response object.');
        }

        $this->sc->get('logger')->info('Recieved response from "'.$route->controller.'"::"'.$route->action.'"');

        $response->send();
        $this->sc->get('logger')->info('Response sent');
    }
}
