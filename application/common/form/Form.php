<?php
namespace app\common\form;

class Form
{
    public static function __callStatic($name, $arguments)
    {
        $name = ucfirst($name);
        $class = __NAMESPACE__.'\\'.$name;
        if(class_exists($class)){
            return new $class($arguments[0], isset($arguments[1]) ? $arguments[1] : '');
        }
    }
}