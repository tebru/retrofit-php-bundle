<?php
/**
 * File ServicesCollection.php 
 */

namespace Tebru\RetrofitBundle;

/**
 * Class ServicesCollection
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ServicesCollection
{
    /**
     * A collection of service class names
     *
     * @var array $services
     */
    private $services = [];

    /**
     * Add a service
     * @param string $service
     */
    public function add($service)
    {
        $this->services[] = $service;
    }

    /**
     * Get collection of services
     *
     * @return array
     */
    public function getServices()
    {
        return $this->services;
    }
}
