<?php
namespace app\common\column;

class Text
{
    public $field = '';
    public $label = '';
    public $render = '';

    public function __construct($field, $label = '')
    {
        $this->field = $field;
        $this->label = $label;
        return $this;
    }

    public function callback($callback)
    {

    }
}