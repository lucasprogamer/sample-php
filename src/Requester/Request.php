<?php

namespace Src\Requester;


class Request
{


    protected $files;
    protected $uri;
    protected $method;
    protected $protocol;
    protected $data = [];

    public function __construct()
    {

        $this->uri  = $_SERVER['REQUEST_URI'] ?? '/';
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->protocol = isset($_SERVER["HTTPS"]) ? 'https' : 'http';
        $this->setData();

        if (count($_FILES) > 0) {
            $this->setFiles();
        }
    }

    protected function setData()
    {
        switch ($this->method) {
            case 'POST':
                if (!empty($_POST)) {
                    $this->data = $_POST;
                    break;
                }
                $this->data = json_decode(file_get_contents('php://input'), true);
                break;
            case 'GET':
                $this->data = $_GET;
                break;
            case 'HEAD':
            case 'PUT':
            case 'DELETE':
            case 'OPTIONS':
                $this->data = json_decode(file_get_contents('php://input'), true);
        }
    }

    protected function setFiles()
    {
        foreach ($_FILES as $key => $value) {
            $this->files[$key] = $value;
        }
    }

    public function base()
    {
        return $this->base;
    }

    public function method()
    {

        return $this->method;
    }

    public function uri()
    {
        return $this->uri;
    }

    public function all()
    {
        return $this->data;
    }

    public function __isset($key)
    {
        return isset($this->data[$key]);
    }

    public function __get($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }
    }

    public function hasFile($key)
    {

        return isset($this->files[$key]);
    }

    public function file($key)
    {

        if (isset($this->files[$key])) {
            return $this->files[$key];
        }
    }
}
