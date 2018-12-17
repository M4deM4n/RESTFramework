<?php
/**
 * bootstrap.php
 * 
 * This file's purpose is simply to load required assets for the RESTFramework
 * to funciton properly.
 * 
 * @package RESTFramework
 * @author Jeff Pizano <jeff@jeffpizano.com>
 * @copyright Copyright (c) 2014, Jeff Pizano
 */


// REQUIRE RUNTIME CONFIGURATION
require_once(REST_FRAMEWORK.'runconfig/request.class.php');

// REQUIRE CONFIGURATION
require_once(REST_FRAMEWORK.'class/corsconfig.class.php');

// REQUIRE INTERFACES
require_once(REST_FRAMEWORK.'interface/authhandler.class.php');
require_once(REST_FRAMEWORK.'interface/responsecodetranslator.class.php');

// REQUIRE ABSTRACT CLASSES
require_once(REST_FRAMEWORK.'abstract/endpoint.abstract.php');
require_once(REST_FRAMEWORK.'abstract/restframework.abstract.php');

// REQUIRE API UTILITY CLASSES
require_once(REST_FRAMEWORK.'class/autoloader.class.php');
require_once(REST_FRAMEWORK.'class/defaulttranslator.class.php');
require_once(REST_FRAMEWORK.'class/contenttype.class.php');