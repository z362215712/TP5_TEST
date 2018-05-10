<?php
namespace app\index\logic;

use think\Db;

class ArticleStatistics
{
    public static function addLog($article_id,$ip_address)
    {
        $data = [
          'article_id'=>$article_id,
          'ip_address'=>$ip_address,
          'add_time'=>date("Y-m-d H:i:s")
        ];
        return Db::name("articleStatistics")
            ->insert($data);
    }
}
