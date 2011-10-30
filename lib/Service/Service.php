<?php

namespace DTL\MVC\Service;

class Service
{
    protected $className;
    protected $args;
    protected $instance;
    protected $methodCalls = array();

    public static function create($className, $args)
    {
        return new Service($className, $args);
    }

    public function __construct($className, $args)
    {
        $this->className = $className;
        $this->args = $args;
    }

    public function getInstance()
    {
        if ($this->instance) {
            return $this->instance;
        }

        $reflectionClass = new \ReflectionClass($this->className);
        $instance = $reflectionClass->newInstanceArgs($this->getRealArgs($this->args));

        foreach ($this->methodCalls as $method => $args) {
            $args = $this->getRealArgs($args);
            call_user_func_array(array($instance, $method), $args);
        }

        $this->instance = $instance;

        return $this->getInstance();
    }

    public function addMethodCall($method, $args)
    {
        $this->methodCalls[$method] = $args;
        return $this;
    }

    protected function getRealArgs($args)
    {
        $realArgs = array();

        foreach ($args as $arg) {
            if ($arg instanceOf Service) {
                $realArgs[] = $arg->getInstance();
            } else {
                $realArgs[] = $arg;
            }
        }

        return $realArgs;
    }
}
