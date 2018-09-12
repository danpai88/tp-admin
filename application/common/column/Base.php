<?php
namespace app\common\column;

class Base
{
    public $field = '';
    public $label = '';
    public $value = '';

    public function __construct($field, $label = '')
    {
        $this->field = $field;
        $this->label = $label;
        return $this;
    }

    public function render($value = '')
    {
        $tmp = explode('\\', get_class($this));
        $type = array_pop($tmp);
        $this->value = $value;
        return view('column/'.$type, ['instance' => $this])->getContent();
    }

    public function callback($callback)
    {

    }
}