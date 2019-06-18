<?php
/**
 * Created by PhpStorm.
 * User: kristengarnier
 * Date: 2019-02-28
 * Time: 14:59
 */

namespace controller;

use app\src\App;

abstract class Controllerbase
{
    /**
     * @var \CityModel
     */
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    protected function redirect($location) {
        header("Location: $location");
        exit();
    }
}