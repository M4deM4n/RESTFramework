<?php
/**
 * Endpoint
 * 
 * This is an abstract class providing functionality to get/set HTTP response
 * codes and messages. This class also contains a helper message to handle
 * responses for HTTP Request Methods that are not supported.
 * 
 * @package RESTFramework
 * @author Jeff Pizano <jeff@jeffpizano.com>
 * @copyright Copyright (c) 2014, Jeff Pizano
 */
abstract class Endpoint
{
    private $verbHandle = array();
    
    
    /**
     * Contains the numeric HTTP Response Code.
     * 
     * @var int $responseCode 
     */
    private $responseCode = 200;
    
    /**
     * Contains the HTTP response that will be serialized into a JSON string.
     * 
     * @var mixed $response 
     */
    private $response;
    
    
    /**
     * 
     */
    public function __construct() 
    {
        
    }
    
    
    protected function processVerb()
    {
        if($this->isVerb(Request::$verb))
            return $this->${Request::$verb}();
    }
    
    /**
     * 
     * @param type $methodName
     * @return type
     * @throws Exception
     */
    protected function registerVerb($methodName)
    {
        if(is_string($methodName))
        {
            array_push($this->verbHandle, $methodName);
            return;
        }
        
        $message  = __CLASS__.'::'.__METHOD__;
        $message .= ' - Verbs must be strings.';
        throw new Exception($message);
    }
    
    
    /**
     * 
     * @param type $verb
     */
    protected function isVerb($verb)
    {
        if(in_array($verb, $this->verbHandle) && method_exists($this, $verb))
                return true;
        
        return false;
    }
    
    
    /**
     * 
     * @return type
     */
    protected function hasParameters()
    {
        return !empty(Request::$args);
    }
    
    /**
     * This method returns the numeric HTTP Response Code.
     * 
     * @return int Http Response Code.
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }
    
    
    /**
     * This method returns the HTTP response that will be serialized into a 
     * JSON string.
     * 
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }
    
    
    /**
     * This method sets the HTTP Response to the current request.
     * 
     * @param type $response
     * @return void
     */
    protected function setResponse($response)
    {
        $this->response = $response;
    }
    
    
    /**
     * This method sets the HTTP Response Code for the current request.
     * 
     * @param type $code
     * @return void
     */
    protected function setResponseCode($code = 200)
    {
        $this->responseCode = $code;
    }
    
    
    /**
     * (Optional)
     * This method sets the HTTP Response and HTTP Response code for instances
     * where a Request Method is not supported.
     * 
     * @return void
     */
    protected function methodNotSupported()
    {
        $this->response  = 'This endpoint does not support the ';
        $this->response .= Request::$method . ' request method.';

        $this->setResponse($this->response);
        $this->setResponseCode(405);
    }
}