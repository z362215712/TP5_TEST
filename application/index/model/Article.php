<?php
namespace app\index\model;

use think\Db;
use think\Model;

class Article extends Model
{
    public function getList(array $where = [])
    {
        if (empty($where)) {
            $where = ['article_status' =>1];
        }else{
            $where['article_status']=1;
        }
        return Db::name('article')->alias('a')
            ->join('user u', 'u.user_id=publish_user_id')
            ->where($where)
            ->order('verify_time', 'DESC')
            ->select();


    }


    public function getMList(array $where = [])
    {
        if (empty($where)) {
            $where = ['article_status' =>1];
        }else{
            $where['article_status']=1;
        }
        return Db::name('marticle')->alias('a')
            ->join('user u', 'u.user_id=publish_user_id')
            ->where($where)
            ->order('verify_time', 'DESC')
            ->select();

//        return Db::name('merchant')->alias('a')
//            ->join('user u', 'u.user_id = a.user_id')
//            ->order("m_id","desc")
//            ->limit(0,5)
//            //->order('verify_time', 'DESC')
//            ->select();
    }

    /*public function getListByUserid($user_id)
    {
        $where = [
            'article_status' => 1,
            'publish_user_id' => $user_id,
        ];
        return Db::name('article')->alias('a')
            ->join('user u', 'u.user_id=publish_user_id')
            ->where($where)
            ->order('verify_time', 'DESC')
            ->select();
    }*/

    public function getOne($article_id)
    {
        return Db::name('article')->alias('a')
            ->join('user u', 'user_id=publish_user_id')
            ->where([
                'article_id' => $article_id
            ])
            ->find();
    }
    public function getMOne($article_id)
    {
        return Db::name('marticle')->alias('a')
            ->join('user u', 'user_id=publish_user_id')
            ->join('merchant m','m.user_id = a.publish_user_id')
            ->where([
                'ma_id' => $article_id
            ])
            ->find();
    }
}
