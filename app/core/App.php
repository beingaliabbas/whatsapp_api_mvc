<?php
namespace App\Core;

class App {
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];
    
    public function __construct() {
        $url = $this->parseUrl();
        
        // Controller setup
        if(isset($url[0])) {
            $controllerName = ucfirst($url[0]).'Controller';
            if(file_exists('../app/controllers/'.$controllerName.'.php')) {
                $this->controller = $controllerName;
                unset($url[0]);
            }
        }
        
        // Include controller file
        require_once '../app/controllers/'.$this->controller.'.php';
        
        // Instantiate controller
        $controllerClass = "App\\Controllers\\".$this->controller;
        $this->controller = new $controllerClass;
        
        // Method setup
        if(isset($url[1])) {
            if(method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }
        
        // Parameters
        $this->params = $url ? array_values($url) : [];
        
        // Call controller method
        call_user_func_array([$this->controller, $this->method], $this->params);
    }
    
    protected function parseUrl() {
        if(isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
    }
}