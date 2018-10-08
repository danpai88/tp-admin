<?php
namespace app\common\column;

class Label extends Base
{
    public $options = [];

    public function options($options)
    {
        $this->options = $options;
        return $this;
    }
}