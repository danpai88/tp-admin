<?php
namespace app\admin\controller;

use app\common\column\Column;
use app\common\display\DisplayTable;
use app\common\display\FormDisplay;
use app\common\form\Form;
use think\Controller;
use think\db\Query;

class Index extends Controller
{
    /**
     * @return \think\response\View
     * @throws \think\exception\DbException
     */
    public function index()
    {
        return DisplayTable::model('WpPosts')->title('文章列表')->callback(function(DisplayTable $instance){
            $instance->columns = [
                Column::text('ID',          '序号'),
                Column::text('post_title',  '文章标题'),
            ];

            $instance->searchs = [
                Form::input('ID', false)->placeholder('输入文章ID'),
                Form::select('post_title', false)->options(['a','b','c']),
            ];

            $instance->query(function(Query $query){
                $query->where('post_type', 'post');
            });
        });
    }

    /**
     * @return FormDisplay
     * @throws \think\exception\DbException
     */
    public function create()
    {
        return FormDisplay::model('WpPosts')->callback(function($instance){
            $instance->forms = [
                Form::input('post_title', '标题')->placeholder('输入标题'),
                Form::input('guid'),
                Form::datetime('post_date_gmt', '发布时间'),
            ];
        });
    }

    public function delete()
    {

    }

    public function update()
    {
        return FormDisplay::model('WpPosts')->title('文章编辑')->callback(function($instance){
            $instance->forms = [
                Form::hidden('ID'),
                Form::input('post_title', '标题')->placeholder('输入标题'),
                Form::input('guid'),
                Form::datetime('post_date_gmt', '发布时间'),
            ];
        });
    }
}
