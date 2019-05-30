<?php
namespace app\admin\controller;

use app\common\column\Column;
use app\common\display\DisplayTable;
use app\common\display\FormDisplay;
use app\common\form\Form;
use think\db\Query;

class Index extends Base
{
    protected $modelName = 'CyWaitCaiji';

    /**
     * 数据列表
     * @throws
     */
    public function index()
    {
        return DisplayTable::model($this->modelName)->title('数据列表')->callback(function(DisplayTable $instance){
            $instance->columns = [
                Column::text('id',  'id'),
				Column::text('cid',  'cid'),
				Column::text('title',  'title'),

                Column::text('is_caiji', "状态")->callback(function ($data){
                    if ($data['is_caiji']){
                        return '正常';
                    }
                    return '已停用';
                }),

				Column::text('is_caiji',  'is_caiji'),
				Column::text('is_origin',  'is_origin'),
				Column::text('source_url',  'source_url'),
				Column::text('publish_time',  'publish_time')
            ];

            $instance->searchs = [
                Form::input("title", false)->placeholder("请输入标题搜索"),
            ];

            $instance->query(function(Query $query){
                $query->order("id desc");
            });

            //禁用创建按钮
            $instance->disableCreate = true;

            $instance->disableEdit;
            $instance->disableAction;
            $instance->disableDelete;

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
                Form::input('cid',  'cid'),
				Form::input('title',  'title'),
				Form::input('is_caiji',  'is_caiji'),
				Form::input('is_origin',  'is_origin'),
				Form::input('source_url',  'source_url'),
				Form::input('publish_time',  'publish_time')
        ];
    }
}