<?php
namespace app\common\display;

use app\common\form\Input;
use app\common\form\Select;
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
        call_user_func($callback, $this);

        $model = model($this->model);
        $pkName = $model->getPk();

        $pkValue = input($pkName);

        if(Request::isPost()){
            if($pkValue){
                $model->where($pkName, $pkValue)->update(Request::post());
            }else{
                $pkValue = $model->insert(Request::post(), false, true);
            }
            $this->success('操作成功', url('update', [$pkName => $pkValue]));
        }

        if($pkValue){
            $this->data = $model->find($pkValue);
        }
        return view('display/form', ['instance' => $this]);
    }
}