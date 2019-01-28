<?php
namespace app\common\column;

class Action
{
	protected $title = '';
	protected $href = '';
	protected $icon = '';
	protected $show_by_key = '';
	protected $show_by_val = '';
	protected $needConfirm = false;

	public function __construct($title)
	{
		$this->title = $title;
	}

	public function confirm($confirm = true)
	{
		$this->needConfirm = $confirm;
		return $this;
	}

	public function showByData(array $data)
	{
		$this->show_by_key = key($data);
		$this->show_by_val = current($data);
		return $this;
	}

	public function isShow($data)
	{
		if(!$this->show_by_key){
			return true;
		}

		if(!isset($data[$this->show_by_key])){
			return false;
		}

		if($data[$this->show_by_key] == $this->show_by_val){
			return true;
		}
		return false;
	}

	public function icon($icon)
	{
		$this->icon = $icon;
		return $this;
	}

	public function href($href)
	{
		$this->href = $href;
		return $this;
	}

	public function __get($name)
	{
		if(isset($this->$name)){
			return $this->$name;
		}
	}
}