<?php

namespace DTL\MVC\Router;

class Route
{
    public $pattern;
    public $params;
    public $controller;
    public $action;

    public function __construct($pattern, $params = array(), $target)
    {
        $this->pattern = $pattern;
        $this->params = $params;
        $this->target = $target;

        $parts = explode('::', $target);
        if (count($parts) < 2) {
            throw new \Exception('Route target be of the form Foo\Bar\MyController::myAction. Got "'.$target.'"');
        }

        $this->controller = $parts[0];
        $this->action = $parts[1];
    }
}
