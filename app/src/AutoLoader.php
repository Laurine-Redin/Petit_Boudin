<?php
/**
 * Created by PhpStorm.
 * User: Romuald
 * Date: 09/04/2019
 * Time: 16:20
 */
namespace app\src;

class AutoLoader
{
    /**
     * Met en place les différents autoloader de l'app php
     */
    public static function register()
    {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    public static function autoload(string $class)
    {
        $nameSpace = explode('\\', $class);
        $class = implode('/', $nameSpace);
        require_once $_SERVER['DOCUMENT_ROOT'] . ‘/‘ . $class.'.php'; 
    }
}