<?php
namespace app\common\form;

use think\facade\Request;

class Base
{
    public $value = '';
    public $placeholder = '';
    public $readonly = false;

    public $id = '';
    public $label = '';

    public $data = [];

    public $defaultValue = '';

    public $css = [];
    public $js = [];

    public function __construct($id, $label = '')
    {
        $this->id = $id;
        $this->label = $label === '' ? $id : $label;
        return $this;
    }

    public function value($value = '')
    {
        $this->value = $value;
        return $this;
    }

    public function defaultValue($value = '')
    {
        $this->defaultValue = $value;
        return $this;
    }

    public function render($data = [])
    {
        $tmp = explode('\\', get_class($this));
        $type = array_pop($tmp);

        if($this->value === ''){
            //优先从 get/post 中获取值
            if(Request::param($this->id)){
                $this->value(Request::param($this->id));
            }elseif(isset($data[$this->id])){
                //从数据库中 获取value
                $this->value($data[$this->id]);
            }else{
                $this->value = $this->defaultValue;
            }
        }

        return view('common@form/'.strtolower($type), ['instance' => $this])->getContent();
    }

    public function readonly($readonly)
    {
        $this->readonly = $readonly;
        return $this;
    }

    public function placeholder($text = '')
    {
        $this->placeholder = $text;
        return $this;
    }
}