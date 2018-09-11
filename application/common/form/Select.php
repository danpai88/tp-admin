<?php
namespace app\common\form;

class Select extends Base
{
    public $options = [];

    public function options($options = [])
    {
        $this->options = $options;
        return $this;
    }
}