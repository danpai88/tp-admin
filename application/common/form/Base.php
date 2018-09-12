<?php
namespace app\common\form;

class Base
{
    public $value = '';
    public $placeholder = '';

    public $id = '';
    public $label = '';

    public $data = [];

    public $defaultValue = '';

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
            if(isset($data[$this->id])){
                $this->value($data[$this->id]);
            }
        }

        return view('form/'.$type, ['instance' => $this])->getContent();
    }

    public function placeholder($text = '')
    {
        $this->placeholder = $text;
        return $this;
    }
}