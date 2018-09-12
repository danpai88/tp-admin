<?php
namespace app\common\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\Db;

class AdminBuild extends Command
{
    protected function configure()
    {
        $this->setName('admin:controller')
            ->addArgument('controller_name', Argument::OPTIONAL, "controller name")
            ->addArgument('model_name', Argument::OPTIONAL, "model name")
            ->setDescription('make a new admin controller');
    }

    protected function execute(Input $input, Output $output)
    {
        $controllName = $input->getArgument('controller_name');
        $table = $input->getArgument('model_name');

        if(stristr($table, '_')){
            $modelName = $this->convertUnderline($table);
        }

        list($columnContent, $formContent) = $this->makeColumns($table);

        $controllerStr = file_get_contents(__DIR__.'/controller.txt');
        $modelStr = file_get_contents(__DIR__.'/model.txt');

        $controllerStr = str_replace(
            ['{$controller_name}', '{$column_content}', '{$form_content}', '{$model_name}'],
            [$controllName, implode(",\n", $columnContent), implode(",\n", $formContent), $modelName],
            $controllerStr
        );
        file_put_contents(app()->getAppPath().'/admin/controller/'.$controllName.'.php', $controllerStr);

        $modelFile = app()->getAppPath().'/common/model/'.$modelName.'.php';
        if(!file_exists($modelFile)){
            $modelStr = str_replace(
                ['{$model_name}'],
                [$modelName],
                $modelStr
            );
            file_put_contents($modelFile, $modelStr);
        }
    }

    public function convertUnderline( $str , $ucfirst = true)
    {
        while(($pos = strpos($str , '_'))!==false)
            $str = substr($str , 0 , $pos).ucfirst(substr($str , $pos+1));
        return $ucfirst ? ucfirst($str) : $str;
    }

    protected function makeColumns($modelName)
    {
        $columns = Db::query('SHOW FULL FIELDS FROM '.$modelName);

        $columnHtml = $formHtml = [];

        foreach ($columns as $column) {
            $columnHtml[] = sprintf(
                "Column::text('%s', '%s')",
                $column['Field'],
                $column['Comment'] ? $column['Comment'] : $column['Field']
            );

            $type = 'input';
            if($column['Type'] == 'datetime'){
                $type = 'datetime';
            }

            $formHtml[] = sprintf(
                "Form::%s('%s', '%s')",
                $type,
                $column['Field'],
                $column['Comment'] ? $column['Comment'] : $column['Field']
            );
        }

        return [$columnHtml, $formHtml];
    }
}