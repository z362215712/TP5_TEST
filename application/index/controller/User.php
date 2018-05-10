<?php
namespace app\index\controller;

use app\index\model\Article as ModelArticle;
use think\Controller;
use think\Db;

class User extends  Base
{
    public function merchant_lst()
    {
        $lst = Db::name('merchant')
            ->alias('m')
            ->join('user u','u.user_id=m.user_id','left')
            ->order('become_time','DESC')
            ->select();
        $this->assign('list',$lst);
        return view();
    }

    public function mdetail()
    {
        $merchant_id = request()->param('m_id');
        $merchant = Db::name('merchant')
            ->alias('m')
            ->join('user u','u.user_id=m.user_id','left')
            ->where('m_id',$merchant_id)
            ->order('become_time','DESC')
            ->find();
        //$model_article= new ModelArticle();
        //$where=['publish_user_id'=>$merchant['user_id']];
        //$lst = $model_article->getList($where);

        $where =[
            "publish_user_id"=>$merchant['user_id'],
            "article_status"=>1
        ];
        $lst = Db::name("marticle")->where($where)->limit(0,5)->order("ma_id","DESC")->select();


        foreach ($lst as &$value3) {
            $value3['content'] = strip_tags(preg_replace('/&nbsp;/is', '', $value3['content']));
        }

        $this->assign('merchant',$merchant);
        $this->assign('list',$lst);

        return view();
    }

    /*
     * 店家排行
     */
    public function mrank()
    {
        $merchant_list = Db::name('merchant')
            ->field('click_count,m_name,m_introduce,cover_image,m_id')
            ->alias('m')
            ->order('click_count','DESC')
            ->select();
        $this->assign('list',$merchant_list);
        return view();
    }

    /**
     *人气用户排行
     * @return \think\response\View
     */
    public function urank()
    {
        $merchant_list = Db::name('article')
            ->field('sum(click_count) cc,nickname,user_id,avatar_url,introduce,sex')//
            ->alias('a')
            ->join('user u','u.user_id = a.publish_user_id')
            ->group('publish_user_id')
            ->order('cc','DESC')
            ->select();
        $this->assign('list',$merchant_list);
        return view();
    }
}
