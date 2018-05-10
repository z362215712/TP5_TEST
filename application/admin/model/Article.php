<?php
namespace app\admin\model;

use think\Model;

class Article extends Model
{

    // 定义数据完成字段
    protected $insert = ['publish_time', 'pv', 'uv'];
    
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }

    public function setPublishTimeAttr()
    {
        return date('Y-m-d H:i:s');
    }

    public function setPvAttr()
    {
        return '0';
    }

    public function setUvAttr()
    {
        return '0';
    }

    public function getStatusAttr($value)
    {
        $status = ['0' => '未审核', '1' => '未通过', '2' => '已通过'];
        return $status[$value];
    }



    public function getList($page,$where)
    {
        return $this
            ->join('user','user_id=publish_user_id')
            ->where($where)
            ->order('status')
            ->paginate($page);
    }
}
