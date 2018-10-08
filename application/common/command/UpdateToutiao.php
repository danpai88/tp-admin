<?php
namespace app\common\command;

use app\common\utils\ToolUtil;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use app\common\model\Toutiao;

date_default_timezone_set(config('default_timezone'));

class UpdateToutiao extends Command
{
	protected function configure()
    {
        $this->setName('update_toutiao')
        	->setDescription('push toutiao post crond');
    }

    /**
     * @param Input $input
     * @param Output $output
     * @return int|null|void
     * @throws
     */
	protected function execute(Input $input, Output $output)
    {
    	$posts = Toutiao::where('status', Toutiao::STATUS_WAIT_PUSH)
            ->where('cid', 20)
            ->limit(10)
            ->order('id asc')
            ->column('id');

    	$ret = 0;
    	if(count($posts)){
    	    $date = ToolUtil::current_date();
            $ret = Toutiao::whereIn('id', $posts)->update([
                'status' => Toutiao::STATUS_NORMAL,
                'updated_at' => $date,
                'publish_time' => $date,
            ]);
        }
        echo $ret.' rows done';
    }
}