<?php
/**
 * Request
 * 
 * This is a static class that contains properties about a HTTP Request as it
 * pertains to this RESTFramework. The purpose of this class is simply to
 * provide globally accessable properties without polluting the global 
 * namespace.
 * 
 * @package RESTFramework
 * @author Jeff Pizano <jeff@jeffpizano.com>
 * @copyright Copyright (c) 2014, Jeff Pizano
 */
class Request
{
    /**
     * This property contains the HTTP Request Method.
     * 
     * @var string 
     */
    public static $method;
    
    /**
     * This property contains the requested Endpoint.
     * 
     * @var string
     */
    public static $endpoint;

    /**
     * This property contains a list of any parameters not defined as an
     * endpoint or verb.
     * 
     * @var array 
     */
    public static $args;
}