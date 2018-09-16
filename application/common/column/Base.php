<?php
namespace app\common\column;

class Base
{
    public $field = '';
    public $label = '';
    public $value = '';
    public $callback = null;

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

        return view('column/'.$type, ['instance' => $this])->getContent();
    }

    public function callback($callback)
    {
        $this->callback = $callback;
        return $this;
    }
}