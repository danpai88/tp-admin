<?php
namespace app\common\form;

class Datetime extends Base
{
    public $format = 'yyyy-mm-dd';

    protected $resources = [
		"/admin-lte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css",
	    "/admin-lte/bower_components/bootstrap-daterangepicker/daterangepicker.css",
	    "/admin-lte/bower_components/bootstrap-daterangepicker/daterangepicker.js",
	    "/admin-lte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js",
    ];

    public function format($format)
    {
        $this->format = $format;
        return $this;
    }
}