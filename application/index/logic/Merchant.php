<?php

namespace app\index\logic;

use think\Db;
use think\Model;

class Merchant
{
    public static function getList()
    {
        return Db::name("merchant")
            ->limit(0,3)
            ->order("m_id","DESC")
            ->select();
    }

}
