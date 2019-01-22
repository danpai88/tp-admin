<?php
namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;
use think\Db;

class AdminBuild extends Command
{
    protected function configure()
    {
        $this->setName('admin:make')
            ->addArgument('controller_name', Argument::OPTIONAL, "controller name")
            ->addArgument('table_name',      Argument::OPTIONAL, "table name")
            ->setDescription('ex: php think admin:make ControllerName table_name');
    }

	/**
	 * @param Input $input
	 * @param Output $output
	 * @return int|void|null
	 * @throws \think\Exception
	 */
    protected function execute(Input $input, Output $output)
    {
        $fields = $this->createModel($input, $output);
        $this->makeController($input, $fields);
    }

    protected function createModel(Input $input, Output $output)
    {
	    $tableName = $input->getArgument('table_name');

	    $sql = sprintf(
		    "SELECT COLUMN_NAME,DATA_TYPE,COLUMN_COMMENT FROM information_schema.COLUMNS WHERE table_name = '%s'"
		    ,$tableName
	    );

	    $columns = Db::query($sql);

	    $commnet = [];

	    foreach ($columns as $column) {
		    $type = $column['DATA_TYPE'];
		    if(in_array($type, ['char', 'varchar', 'datetime'])){
			    $type = 'string';
		    }else if(stristr($type, 'int')){
			    $type = 'integer';
		    }elseif(in_array($type, ['decimal'])){
			    $type = 'float';
		    }else{
			    $type = 'string';
		    }
		    $commnet[] = ' * @property '.$type.' '.$column['COLUMN_NAME'].' '.$column['COLUMN_COMMENT'];
		    $fields[$column['COLUMN_NAME']] = $column['COLUMN_COMMENT'];
	    }

	    $tmp = implode("\n", $commnet);
	    $className = $this->convertUnderline($tableName);
	    $module = 'common';
	    $str = <<<EOT
<?php
namespace app\\{$module}\model;

use think\Model;

/**
 * Class {$className}
 * @package app\\{$module}\model
 *
 {$tmp}
 */
class {$className} extends Model
{
    protected \$table = '{$tableName}';
}
EOT;
	    $file = app()->getAppPath().'/'.$module.'/model/'.$className.'.php';
	    if(!file_exists($file)){
		    $ret = file_put_contents($file, $str);
		    if($ret){
			    $output->writeln($className.' create ok');
		    }
	    }
	    return $fields;
    }

    /**
     * 构建controller文件
     * @param Input $input
     * @param $columnContent
     * @param $formContent
     * @param $modelName
     */
    protected function makeController(Input $input, $fields, $moduleName = 'admin')
    {
        $controllName = $input->getArgument('controller_name');
        $controllerStr = file_get_contents(__DIR__.'/controller.txt');

	    $columnContent = [];
	    $formContent = [];

		foreach ($fields as $name => $comment){
			$columnContent[] = sprintf("				Column::text('%s',  '%s')", $name, $comment ? $comment : $name);
			if($name != 'id'){
				$formContent[] = sprintf("				Form::input('%s',  '%s')", $name, $comment ? $comment : $name);
			}
		}

	    $tableName = $input->getArgument('table_name');
	    $modelName = $this->convertUnderline($tableName);

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

    public function convertUnderline( $str , $ucfirst = true)
    {
        while(($pos = strpos($str , '_'))!==false)
            $str = substr($str , 0 , $pos).ucfirst(substr($str , $pos+1));
        return $ucfirst ? ucfirst($str) : $str;
    }
}