<?php

/**
 * AutoLoader
 * 
 * This class is used to handle the auto-loading of objects based on definitions
 * defined by the user.
 * 
 * @package RESTFramework
 * @author Jeff Pizano <jeff@jeffpizano.com>
 */
class AutoLoader
{
    /**
     * Contains a list of needles to search for within a class name.
     * 
     * @var array $classType
     */
    protected $classType = array();
    
    /**
     * Contains a list of paths to search for classes.
     * 
     * @var array $classPath
     */
    protected $classPath = array();
    
    /**
     * Contains a list of extensions to search for classes.
     * 
     * @var array $classExtension
     */
    protected $classExtension = array();
    
    
    /**
     * This method is the primary entry point for this class. It's only purpose
     * is to register this classes `autoLoad` method as a PHP autoloader.
     * 
     */
    public function __construct()
    {
        spl_autoload_register(array($this, 'autoload'));
    }


    /**
     * This method is used to define needles to search for within class names
     * and define their associated path.
     *
     * @todo Write better description of this method.
     *
     * @param string $suffix
     * @param string $path
     * @param string $extension
     */
    public function lookFor($suffix, $path, $extension = '.class.php')
    {
        if(!array_key_exists($suffix, $this->classType)) {
            array_push($this->classType, $suffix);
        }
        
        $this->classPath[$suffix] = $path;
        $this->classExtension[$suffix] = $extension;
    }
    
    
    /**
     * This method will be registered as a PHP autoloading function. This method
     * will parse classnames for defined needles and attempt to construct a full
     * working path in order to load the file.
     * 
     * @param string $className
     * @return void
     */
    private function autoLoad($className)
    {
        $limit = count($this->classType);
        for($i = 0; $i < $limit; $i++)
        {
            $classType = $this->classType[$i];
            if(strpos($className, $classType) === FALSE) { continue; }
                    
            $filePath = $this->classPath[$classType];
            $filePath .= strtolower(str_replace($classType, "", $className) . $this->classExtension[$classType]);
            
            include_once($filePath);
            return;
        }
    }
}