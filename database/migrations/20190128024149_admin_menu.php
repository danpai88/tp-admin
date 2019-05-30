<?php

use think\migration\Migrator;
use think\migration\db\Column;

class AdminMenu extends Migrator
{
    public $table = 'admin_menus';

    public function up()
    {
        if(!$this->hasTable($this->table)){
            $this->table($this->table, ['id' => false])
                ->addColumn(Column::integer('id')->setUnsigned()->setComment('序号')->setIdentity(true))
                ->addColumn(Column::string('title', 50)->setComment('菜单标题'))
                ->addColumn(Column::string('url', 100)->setDefault('')->setComment('菜单url'))
                ->addColumn(Column::smallInteger('order')->setUnsigned()->setDefault(99)->setComment('排序，越小越靠前'))
                ->addColumn(Column::tinyInteger('pid')->setUnsigned()->setDefault(0)->setComment('父类ID'))
                ->addColumn(Column::string('icon', 20)->setDefault('fa-bars')->setComment('图标'))
                ->setPrimaryKey('id')
                ->create();
        }

        $this->table($this->table)->insert([
            [
                'title' => '菜单管理',
                'icon' => 'fa-bars',
            ],[
                'title' => '节点管理',
                'url' => 'Menu/index',
                'pid' => 1,
                'icon' => 'fa-angle-right',
            ]
        ])->save();
    }

    public function down()
    {
        if($this->hasTable($this->table)){
            $this->dropTable($this->table);
        }
    }
}
