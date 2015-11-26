<?php
/**
 * Created by PhpStorm.
 * User: Otas
 * Date: 25. 11. 2015
 * Time: 0:09
 */

namespace core;


class Request
{
    protected $url;
    protected $method;

    public function __construct()
    {
        if(isset($_GET['url'])){
            $this->url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }

        $this->method = $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @return array
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }
}