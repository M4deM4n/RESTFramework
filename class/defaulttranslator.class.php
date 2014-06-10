<?php
/**
 * DefaultTranslator
 * 
 * @package RESTFramework
 * @author Jeff Pizano <jeff@jeffpizano.com>
 * @copyright Copyright (c) 2014, Jeff Pizano
 */
class DefaultTranslator implements ResponseCodeTranslator
{
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
                : $this->codeTranslation[500]);
    }
}