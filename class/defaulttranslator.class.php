<?php
/*
 * Copyright (C) Solutions By Design - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited 
 * Proprietary and confidential
 */

/**
 * DefaultTranslator
 * 
 * @package RESTFramework
 * @author Jeff Pizano <jeff@jeffpizano.com>
 * @copyright Copyright (c) 2014, Jeff Pizano
 */
class DefaultTranslator implements ResponseCodeTranslator
{
    const STATUS_OK = 200;
    const STATUS_BAD_REQUEST = 400;
    const STATUS_UNAUTHORIZED = 401;
    const STATUS_NOT_FOUND = 404;
    const STATUS_METHOD_NOT_ALLOWED = 405;
    const STATUS_INTERNAL_SERVER_ERROR = 500;
    const STATUS_NOT_IMPLEMENTED = 501;
    
    public $codeTranslation = array(
            200 => 'OK',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            404 => 'Endpoint Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented'
        );
    
    public function translateCode($responseCode) 
    {
        return (!empty($this->codeTranslation[$responseCode]) 
                ? $this->codeTranslation[$responseCode] 
                : $this->codeTranslation[self::STATUS_INTERNAL_SERVER_ERROR]);
    }
}