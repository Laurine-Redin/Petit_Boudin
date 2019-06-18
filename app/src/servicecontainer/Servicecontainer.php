<?php
/**
 * Created by PhpStorm.
 * User: Romuald
 * Date: 09/04/2019
 * Time: 17:45
 */

namespace app\src\servicecontainer;

class Servicecontainer
{
    /**
     * Contains all service of php app
     * @var array
     */
    private $container = array();

    /**
     * Get a service int the container
     *
     * @param string $serviceName Name of the service to create in the container
     * @return mixed
     */
    public function get(string $serviceName)
    {
        return $this->container[$serviceName];
    }

    /**
     * create a service in the container
     *
     * @param string $name Name of the service to retrieve
     * @param $assigned Value associated to the service (can be any type)
     */
    public function set(string $name, $assigned)
    {
        $this->container[$name] = $assigned;
    }

    /**
     * unset a service in the container
     *
     * @param string $name Name of the service to unset in the container
     */
    public function unset(string $name)
    {
        unset($this->container[$name]);
    }
}