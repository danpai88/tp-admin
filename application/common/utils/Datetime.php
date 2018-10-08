<?php
namespace app\common\utils;

class Datetime
{
    public static function currentDate()
    {
        return date('Y-m-d H:i:s');
    }

    public static function currentShortDate($str = '')
    {
        if(is_numeric($str)){
            return date('Y-m-d', $str);
        }

        if($str){
            return date('Y-m-d', strtotime($str));
        }
        return date('Y-m-d');
    }
}