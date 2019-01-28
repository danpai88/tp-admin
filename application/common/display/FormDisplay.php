<?php
namespace app\common\display;

use think\facade\Request;
use traits\controller\Jump;

class FormDisplay extends Base
{
    use Jump;

    public $forms = [];
    public $model = '';
    public $data = [];
    public $title = '';

    public $query = null;

    public $validate = [];

    /**
     * @param $callback
     * @throws \think\exception\DbException
     * @return $this
     */
    public function callback($callback)
    {
        $this->model = model($this->model);

        call_user_func($callback, $this);

        $pkName = $this->model->getPk();

        $pkValue = input($pkName);

        if(Request::isPost()){
            $data = Request::post();
            if($pkValue){
                $this->model->save($data, [$pkName => $pkValue]);
                $this->success('编辑成功', url('update', [$pkName => $pkValue]));
            }else{
                $this->model->save($data);
                $pkValue = $this->model->$pkName;
                $this->success('创建成功', url('create'));
            }
        }

        if($pkValue){
            $this->data = $this->model->find($pkValue);
        }

	    list($parent_path_id, $son_path_id) = $this->getCurrentMenu();

        return view('common@display/form', [
	        'parent_path_id' => $parent_path_id,
	        'son_path_id' => $son_path_id,
        	'instance' => $this
        ]);
    }
}