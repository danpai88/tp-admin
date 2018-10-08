<?php
namespace app\admin\controller;

use app\common\column\Column;
use app\common\display\DisplayTable;
use app\common\display\FormDisplay;
use app\common\form\Form;
use think\Controller;
use think\db\Query;

class Setting extends Controller
{
    protected $modelName = 'CySettings';

    /**
     * 数据列表
     * @throws
     */
    public function index()
    {
        return DisplayTable::model($this->modelName)->title('数据列表')->callback(function(DisplayTable $instance){
            $instance->columns = [
                Column::text('id', 'id'),
                Column::text('key', 'key'),
                Column::text('value', 'value'),
                Column::text('created_at', 'created_at'),
                Column::text('updated_at', 'updated_at'),
                Column::text('remark', 'remark')
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
            Form::input('id', 'id'),
                Form::input('key', 'key'),
                Form::input('value', 'value'),
                Form::datetime('created_at', 'created_at'),
                Form::datetime('updated_at', 'updated_at'),
                Form::input('remark', 'remark')
        ];
    }
}