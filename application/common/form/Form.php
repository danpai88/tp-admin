<?php
namespace app\common\form;

/**
 * Class Form
 * @package app\common\form
 *
 * 魔术方法
 *
 * @method static Input     input($id, $label = '')
 * @method static Hidden    hidden($id, $label = '')
 * @method static Select    select($id, $label = '')
 * @method static Datetime  datetime($id, $label = '')
 * @method static Editor    editor($id, $label = '')
 * @method static Textarea  textarea($id, $label = '')
 */

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