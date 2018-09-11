<?php
namespace app\admin\controller;

use app\common\column\Column;
use app\common\display\DisplayTable;
use app\common\display\FormDisplay;
use app\common\form\Form;

class Index
{
    /**
     * @return \think\response\View
     * @throws \think\exception\DbException
     */
    public function index()
    {
        return DisplayTable::model('Users')->title('用户列表')->callback(function(DisplayTable $instance){
            $instance->columns = [
                Column::text('id',       '序号'),
                Column::text('nickName', '昵称'),
            ];

            $instance->searchs = [
                Form::input('id')->placeholder('输入用户ID'),
                Form::select('nickName')->options(['a','b','c']),
            ];
        });
    }

    /**
     * @return FormDisplay
     * @throws \think\exception\DbException
     */
    public function create()
    {
        return FormDisplay::model('Users')->callback(function($instance){
            $instance->forms = [
                Form::input('nickName')->placeholder('用户昵称')
            ];
        });
    }

    public function delete()
    {

    }

    public function update()
    {
        return FormDisplay::model('Users')->title('用户编辑')->callback(function($instance){
            $instance->forms = [
                Form::input('nickName', '用户昵称')->placeholder('用户昵称')
            ];
        });
    }
}
