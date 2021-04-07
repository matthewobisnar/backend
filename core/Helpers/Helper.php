<?php
namespace Core\Helpers;

class Helper 
{
    public static function postRequest() {
    
        if (isset($_SERVER['CONTENT_TYPE']) 
        && $_SERVER['CONTENT_TYPE'] == 'application/json') {
            
           return json_decode(
                file_get_contents('php://input'), true
            );
    
        } else {
            return $_POST;
        }

    }

    public static function getRequest()
    {
        return $_GET;
    }
    
    public static function parseConfig($string)
    {
        return json_decode($string, 1);
    }

    public static function className($object)
    {
        return (new \ReflectionClass($object))->getShortName();
    }

    public static function getArrayValue($arr, $key)
    {   
        if (!empty($arr[$key])) {
            if (is_array($arr[$key])) {
                return (array) $arr[$key];
            } else {
                return $arr[$key] ?? null;
            }
        }

        return null;
    }

    public static function response()
    {
        ob_clean();
        ob_flush();

        var_dump(func_get_args());
        
        die(json_encode(func_get_args()));
    }
}