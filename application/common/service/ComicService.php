<?php
namespace app\common\service;

class ComicService extends BaseService
{
    /**
     * @param int $bid
     * @param int $curId
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function chapterNext($bid, $curId, $field = '')
    {
        $chapter = model('Chapters')
            ->field($field)
            ->where('bookid', $bid)
            ->where('id', '>', $curId)
            ->order('id asc')
            ->find();
        return $chapter;
    }

    /**
     * @param int $bid
     * @param int $curId
     * @return mixed
     * @throws 
     */
    public static function chapterPrev($bid, $curId, $field = '')
    {
        $chapter = model('Chapters')
            ->field($field)
            ->where('bookid', $bid)
            ->where('id', '<', $curId)
            ->order('id desc')
            ->find();
        return $chapter;
    }

    /**
     * @param int $zid
     * @throws
     */
    public static function getChapter($zid)
    {
        $chapter = model('Chapters')->scope('WithListField')->find($zid);
        return $chapter;
    }

    public static function imgById()
    {

    }
}