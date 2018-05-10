<?php
namespace app\admin\logic;

use think\Db;

class User
{
    public static function getUser($user_id)
    {
        return Db::name('user')->where('user_id',$user_id)->find();
    }
}
