<?php
/**
 * AuthHandler
 * 
 * This interface is provided to ensure that any registered Translator supplies
 * a `translateCode` method.
 * 
 * @package RESTFramework
 * @author Jeff Pizano <jeff@jeffpizano.com>
 * @copyright Copyright (c) 2014, Jeff Pizano
 */
interface ResponseCodeTranslator
{
    /**
     * 
     * @param type $responseCode
     */
    public function translateCode($responseCode);
}