<?php
namespace app\common\display;

use think\facade\Request;

class DisplayTable
{
    public static $handle = null;

    public $columns = [];
    public $searchs = [];

    public $title = '';

    public $disableAction = false;
    public $disableEdit = false;
    public $disableDelete = false;

    public $model = '';

    /**
     * @param $model
     * @return $this
     */
    public static function model($model)
    {
        if(is_null(static::$handle)){
            static::$handle = new self();
        }

        static::$handle->model = $model;

        return static::$handle;
    }

    public function title($title = '')
    {
        $this->title = $title;
        return $this;
    }

    public function getPage()
    {
        return Request::get('p', 1);
    }

    /**
     * @param $callback
     * @return \think\response\View
     * @throws \think\exception\DbException
     */
    public function callback($callback)
    {
        call_user_func($callback, $this);

        $model = model($this->model);

        $where = [];
        foreach ($this->searchs as $search) {
            if(Request::get($search->id)){
                $where[$search->id] = Request::get($search->id);
            }
        }

        $datas = $model->where($where)->paginate(10);

        $searchHtml = view('display/search', [
            'instance' => $this,
        ])->getContent();

        $tableHtml = view('column/tr', [
            'datas' => $datas,
            'instance' => $this,
            'pk' => $model->getPk(),
            'model' => $model,
        ])->getContent();

        return view('display/table', [
            'title' => $this->title,
            'table_content' => $tableHtml,
            'search_content' => $searchHtml,
        ]);
    }
}