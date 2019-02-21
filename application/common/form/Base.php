<?php
namespace app\common\form;

use think\facade\Request;

class Base
{
    public $value = '';
    protected $placeholder = '';
    public $readonly = false;

    protected $id = '';
	protected $label = '';

    public $data = [];

    private $defaultValue = '';

	protected $required = false;

    //存放 js css
    protected $resources = [];

    public function __construct($id, $label = '')
    {
        $this->id = $id;
        $this->label = $label === '' ? $id : $label;
        $this->setJsCss();
        return $this;
    }

    //设置组件要用到的 js和css
    protected function setJsCss()
    {
	    foreach ($this->resources as $item){
		    if (!in_array($item, Form::$resources)){
			    Form::$resources[] = $item;
		    }
	    }
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
            }elseif(array_keys($this->id, $data)){
                //从数据库中 获取value
                $this->value($data[$this->id]);
            }else{
                $this->value = $this->defaultValue;
            }
        }

        return view('common@form/'.strtolower($type), ['instance' => $this])->getContent();
    }

    public function __get($name)
    {
	    if(isset($this->$name)){
	    	return $this->$name;
	    }
    }

	public function required($required)
    {
    	$this->required = $required;
    	return $this;
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