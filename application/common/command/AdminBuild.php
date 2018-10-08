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
        $this->setName('admin:make')
            ->addArgument('module_name',     Argument::OPTIONAL, "module name")
            ->addArgument('controller_name', Argument::OPTIONAL, "controller name")
            ->addArgument('model_name',      Argument::OPTIONAL, "model name")
            ->addArgument('db_conn',         Argument::OPTIONAL, "db_conn name")
            ->setDescription('ex: php think admin:make adminyzbm CyPush cy_pushs yzbm');
    }

    protected function execute(Input $input, Output $output)
    {
        $table = $input->getArgument('model_name');
        $db_conn = $input->getArgument('db_conn');

        if(stristr($table, '_')){
            $modelName = $this->convertUnderline($table);
        }

        list($columnContent, $formContent) = $this->makeColumns($table, $db_conn);

        $this->makeController($input, $columnContent, $formContent, $modelName);
        $this->makeModel($input, $modelName);
    }

    /**
     * 构建controller文件
     * @param Input $input
     * @param $columnContent
     * @param $formContent
     * @param $modelName
     */
    protected function makeController(Input $input, $columnContent, $formContent, $modelName)
    {
        $moduleName = $input->getArgument('module_name');
        $controllName = $input->getArgument('controller_name');
        $controllerStr = file_get_contents(__DIR__.'/controller.txt');

        $controllerStr = str_replace(
            [
                '{$controller_name}',
                '{$column_content}',
                '{$form_content}',
                '{$model_name}',
                '{$module_name}'
            ],
            [
                $controllName,
                implode(",\n", $columnContent),
                implode(",\n", $formContent),
                $modelName,
                $moduleName
            ],
            $controllerStr
        );

        file_put_contents(app()->getAppPath().'/'.$moduleName.'/controller/'.$controllName.'.php', $controllerStr);
    }

    /**
     * 构建model文件
     * @param Input $input
     * @param $modelName
     */
    protected function makeModel(Input $input, $modelName)
    {
        $moduleName = $input->getArgument('module_name');
        $modelStr = file_get_contents(__DIR__.'/model.txt');

        $modelFile = app()->getAppPath().'/'.$moduleName.'/model/'.$modelName.'.php';

        if(!file_exists($modelFile)){
            $modelStr = str_replace(
                ['{$model_name}', '{$module_name}'],
                [$modelName, $moduleName],
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

    /**
     * 获取数据表结构
     * @param string $modelName
     * @param string $db_conn
     * @return array
     * @throws \think\Exception
     */
    protected function makeColumns($modelName, $db_conn)
    {
        $columns = Db::connect($db_conn)->query('SHOW FULL FIELDS FROM '.$modelName);

        $columnHtml = $formHtml = [];

        foreach ($columns as $key => $column) {
            $space = '                ';
            $columnHtml[] = sprintf(
                ($key > 0? $space : '')."Column::text('%s', '%s')",
                $column['Field'],
                $column['Comment'] ? $column['Comment'] : $column['Field']
            );

            $type = 'input';
            if($column['Type'] == 'datetime'){
                $type = 'datetime';
            }

            $formHtml[] = sprintf(
                ($key > 0? $space : '')."Form::%s('%s', '%s')",
                $type,
                $column['Field'],
                $column['Comment'] ? $column['Comment'] : $column['Field']
            );
        }

        return [$columnHtml, $formHtml];
    }
}