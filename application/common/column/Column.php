<?php
namespace app\common\column;

/**
 * 魔术方法
 *
 * @method static Text      text($field, $label = '')
 * @method static Label     label($field, $label = '')
 * @method static Img       img($field, $label = '')
 * @method static Link      link($field, $label = '')
 * @method static Datetime  datetime($field, $label = '')
 */

class Column
{
    /**
     * 静态方法
     * @param string $name
     * @param array $arguments
     * @method static Text text($field, $label = '')
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