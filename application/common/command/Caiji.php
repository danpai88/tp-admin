<?php
namespace app\common\command;

use app\common\model\Keyword;
use app\common\model\TownNoResult;
use app\common\service\YzbmService;
use app\common\utils\ToolUtil;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Env;

class Caiji extends Command
{
	protected function configure()
    {
        $this->setName('caiji_csv')
        	->setDescription('caiji csv');
    }

    /**
     * @param Input $input
     * @param Output $output
     * @return int|null|void
     * @throws
     */
	protected function execute(Input $input, Output $output)
    {
        define('ROOT_PATH', app()->getRootPath());

        $this->caijiFromDb();
    }

    /**
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function caijiFromDb()
    {
        $lists = TownNoResult::where('bd_status', 0)->order('id desc')->limit(10)->select();
        foreach ($lists as $list) {
            $json = YzbmService::searchFromBaidu($list->title);
            foreach ($json as $item) {
                YzbmService::addNewYzbm($item['code'], $item['full_name']);
            }
            TownNoResult::where('id', $list->id)->update(['bd_status' => 1]);
            sleep(3);
        }
    }

    public function index()
    {
        $datas = ToolUtil::csv2array(ROOT_PATH.'/111.csv');

        foreach ($datas as $data) {
            if(!empty($data[1])){
                list(, $school,) = $data;
                $school = str_replace('"', '', $school);
                if(!Keyword::where('title', $school)->count()){
                    Keyword::insert(['title' => $school]);
                }
            }
        }
    }

    public function csv2array( $filename)
    {
        $all_lines = @file( $filename );
        if( !$all_lines ) {
            return FALSE;
        }

        $csv = [];

        foreach ($all_lines as $line) {
            $line = iconv('gb2312','utf-8', $line);
            $csv[] = explode(',', $line);
        }
        return $csv;
    }
}