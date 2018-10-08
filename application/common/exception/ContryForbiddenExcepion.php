<?php
namespace app\common\exception;

use think\Exception;

class ContryForbiddenExcepion extends Exception
{
    public function __construct($message = '', $code = 500, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}