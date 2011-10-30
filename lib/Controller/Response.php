<?php

namespace DTL\MVC\Controller;

class Response
{
    protected $body;
    protected $headers;
    protected $statusCode;

    public function __construct($statusCode = 200, $headers = array(), $body = null)
    {
        $this->statusCode = $statusCode;
        $this->body = $body;
        $this->headers = $headers;
    }

    public static function create($statusCode = null, $headers = null, $body = null)
    {
        return new Response($statusCode, $headers, $body);
    }

    public function setStatusCode($code)
    {
        $this->statusCode = $code;
        return $this;
    }

    public function setHeader($header, $value)
    {
        $this->headers[$header] = $value;
        return $this;
    }

    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    public function send()
    {
        header('HTTP/1.0 '.$this->statusCode);
        foreach ($this->headers as $header => $value) {
            header(strtoupper($header).': '.$value);
        }
        echo $this->body;
    }
}
