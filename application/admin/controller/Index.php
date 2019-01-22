<?php
namespace app\admin\controller;

use app\common\column\Column;
use app\common\display\DisplayTable;
use app\common\display\FormDisplay;
use app\common\form\Form;
use think\db\Query;

class Index extends Base
{
    protected $modelName = 'CyKefuPics';

    /**
     * 数据列表
     * @throws
     */
    public function index()
    {
        return DisplayTable::model($this->modelName)->title('数据列表')->callback(function(DisplayTable $instance){
            $instance->columns = [
                				Column::text('id',  'id'),
				Column::text('title',  '消息标题'),
				Column::text('image',  '图片链接'),
				Column::text('description',  '图片链接'),
				Column::text('url',  '链接'),
				Column::text('created_at',  'created_at')
            ];

            $instance->searchs = [

            ];

            $instance->query(function(Query $query){

            });
        });
    }

    /**
     * 创建数据
     * @throws
     */
    public function create()
    {
        return FormDisplay::model($this->modelName)->title('创建数据')->callback(function($instance){
            $instance->forms = $this->formFields();

            $instance->model->event('before_insert', function($data){

            });
        });
    }

    /**
     * 删除数据
     * @throws
     */
    public function delete($id = 0)
    {
        $ret = model($this->modelName)->where(model($this->modelName)->getPk(), $id)->delete();
        if($ret){
            $this->success('删除成功');
        }
        $this->error('删除失败');
    }

    /**
     * 编辑数据
     * @throws
     */
    public function update()
    {
        return FormDisplay::model($this->modelName)->title('编辑数据')->callback(function($instance){
            $forms = $this->formFields();
            $forms[] = Form::hidden(model($this->modelName)->getPk());
            $instance->forms = $forms;

            $instance->model->event('before_update', function($data){

            });
        });
    }

    /**
     * 创建和编辑公共表单字段
     * @throws
     */
    protected function formFields()
    {
        return [
        	Form::input('title',  '消息标题'),
				Form::input('image',  '图片链接'),
				Form::input('description',  '图片链接'),
				Form::input('url',  '链接'),
				Form::input('created_at',  'created_at')
        ];
    }
}