# RESTFramework
This is a simple REST framework I wrote for myself in PHP.

### Get Started
The "files" listed below demonstrate the bare minimum needed for this framework to function. 
I may provide a more complex demo in the future to demonstrate advanced functionality such as authentication.

**.htaccess**
```bash
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule (.*)$ index.php?request=$1 [QSA,NC,L]
</IfModule>
```

**index.php**
```php
<?php
define('REST_FRAMEWORK', '/path/to/RESTFramework/');
require_once(REST_FRAMEWORK . 'bootstrap.php');
include_once('myapi.class.php');

/**
 * The request parameter is populated by a rule in .htaccess. The .htaccess
 * rule takes the request URI, sans the domain name, and passes it as a
 * parameter named 'request' to this page.
 */
$request = (!empty($_REQUEST['request']) ? $_REQUEST['request'] : '');

/**
 * Run your API
 */
$MyAPI = new MyAPI($request);
$MyAPI->run();
```

**myapi.class.php**
```php
<?php
class MyAPI extends RESTFramework {

    public function __construct($request)
    {
        // Parent constuctor must be called first.
        parent::__construct($request);

        // Create an endpoint. The string parameter must match a function name.
        $this->addEndpoint('hello');
    }

    // Endpoint function
    public function hello()
    {
        $this->contentType = self::CONTENT_TYPE_PLAIN; // json is the default.
        return 'Hello world!';
    }
}

```
