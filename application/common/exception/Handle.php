<?php
namespace app\common\exception;

use Exception;
use think\facade\Request;
use think\exception\Handle as BaseHandle;
use app\common\utils\QQMailer;

class Handle extends BaseHandle
{
    public function render(Exception $e)
    {
        $isTrue = $e instanceof ContryForbiddenExcepion;

        //跳过黑名单的异常报告
        if(!$isTrue && !config('app_debug')){
            (new QQMailer())->send(
                'chenyanpc@139.com',
                '服务器:'.$_SERVER['SERVER_ADDR'],
                $e->getMessage().' from url：'.Request::url(true)."\n".$e->getTraceAsString()."\n".'ip:'.Request::ip()
            );

            if($e instanceof ForbiddenException){
                return response($e->getMessage(), $e->getCode());
            }
        }

        // 其他错误交给系统处理
        return parent::render($e);
    }
}