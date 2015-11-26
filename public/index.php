<?php
/**
 * Created by PhpStorm.
 * User: Otas
 * Date: 24. 11. 2015
 * Time: 22:26
 */

require_once'../vendor/autoload.php';

use core\Router;
use core\Request;

Router::get('home/hovno/{id}/{user}', array('home_controller', 'index'));
Router::get('home/users/neco/nevim/{id}/{user}/{gender}', array('user_controller', 'index'));
Router::post('home/{str}/{int}', array('home', 'index'));

Router::printRoutes();

$r = new Request();
Router::Dispatch($r);
?>
<form action="home" method="post">
    <input type="text" name="hovno">
    <input type="text" name="hovdsdno">
    <input type="submit">
</form>
