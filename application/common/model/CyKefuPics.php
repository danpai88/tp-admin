<?php
namespace app\common\model;

use think\Model;

/**
 * Class CyKefuPics
 * @package app\common\model
 *
  * @property integer id 
 * @property string title 消息标题
 * @property string image 图片链接
 * @property string description 图片链接
 * @property string url 链接
 * @property string created_at 
 */
class CyKefuPics extends Model
{
    protected $table = 'cy_kefu_pics';
}