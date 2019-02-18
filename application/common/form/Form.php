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
 * @method static Password  password($id, $label = '')
 * @method static Alink     alink($id, $label = '')
 */

class Form
{
	public static $resources = [
		"/admin-lte/bower_components/bootstrap/dist/css/bootstrap.min.css",
		"/admin-lte/bower_components/font-awesome/css/font-awesome.min.css",
		"/admin-lte/bower_components/Ionicons/css/ionicons.min.css",
		"/admin-lte/dist/css/AdminLTE.min.css",
		"/admin-lte/dist/css/skins/_all-skins.min.css",
		"https://cdn.staticfile.org/jquery/3.3.1/jquery.min.js",
		"/admin-lte/bower_components/jquery-ui/jquery-ui.min.js",
		"/admin-lte/bower_components/bootstrap/dist/js/bootstrap.min.js",
		"/admin-lte/bower_components/fastclick/lib/fastclick.js",
		"/admin-lte/dist/js/adminlte.min.js",
	];

    public static function __callStatic($name, $arguments)
    {
        $name = ucfirst($name);
        $class = __NAMESPACE__.'\\'.$name;
        if(class_exists($class)){
            return new $class($arguments[0], isset($arguments[1]) ? $arguments[1] : '');
        }
    }
}