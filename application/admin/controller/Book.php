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
                Column::text('id', 'id'),
Column::text('title', '标题'),
Column::text('is_full', 'is_full'),
Column::text('author', 'author'),
Column::text('views', 'views'),
Column::img('thumbnail', 'thumbnail'),
Column::text('created_at', 'created_at'),
Column::text('is_vip', 'is_vip'),
Column::text('status', 'status')
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
                Form::input('id', 'id'),
Form::input('title', '标题'),
Form::input('thumbnail', 'thumbnail'),
Form::input('intro', 'intro'),
Form::input('seo_t', 'seo_t'),
Form::input('seo_d', 'seo_d'),
Form::input('seo_k', 'seo_k'),
Form::input('is_full', 'is_full'),
Form::input('author', 'author'),
Form::input('views', 'views'),
Form::input('source_url', 'source_url'),
Form::datetime('created_at', 'created_at'),
Form::datetime('updated_at', 'updated_at'),
Form::input('is_vip', 'is_vip'),
Form::input('status', 'status')
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
                Form::input('id', 'id'),
Form::input('title', '标题'),
Form::input('thumbnail', 'thumbnail'),
Form::input('intro', 'intro'),
Form::input('seo_t', 'seo_t'),
Form::input('seo_d', 'seo_d'),
Form::input('seo_k', 'seo_k'),
Form::input('is_full', 'is_full'),
Form::input('author', 'author'),
Form::input('views', 'views'),
Form::input('source_url', 'source_url'),
Form::datetime('created_at', 'created_at'),
Form::datetime('updated_at', 'updated_at'),
Form::input('is_vip', 'is_vip'),
Form::input('status', 'status')
            ];
        });
    }
}