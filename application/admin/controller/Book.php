<?php
namespace app\admin\controller;

use app\common\column\Column;
use app\common\display\DisplayTable;
use app\common\display\FormDisplay;
use app\common\form\Form;
use think\Controller;
use think\db\Query;

class Book extends Controller
{
    /**
     * @return \think\response\View
     * @throws \think\exception\DbException
     */
    public function index()
    {
        return DisplayTable::model('CyBooks')->title('列表')->callback(function(DisplayTable $instance){
            $instance->columns = [
                Column::text('id', '序号'),
                Column::text('title', '标题'),
                Column::text('is_full', '是否完结'),
                Column::text('author', '作者'),
                Column::text('views', '浏览量'),
                Column::img('thumbnail', '封面'),
                Column::text('is_vip', '是否VIP'),
                Column::text('status', '状态'),
                Column::text('created_at', '创建时间'),
            ];

            $instance->searchs = [

            ];

            $instance->query(function(Query $query){

            });
        });
    }

    /**
     * @return FormDisplay
     * @throws \think\exception\DbException
     */
    public function create()
    {
        return FormDisplay::model('CyBooks')->title('创建')->callback(function($instance){
            $instance->forms = [
                Form::input('title', '标题'),
                Form::input('thumbnail', '封面'),
                Form::input('intro', '简介'),
                Form::input('seo_t', 'seo_t'),
                Form::input('seo_d', 'seo_d'),
                Form::input('seo_k', 'seo_k'),
                Form::select('is_full', '是否完结')->options([0 => '否', 1 => '是'])->defaultValue(0),
                Form::input('author', '作者'),
                Form::input('views', '浏览量'),
                Form::select('is_vip', '是否VIP')->options([0 => '否', 1 => '是']),
                Form::select('status', '图书状态')->options([0 => '下架', 1 => '上架'])->defaultValue(1),
            ];
        });
    }

    public function delete()
    {

    }

    public function update()
    {
        return FormDisplay::model('CyBooks')->title('编辑')->callback(function($instance){
            $instance->forms = [
                Form::hidden('id'),
                Form::input('title', '标题'),
                Form::input('thumbnail', 'thumbnail'),
                Form::input('intro', 'intro'),
                Form::input('seo_t', 'seo_t'),
                Form::input('seo_d', 'seo_d'),
                Form::input('seo_k', 'seo_k'),
                Form::select('is_full', '是否完结')->options([0 => '否', 1 => '是']),
                Form::input('author', '作者'),
                Form::input('views', '浏览量'),
                Form::select('is_vip', '是否VIP')->options([0 => '否', 1 => '是']),
                Form::select('status', '图书状态')->options([0 => '下架', 1 => '上架']),
            ];
        });
    }
}