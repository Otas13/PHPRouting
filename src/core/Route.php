<?php
/**
 * Created by PhpStorm.
 * User: Otas
 * Date: 24. 11. 2015
 * Time: 22:30
 */

namespace core;


class Route
{
    protected $controller_path;
    protected $action_params;
    protected $action_method;
    protected $controller;
    protected $action;

    public function __construct($pattern, $action_method, $controller, $action){

        $this->action_method = $action_method;
        $this->controller = $controller;
        $this->action = $action;

        $regex_path = explode('/', $pattern);

        $params = array();

        $i = 0;
        foreach($regex_path as $item){
            if(preg_match('/{([a-z][^}]*)}/', $item)){
                $item = str_replace('{', '', $item);
                $item = str_replace('}', '', $item);
                array_push($params, $item);
                unset($regex_path[$i]);
            }
            $i++;
        }
        $this->path = implode('/', $regex_path);
        $this->action_params = $params;
    }

    /**
     * @return mixed
     */
    public function getActionMethod()
    {
        return $this->action_method;
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return mixed
     */
    public function getControllerPath()
    {
        return $this->path;
    }

    /**
     * @return array
     */
    public function getActionParams()
    {
        return $this->action_params;
    }
}