<?php
namespace app\common\column;

class Button extends Base
{
	protected $href = '';

	public function href($href)
	{
		$this->href = $href;
		return $this;
	}

	public function render($data = [], $field = '')
	{
		$tmp = explode('\\', get_class($this));
		$type = array_pop($tmp);

		return view('common@column/'.strtolower($type), [
				'instance' => $this
			])
			->getContent();
	}
}