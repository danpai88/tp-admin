<?php
namespace app\common\column;

class Base
{
    public $field = '';
    public $label = '';
    public $value = '';
    public $callback = null;
    protected $fast_options = null;
    protected $fast_option_default = '';

    public function __construct($field, $label = '')
    {
        $this->field = $field;
        $this->label = $label;
        return $this;
    }

    public function render($data, $field)
    {
        $tmp = explode('\\', get_class($this));
        $type = array_pop($tmp);
        $this->value = $data[$field];

        if($this->callback instanceof \Closure){
            $this->value = call_user_func($this->callback, $data);
        }

        if($this->fast_options){
            if(isset($this->fast_options[$data[$field]])){
                $this->value = $this->fast_options[$data[$field]];
            }
        }

        return view('common@column/'.strtolower($type), ['instance' => $this])->getContent();
    }

    public function fastCallback($options, $default = '')
    {
        $this->fast_options = $options;
        $this->fast_option_default = $default;
        return $this;
    }

    public function callback($callback)
    {
        $this->callback = $callback;
        return $this;
    }

	/**
	 * 获取不可见的成员属性
	 * @param $name
	 * @return mixed
	 * @throws \Exception
	 */
	public function __get($name)
	{
		if(!empty($this->$name)){
			return $this->$name;
		}
		exception("attr {$name} not found");
	}
}