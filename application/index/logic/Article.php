<?php
namespace app\index\logic;

use think\Db;

class Article
{
    public static function getRecomendList()
    {
        return Db::name("recommend")
            ->alias("c")
            ->join("article a","c.article_id=a.article_id")
            ->limit(0,5)
            ->select();
    }
}
