<?php
namespace app\common\column;

use app\common\column\Text;

class Column
{
    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        $name = ucfirst($name);
        $class = __NAMESPACE__.'\\'.$name;
        if(class_exists($class)){
            return new $class($arguments[0], isset($arguments[1]) ? $arguments[1] : '');
        }
    }
}