<?php
namespace app\admin\controller;

use app\common\column\Column;
use app\common\display\DisplayTable;
use app\common\display\FormDisplay;
use app\common\form\Form;
use think\Controller;
use think\db\Query;

class Chapter extends Controller
{
    /**
     * @return \think\response\View
     * @throws \think\exception\DbException
     */
    public function index()
    {
        return DisplayTable::model('CyChapters')->title('列表')->callback(function(DisplayTable $instance){
            $instance->columns = [
                Column::text('id', 'id'),
Column::text('bid', 'bid'),
Column::text('title', 'title'),
Column::text('order', 'order'),
Column::text('created_at', 'created_at'),
Column::text('seo_t', 'seo_t'),
Column::text('seo_d', 'seo_d'),
Column::text('seo_k', 'seo_k'),
Column::text('is_vip', 'is_vip'),
Column::text('is_caiji', 'is_caiji'),
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
        return FormDisplay::model('CyChapters')->title('创建')->callback(function($instance){
            $instance->forms = [
                Form::input('id', 'id'),
Form::input('bid', 'bid'),
Form::input('title', 'title'),
Form::input('order', 'order'),
Form::datetime('created_at', 'created_at'),
Form::datetime('updated_at', 'updated_at'),
Form::input('seo_t', 'seo_t'),
Form::input('seo_d', 'seo_d'),
Form::input('seo_k', 'seo_k'),
Form::input('is_vip', 'is_vip'),
Form::input('source_url', 'source_url'),
Form::input('is_caiji', 'is_caiji'),
Form::input('status', 'status')
            ];
        });
    }

    public function delete()
    {

    }

    public function update()
    {
        return FormDisplay::model('CyChapters')->title('编辑')->callback(function($instance){
            $instance->forms = [
                Form::input('id', 'id'),
Form::input('bid', 'bid'),
Form::input('title', 'title'),
Form::input('order', 'order'),
Form::datetime('created_at', 'created_at'),
Form::datetime('updated_at', 'updated_at'),
Form::input('seo_t', 'seo_t'),
Form::input('seo_d', 'seo_d'),
Form::input('seo_k', 'seo_k'),
Form::input('is_vip', 'is_vip'),
Form::input('source_url', 'source_url'),
Form::input('is_caiji', 'is_caiji'),
Form::input('status', 'status')
            ];
        });
    }
}