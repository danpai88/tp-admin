<?php
namespace app\common\display;

use app\common\form\Input;
use app\common\form\Select;
use think\facade\Request;

class FormDisplay
{
    public static $handle = null;

    public $forms = [];
    public $model = '';
    public $data = [];
    public $title = '';

    /**
     * @param $model
     * @return Input
     * @return Select
     */
    public static function model($model)
    {
        if(is_null(static::$handle)){
            static::$handle = new self();
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
     * @param $callback
     * @throws \think\exception\DbException
     * @return $this
     */
    public function callback($callback)
    {
        call_user_func($callback, $this);

        $model = model($this->model);
        $pk = $model->getPk();

        if(Request::get($pk)){
            $this->data = $model->find(Request::get($pk));
        }

        return view('display/form', ['instance' => $this]);
    }
}