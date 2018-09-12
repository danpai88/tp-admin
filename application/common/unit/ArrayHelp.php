<?php
namespace app\common\unit;

class ArrayHelp
{
    public static function map($datas, $keyField, $valueField)
    {
        $tmp = [];
        foreach ($datas as $data) {
            $tmp[$data[$keyField]] = $data[$valueField];
        }
        return $tmp;
    }
}