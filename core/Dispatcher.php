<?php
namespace Core;

class Dispatcher
{
    public $server;
    public $request;

    public function __construct($server)
    {
        $this->server = $server;

    }

    public function run()
    {
        $request = trim($this->server['REQUEST_URI'], "/");

        if (empty($request)) $this->classNotFound();

        list($controller, $action) = explode("/", $request);
        
        $controller = $this->camelCase($controller);
        $action = "action" . $this->camelCase($action);
        $className = "\\Api\\Controllers\\{$controller}";

        if (class_exists($className)) {
            
            $class = new $className();

            if (is_callable(array($class, $action))) {
                return $class->{$action}();
            } 
        }
        
        $this->classNotFound();

    }

    public function camelCase($action)
    {
        return str_replace(" ", "", ucwords(preg_replace("/[-_]/", " ", $action)));
    }

    public function classNotFound()
    {
        header("HTTP/1.0 404 Not Found");

        ob_clean();
        ob_flush();

        die(json_encode([
            "status" => http_response_code(404),
            "description" => " Class Not Found."
        ]));
    }

}