<?php
namespace app\common\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use app\common\model\Books;

class UpdateBook extends Command
{
	protected function configure()
    {
        $this->setName('update_book')
        	->setDescription('update book something');
    }

	protected function execute(Input $input, Output $output)
    {
    	$books = Books::whereRaw('view < 1')->select();
    	foreach ($books as $key => $book) {
    		$book->view = rand(10, 1000);
    		$book->save();
    		$output->writeln($book->id . ' done ~ ' . $book->view);
    	}
    }
}