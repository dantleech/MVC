<?php

namespace DTL\MVC\Controller;

/**
 * @author Daniel Leech <daniel@dantleech.com> 
 */
class Request
{
    public $params = array();
    protected $server = array();

    public function __construct($getParams = array(), $postParams = array(), $serverParams = array())
    {
        $params = array();
        array_merge($params, $getParams);
        array_merge($params, $postParams);
        $this->params = $params;
        $this->server = $serverParams;
    }

    public static function createFromGlobals()
    {
        return new Request($_GET, $_POST, $_SERVER);
    }

    public function getPathInfo()
    {
        if (!isset($this->server['PATH_INFO'])) {
            return '/';
        } else {
            return $this->server['PATH_INFO'];
        }
    }

    public function mergeParams($params = array())
    {
        array_merge($this->params, $params);
    }
}
