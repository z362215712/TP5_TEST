<?php

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;

class Base extends Controller
{
    public function _initialize()
    {
        if (\request()->baseFile() != "favicon.ico") {
            Db::name("statistics")->insert([
                "path" => \request()->url(),
                "address" => $_SERVER['REMOTE_ADDR'],
                "addtime" => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
