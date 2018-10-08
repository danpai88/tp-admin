<?php
namespace app\common\form;

class Textarea extends Base
{
    public $height = 200;

    public function height($height)
    {
        $this->height = $height;
        return $this;
    }
}