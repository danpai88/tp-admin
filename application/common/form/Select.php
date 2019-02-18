<?php
namespace app\common\form;

class Select extends Base
{
    protected $options = [];

    protected $isMulti = false;

    public $resources = [
	    "/admin-lte/bower_components/select2/dist/css/select2.css",
	    "/admin-lte/bower_components/select2/dist/js/select2.js"
    ];

    public function options($options = [])
    {
        $this->options = $options;
        return $this;
    }

    public function multi($multi = true)
    {
    	$this->isMulti = $multi;
    	return $this;
    }
}