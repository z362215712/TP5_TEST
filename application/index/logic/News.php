<?php

namespace app\index\logic;

use think\Db;
use think\Model;

class News
{
    public static function getList()
    {
        return Db::name("news")
            ->where([
                "article_status"=>1
            ])
            ->order("news_id", "desc")
            ->limit(0, 7)
            ->select();

    }

}
