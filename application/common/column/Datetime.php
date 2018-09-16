<?php
namespace app\common\column;

class Datetime extends Base
{
    public $format = null;

    public function render($data, $field)
    {
        $value = $data[$field];

        if($this->format){
            $tmp = $value;
            if(!is_numeric($value)){
                $tmp = strtotime($value);
            }
            $this->value = date($this->format, $tmp);
        }
        return view('column/text', ['instance' => $this])->getContent();
    }

    public function format($format = '')
    {
        $this->format = $format;
        return $this;
    }
}