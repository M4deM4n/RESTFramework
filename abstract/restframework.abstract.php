<?php
error_reporting(0);
/**
 * API
 * 
 * This is an abstract class that handles all API transactions along with token 
 * validation.
 * 
 * @package RESTFramework
 * @author Jeff Pizano <jeff@jeffpizano.com>
 * @copyright Copyright (c) 2014, Jeff Pizano
 */
abstract class RESTFramework
{
    
    const CONTENT_TYPE_PLAIN    = 'Content-Type: text/plain';
    const CONTENT_TYPE_HTML     = 'Content-Type: text/html';
    const CONTENT_TYPE_JSON     = 'Content-Type: text/json';
    const CONTENT_TYPE_XML      = 'Content-Type: text/xml';
    const CONTENT_TYPE_STL      = 'Content-Type: application/vnd.ms-pkistl';
    const CONTENT_TYPE_PDF      = 'Content-Type: application/pdf';
    const CONTENT_TYPE_PNG      = 'Content-Type: image/png';
    const CONTENT_TYPE_JPG      = 'Content-Type: image/jpg';
    
    
    /**
     *
     * @var type 
     */
    protected $contentType = self::CONTENT_TYPE_JSON;
    
    
    /**
     * The name of the current Endpoint.
     * 
     * @var String
     */
    private $authEndpoint;
    
    
    /**
     * Dictates whether or not to use a supplied auth handler.
     * 
     * @var boolean
     */
    private $useAuthHandler = false;
    
    
    /**
     * Must implement the AuthHandler interface.
     * 
     * @var Object
     */
    protected $authHandler;
    
    
    /**
     * Must implement the ResponseCodeTranslator interface.
     * @var Object 
     */
    protected $translator;
    
    
    /**
     * An instance of the AutoLoader class used for dynamically loading php
     * assets.
     * 
     * @var AutoLoader 
     */
    protected $autoloader;
    
    
    /**
     * An indexed array of strings representing valid API endpoints.
     * 
     * @var Array Valid Endpoint list
     */
    private $endpointList = array();
    
    
    /**
     * An indexed array of strings representing valid API endpoints that 
     * requires no authentication.
     * 
     * @var type 
     */
    private $openEndpointList = array();
    
    
    /**
     * A numeric HTTP Response Code.
     * 
     * @var int 
     */
    private $httpResponseCode = 200;
    
    
    /**
     * If enabled it will allow for a response on error.
     * @var boolean
     */
    protected $respondOnError = false;

    
    /**
     * If enabled it will format JSON strings with line breaks.
     * @var bool
     */
    protected $prettyPrint = false;
    
    
    /**
     * Primary entry point for the API base class. This method is used to 
     * initialize any Runtime Configuration properties, and instantiate the
     * AutoLoader class.
     * 
     * @param array $request
     */
    public function __construct($request, $CORSConfig = null) 
    {
        if(!is_null($CORSConfig) && ($CORSConfig instanceof CORSConfig)) {
            $CORSConfig->handleCORS();
        }

        Request::$method    = strtoupper($_SERVER['REQUEST_METHOD']);
        Request::$args      = $this->getArgs($request);
        Request::$endpoint  = $this->getEndPoint();

        $this->contentType = self::CONTENT_TYPE_JSON;
        $this->autoloader = new AutoLoader();
        $this->translator = new DefaultTranslator();
    }
    
    
    /**
     * This method pushes a new String to the array of valid API endpoints. Any
     * non string parameter will cause this method to throw an exception.
     * 
     * @param String $endpoint
     * @return void
     * @throws Exception
     */
    protected function addEndpoint($endpoint)
    {
        if(is_string($endpoint)) {
            array_push($this->endpointList, $endpoint);
            return;
        }
        
        $message = __CLASS__.'::'.__METHOD__.' - ';
        $message .= 'Expected String';
        throw new Exception($message);
    }
    
    
    /**
     * 
     * @param type $endpoint
     */
    protected function addOpenEndpoint($endpoint)
    {
        if(is_string($endpoint)) {
            array_push($this->endpointList, $endpoint);
            array_push($this->openEndpointList, $endpoint);
            return;
        }
        
        $message = __CLASS__.'::'.__METHOD__.' - ';
        $message .= 'Expected String';
        throw new Exception($message);
    }
    
    
    /**
     * This method returns a boolean true if the specified endpoint is an "open"
     * endpoint, meaning that it does NOT require authentication.
     * 
     * @param type $endpoint
     * @return type
     */
    protected function isOpen($endpoint)
    {
        return in_array($endpoint, $this->openEndpointList);
    }
    
    
    /**
     * This method splits a request into multiple arguments.
     * 
     * @param String $request
     * @return array
     */
    private function getArgs($request)
    {
        return explode('/', rtrim($request, '/'));
    }
    
    
    /**
     * This method returns the first element from a request and designates it
     * as a potential API Endpoint.
     * 
     * @return String
     */
    private function getEndPoint()
    {
        return array_shift(Request::$args);
    }

    
    /**
     * This is an optional method that when called will force the REST API to 
     * use the provided AuthHandler object for token validation prior to 
     * executing a request on an endpoint.
     * 
     * @param AuthHandler $object
     * @param String $endpointOverride The Authentication endpoint to be ignored.
     * @return void
     * @throws Exception If object doesn't implement AuthHandler
     */
    protected function registerAuthHandler($object, $endpointOverride)
    {
        if(($object instanceof AuthHandler) && is_string($endpointOverride))
        {
            $this->useAuthHandler = true;
            $this->authEndpoint = $endpointOverride;
            $this->authHandler = $object;
            return;
        }
        
        $msg  = __CLASS__.'::'.__METHOD__.' - ';
        $msg .= 'Object must implement AuthHandler, and endpoint must be a ';
        $msg .= 'string.';
        throw new Exception($msg);
    }


    /**
     * @param $object
     * @throws Exception
     */
    protected function registerTranslator($object)
    {
        if(($object instanceof ResponseCodeTranslator))
        {
            $this->translator = $object;
            return;
        }
        
        $msg  = __CLASS__.'::'.__METHOD__.' - ';
        $msg .= 'Object must implement ResponseCodeTranslator.';
        throw new Exception($msg);
    }
    
    
    /**
     * This method returns the currently defined HTTP Response Code.
     * 
     * @return int
     */
    protected function getResponseCode()
    {
        return $this->httpResponseCode;
    }
    
    
    /**
     * This method sets the servers HTTP Response code.
     * 
     * @param int $code
     * @throws Exception Thrown if parameter is not numeric.
     */
    protected function setResponseCode($code)
    {
        if(is_numeric($code))
        {
            $this->httpResponseCode = $code;
            return;
        }
        
        $msg  = __CLASS__ . '::' . __METHOD__;
        $msg .= ' - Invalid Type, expected integer.';
        throw new Exception($msg);
    }
    
    
    /**
     * This method executes the API using the registered AuthHandler if 
     * provided. This method also handles the endpoint validation and responds
     * appropriately if an invalid endpoint has been requested.
     * 
     * @return void
     */
    public function run()
    {
        $method = Request::$endpoint;
        
        // USE AUTH HANDLER IF AVAILABLE
        if(
            $this->useAuthHandler && 
            !in_array($method, $this->openEndpointList) &&
            $method != $this->authEndpoint && 
            !$this->authHandler->isAuthenticated()
        ) {
            $this->sendResponse($this->translateCode(401), 401);
            return;
        }
        
        // EXECUTE ENDPOINT METHOD
        if(in_array($method, $this->endpointList) && method_exists($this, $method)) {
            $data = $this->$method();
            $code = $this->getResponseCode();

            $this->sendResponse($data, $code);
            return;
        }

        // FAIL ON BAD ENDPOINT
        $this->sendResponse($this->translateCode(404), 404);
    }
    
    
    /**
     * This method sets the appropriate headers based on the request and writes
     * the response data to stdout in a JSON encoded string.
     * 
     * @param string $data
     * @param int $status
     */
    protected function sendResponse($data, $status = 200)
    {
        header("HTTP/1.1 " . $status . " " . $this->translateCode($status));

        if($status == 200 || $status == 201 || $this->respondOnError) {
            echo $this->renderResponse($data);
        }
    }


    /**
     * @param $data
     * @return string
     */
    protected function renderResponse($data)
    {
        switch($this->contentType)
        {
            case self::CONTENT_TYPE_HTML:
            case self::CONTENT_TYPE_JPG:
            case self::CONTENT_TYPE_PDF:
            case self::CONTENT_TYPE_PLAIN:
            case self::CONTENT_TYPE_PNG:
            case self::CONTENT_TYPE_STL:
            case self::CONTENT_TYPE_XML:
                header($this->contentType);
                return $data;
            
            case self::CONTENT_TYPE_JSON:
                header($this->contentType);
                return json_encode($data,($this->prettyPrint ? JSON_PRETTY_PRINT : 0));
            
            default:
                return $data;
        }
    }
    
    
    /**
     * This method takes a numeric HTTP response code and provides a custom 
     * string representation of that response code's meaning.
     * 
     * @todo This method should be able to be customized externally for user
     * created translations.
     * 
     * @param int $responseCode
     * @return String
     */
    private function translateCode($responseCode)
    {
        return $this->translator->translateCode($responseCode);
    }
}