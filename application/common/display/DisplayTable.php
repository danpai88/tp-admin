<?php
namespace app\common\display;

use think\facade\Request;

class DisplayTable extends Base
{
    public static $handle = null;

    public $columns = [];
    public $searchs = [];

    public $title = '';

    public $disableAction = false;
    public $disableEdit = false;
    public $disableCreate = false;
    public $disableDelete = false;

    public $model = '';
    public $model_handle = null;

    public $model_user_query = null;

    public function getPage()
    {
        return Request::get('p', 1);
    }

    public function query($callback)
    {
        $this->model_user_query = $callback;
        return $this;
    }

    /**
     * @param $callback
     * @return \think\response\View
     * @throws \think\exception\DbException
     */
    public function callback($callback)
    {
        call_user_func($callback, $this);

        $this->model_handle = model($this->model)->where([]);

        $where = [];
        foreach ($this->searchs as $search) {
            if(Request::get($search->id)){
                $where[$search->id] = Request::get($search->id);
            }
        }

        //处理用户自定义的 查询条件
        if($this->model_user_query instanceof \Closure){
            call_user_func($this->model_user_query, $this->model_handle);
        }

        $this->model_handle->where($where);
        $datas = $this->model_handle->paginate(8);

        $searchHtml = view('display/search', [
            'instance' => $this,
        ])->getContent();

        $tableHtml = view('column/tr', [
            'datas' => $datas,
            'instance' => $this,
            'pk' => $this->model_handle->getPk(),
            'model' => $this->model_handle,
        ])->getContent();

        return view('display/table', [
            'instance' => $this,
            'title' => $this->title,
            'table_content' => $tableHtml,
            'search_content' => $searchHtml,
        ]);
    }
}