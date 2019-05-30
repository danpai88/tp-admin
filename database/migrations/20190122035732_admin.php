<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Admin extends Migrator
{
	public $table = 'admin_users';

    public function up()
    {
    	if(!$this->hasTable($this->table)){
		    $this->table($this->table, ['id' => false])
			    ->addColumn(Column::integer('id')->setUnsigned()->setComment('序号')->setIdentity(true))
			    ->addColumn(Column::string('nickname', 50)->setComment('用户昵称'))
			    ->addColumn(Column::string('username', 50)->setComment('登录用户名'))
			    ->addColumn(Column::string('password', 64)->setComment('登录密码'))
			    ->addColumn(Column::tinyInteger('status')->setUnsigned()->setDefault(1)->setComment('账号状态'))
			    ->addColumn(Column::integer('created_at')->setUnsigned()->setDefault(0)->setComment('创建时间'))
			    ->addColumn(Column::integer('updated_at')->setUnsigned()->setDefault(0)->setComment('更新时间'))
			    ->setPrimaryKey('id')
			    ->create();
	    }

	    $this->table($this->table)->insert([
		    'nickname' => '超级管理员',
		    'username' => 'admin',
		    'password' => password_hash('admin888', PASSWORD_DEFAULT),
		    'created_at' => time(),
		    'updated_at' => time(),
	    ])->save();
    }

    public function down()
    {
	    if($this->hasTable($this->table)){
	    	$this->dropTable($this->table);
	    }
    }
}
