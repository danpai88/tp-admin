<?php
namespace app\common\utils;

class ArrayHelp
{
    /**
     * 取出数组其中的一列
     * @param $array
     * @param $field
     * @return array
     */
    public static function column($array, $field)
    {
        $tmp = [];
        foreach ($array as $item) {
            if(isset($item[$field])){
                $tmp[] = $item[$field];
            }
        }
        return $tmp;
    }

    /**
     * 转成一维数组
     * @param $array
     * @param $keyField
     * @param $valField
     * @return array
     */
    public static function map($array, $keyField, $valField)
    {
        $tmp = [];
        foreach ($array as $item) {
            if(isset($item[$keyField]) && isset($item[$valField])){
                $tmp[$item[$keyField]] = $item[$valField];
            }
        }
        return $tmp;
    }

    public static function index($array, $keyField)
    {
        $tmp = [];
        foreach ($array as $item) {
            if(isset($item[$keyField])){
                $tmp[$item[$keyField]] = $item;
            }
        }
        return $tmp;
    }

}