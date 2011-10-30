<?php

namespace DTL\MVC\Service;

class ServiceContainer
{
    protected $services;

    public function set($name, Service $service)
    {
        $this->services[$name] = $service;
    }

    public function get($name)
    {
        if (!isset($this->services[$name])) {
            throw new \Exception('Service "'.$name.'" does not exist.');
        }

        return $this->services[$name]->getInstance();
    }
}
