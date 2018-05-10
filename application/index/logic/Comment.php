<?php

namespace app\index\logic;

use think\Db;
use think\Model;

class Comment
{
    public static function getList($article_id)
    {
        return Db::name("comment")
            ->alias("c")
            ->join("user u",'u.user_id=c.user_id')
            ->where([
                'article_id'=>$article_id
            ])
            ->select();

    }

}
