<?php
/*
 * Copyright (C) Solutions By Design - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited 
 * Proprietary and confidential
 */

/**
 * CORSConfig
 * 
 * @package RESTFramework
 * @author Jeff Pizano <jeff@jeffpizano.com>
 * @copyright Copyright (c) 2014, Jeff Pizano
 */
class CORSConfig {
    protected $allowed_origins = array();
    protected $allowed_methods = array();
    protected $allowed_headers = array();
    protected $max_age = 3600;

    public function __construct($allowedOrigins = array(), $allowedMethods = array(), $allowedHeaders = array(), $maxAge = 3600) {
        $this->allowed_origins = $allowedOrigins;
        $this->allowed_methods = $allowedMethods;
        $this->allowed_headers = $allowedHeaders;
        $this->max_age = $maxAge;
    }

    public function addAllowedOrigin($origin) {
        array_push($this->allowed_origins, $origin);
    }

    public function addAllowedMethod($method) {
        array_push($this->allowed_methods, $method);
    }

    public function addAllowedHeader($header) {
        array_push($this->allowed_headers, $header);
    }

    public function setMaxAge($maxage) {
        $this->max_age = $maxage;
    }

    public function handleCORS() {
        if($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            if(isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']) && isset($_SERVER['HTTP_ORIGIN'])) {
                if(empty($this->allowed_origins)) {
                    header("Access-Control-Allow-Origin: *");
                } else {
                    header("Access-Control-Allow-Origin: " . implode(', ', $this->allowed_origins));
                }
                
                if(empty($this->allowed_methods)) {
                    header("Access-Control-Allow-Methods: *");
                } else {
                    header("Access-Control-Allow-Methods: " . implode(', ', $this->allowed_methods));
                }
                
                if(!empty($this->allowed_headers)) {
                    header("Access-Control-Allow-Headers: " . implode(', ', $this->allowed_headers));
                }

                header("Access-Control-Max-Age: " . $this->max_age);
            }
            exit;
        }
        
        header("Access-Control-Allow-Origin: " . implode(', ', $this->allowed_origins));
        // header("Access-Control-Allow-Methods: " . implode(', ', $this->allowed_methods));
    }
}