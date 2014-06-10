<?php
/**
 * AuthHandler
 * 
 * This interface is provided to ensure that any registered AuthHandler supplies
 * a `isAuthenticated` method.
 * 
 * @package RESTFramework
 * @author Jeff Pizano <jeff@jeffpizano.com>
 * @copyright Copyright (c) 2014, Jeff Pizano
 */
interface AuthHandler
{
    /**
     * This method should return a boolean value
     * 
     * @return boolean
     */
    public function isAuthenticated();
}