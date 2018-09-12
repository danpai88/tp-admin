<?php
namespace app\common\display;

use app\common\form\Input;
use app\common\form\Select;

class Base
{
    public static $handle = null;
    public $menus = [];

    /**
     * @param $model
     * @return Input
     * @return Select
     */
    public static function model($model)
    {
        if(is_null(static::$handle)){
            static::$handle = new static();
        }

        static::$handle->model = $model;

        return static::$handle;
    }

    public function title($title = '')
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getMenus()
    {
        $this->menus = model('CyMenus')->where('pid', 0)->order('order asc')->select();
        foreach ($this->menus as $key => $menu) {
            $this->menus[$key]['items'] = model('CyMenus')->where('pid', $menu['id'])->order('order asc')->select();
        }
        return $this->menus;
    }
}