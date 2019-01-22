<?php
namespace app\admin\controller;

use app\common\column\Column;
use app\common\display\DisplayTable;
use app\common\display\FormDisplay;
use app\common\form\Form;
use app\common\utils\ArrayHelp;

class Menu extends Base
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
                Column::text('icon',    '图标'),
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
        return FormDisplay::model('CyMenus')->title('创建菜单')->callback(function(FormDisplay $instance){
            $parents = model('CyMenus')->where('pid', 0)->select();
            $datas = ArrayHelp::map($parents, 'id', 'title');
            $datas[0] = '--顶级--';

            $instance->forms = [
                Form::select('pid', '选择父类')->options($datas)->defaultValue(0),
                Form::input('title', '名称'),
                Form::input('url', '链接'),
                Form::input('icon', '图标'),
                Form::input('order', '排序')->value(0),
            ];
        });
    }

    public function update()
    {
        return FormDisplay::model('CyMenus')->title('编辑菜单')->callback(function(FormDisplay $instance){
            $parents = model('CyMenus')->where('pid', 0)->select();
            $datas = ArrayHelp::map($parents, 'id', 'title');
            $datas[0] = '--顶级--';

            $instance->forms = [
                Form::hidden('id'),
                Form::select('pid', '选择父类')->options($datas),
                Form::input('title', '名称'),
                Form::input('url', '链接'),
                Form::input('icon', '图标'),
                Form::input('order', '排序'),
            ];
        });
    }

    public function delete()
    {

    }
}
