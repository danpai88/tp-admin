<?php
namespace app\common\form;

class Datetime extends Base
{
    public $format = 'yyyy-mm-dd';

    public function format($format)
    {
        $this->format = $format;
        return $this;
    }
}