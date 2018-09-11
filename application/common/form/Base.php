<?php
namespace app\common\form;

class Base
{
    public $value = '';
    public $placeholder = '';

    public $id = '';
    public $label = '';

    public function __construct($id, $label = '')
    {
        $this->id = $id;
        $this->label = $label;
        return $this;
    }

    public function value($value = '')
    {
        $this->value = $value;
        return $this;
    }

    public function render($type = '')
    {
        if(!$type){
            $tmp = explode('\\', get_class($this));
            $type = array_pop($tmp);
        }
        return view('form/'.$type, ['instance' => $this])->getContent();
    }

    public function placeholder($text = '')
    {
        $this->placeholder = $text;
        return $this;
    }
}