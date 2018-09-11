<?php
namespace app\admin\controller;

use app\common\column\Column;
use app\common\display\DisplayTable;
use app\common\display\FormDisplay;
use app\common\form\Form;
use think\Controller;

class Menu extends Controller
{
    /**
     * @return \think\response\View
     * @throws \think\exception\DbException
     */
    public function index()
    {
        return DisplayTable::model('CyMenus')->title('菜单列表')->callback(function(DisplayTable $instance){
            $instance->columns = [
                Column::text('id',     '序号'),
                Column::text('title',  '名称'),
                Column::text('url',    '链接'),
                Column::text('order',  '排序'),
            ];
        });
    }

    /**
     * @return FormDisplay
     * @throws \think\exception\DbException
     */
    public function create()
    {
        return FormDisplay::model('CyMenus')->title('创建菜单')->callback(function($instance){
            $instance->forms = [
                Form::input('title', '名称'),
                Form::input('url', '链接'),
            ];
        });
    }

    public function update()
    {
        return FormDisplay::model('CyMenus')->title('编辑菜单')->callback(function($instance){
            $instance->forms = [
                Form::hidden('id'),
                Form::input('title', '名称'),
                Form::input('url', '链接'),
            ];
        });
    }

    public function delete()
    {

    }
}
