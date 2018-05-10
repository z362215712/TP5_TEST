<?php
namespace app\admin\model;

use think\Model;

class Admin extends Model
{

    // 定义数据完成字段
    protected $insert = ['reg_time'];
    protected $table = 't_user';

    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }

    public function setRegTimeAttr()
    {
        return date('Y-m-d H:i:s');
    }

}
