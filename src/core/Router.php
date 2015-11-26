<?php
/**
 * Created by PhpStorm.
 * User: Otas
 * Date: 24. 11. 2015
 * Time: 22:30
 */

namespace core;

use core\Route;
use Exception;

class Router
{
    protected static $routes = array();

    public static function __callStatic($name, $arguments)
    {
        if(is_object($arguments[1])){
            call_user_func($arguments[1]);
            return;
        }

        array_push(self::$routes, new Route($arguments[0],  $name, $arguments[1][0], $arguments[1][1]));
    }

    public static function printRoutes(){
        foreach(self::$routes as $route){
            echo("<br/>====<br/>".$route->getControllerPath()."<br/>".$route->getController()."<br/>".$route->getAction()
                ."<br/>".$route->getActionMethod()."<br/>");
            print_r($route->getActionParams());
        }
    }

    public static function Dispatch(Request $request){
        $url = $request->getUrl();
        $method = $request->getMethod();
        if(!isset($url[0])){
            #defaultni hodnoty
            return;
        }

        foreach(self::$routes as $route) {
            # zjisteni jestli je funkce get/post
            $route_method = strtolower($route->getActionMethod());
            # metoda vyzadana uzivatelem
            $request_method = strtolower($request->getMethod());
            # cesta v route
            $route_path = explode('/', $route->getControllerPath());


            if($route_method != $request_method){
                continue;
            }

            if ($request_method == 'post'){
                if((sizeof($url) - sizeof($route_path)) != 0){
                    continue;
                }
            }else{
                if ((sizeof($url) - sizeof($route_path) - sizeof($route->getActionParams())) != 0) {
                    continue;
                }
            }
            # ukrojeni parametru z url
            $url_slice = array_slice($url, 0, sizeof($route_path));

            # porovnani url a metody requestu s routou
            if($url_slice == $route_path){
                #routa odpovidajici url

                # pole parametru v predpisu routy
                $params = $route->getActionParams();
                # pole parametru zadanych uzivatelem
                if($request_method == 'post'){
                    $usr_params = $_POST;
                    foreach($_POST as $item){
                        array_push($usr_params, $item);
                    }
                    unset($_POST);
                }else {
                    $usr_params = array_slice($url, sizeof($route_path));
                }
                # counter
                $i = 0;
                foreach($params as $param){
                    # jsou vyzadovana pouze cisla
                    if(strtolower($param) == 'int'){
                        if(!is_numeric($usr_params[$i])){
                            throw new Exception('Neplatne hodnoty.');
                            die();
                        }
                        # jsou vyzadovana pouze pismena
                    }elseif(strtolower($param) == 'str'){
                        if(!preg_match('/[a-zA-z]/', $usr_params[$i])){
                            throw new Exception('Neplatne hodnoty.');
                            die();
                        }
                    }
                    $i++;
                }

                $controller_path = 'controllers\\'.$route->getController();

                if(!class_exists($controller_path)){
                    throw new Exception('Stranka nenalezena');
                    die();
                }

                $controller = new $controller_path;

                if(is_callable(array($controller, $route->getAction()))){
                        call_user_func_array(array($controller, $route->getAction()), $usr_params);
                }

                # controller s akci byl zavolan...
            }
        }
    }
}