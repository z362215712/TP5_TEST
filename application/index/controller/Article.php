<?php
namespace app\index\controller;

use app\index\model\Article as ModelArticle;
use app\index\logic\Comment as LogicComment;
use app\index\logic\ArticleStatistics as LogicArticleStatistics;
use think\Controller;
use think\Db;

class Article extends Base
{
    public function lst()
    {
        $category_id = request()->param('cid');
        $category_info = Db::name('category')->where('c_id', $category_id)->find();
        $model_article = new ModelArticle();
        $list = $model_article->getList(['c_id' => $category_id]);
        foreach ($list as &$value3) {
            $value3['content'] = strip_tags(preg_replace('/&nbsp;/is', '', $value3['content']));
        }

        //排行榜
        $rank_list = Db::name('article')->
        where([
            'c_id' => $category_id,
            "article_status"=>1
        ])
            ->order('click_count', 'DESC')
            ->limit(0, 8)
            ->select();
        foreach ($rank_list as &$value) {
            $value['content'] = mb_substr(strip_tags(preg_replace('/&nbsp;/is', '', $value['content'])), 0, 20);
        }

        $this->assign('list', $list);
        $this->assign('category_info', $category_info);
        $this->assign('rank_list', $rank_list);
        return view();
    }

    public function detail()
    {
        $article_id = request()->param('id');
        //增添文章的点击数
        Db::name('article')->where('article_id', $article_id)->setInc('click_count');
        //增添记录
        LogicArticleStatistics::addLog($article_id,$_SERVER['SERVER_ADDR']);


        $model_article = new ModelArticle();
        $article_info = $model_article->getOne($article_id);

        //增添商家的点击数
        Db::name('merchant')
            ->alias('m')
            ->join('user u', 'm.user_id = u.user_id', 'right')
            ->where('u.user_id', $article_info['publish_user_id'])->setInc('click_count');

        $comment_list = LogicComment::getList($article_id);

        $this->assign('article', $article_info);
        $this->assign('comment_list', $comment_list);
        return view();
    }

    /**
     * 新闻详情
     * @return \think\response\View
     */
    public function news()
    {
        $news_id = request()->param('id');
        //增添文章的点击数
        Db::name('news')->where('news_id', $news_id)->setInc('click_count');
        $news_info = Db::name("news")
            ->alias("n")
            ->join("user u ","u.user_id=n.publish_user_id")
            ->where(["news_id"=>$news_id])->find();

        $comment_list = LogicComment::getList($news_id);

        $this->assign('article', $news_info);
        $this->assign('comment_list', $comment_list);
        return view();
    }

    public function mdetail()
    {
        $article_id = request()->param('id');
        //增添文章的点击数
        Db::name('marticle')->where('ma_id', $article_id)->setInc('click_count');
        $model_article = new ModelArticle();
        $article_info = $model_article->getMOne($article_id);
        //增添商家的点击数
        Db::name('merchant')
            ->alias('m')
            ->join('user u', 'm.user_id = u.user_id', 'right')
            ->where('u.user_id', $article_info['publish_user_id'])->setInc('click_count');
        $this->assign('article', $article_info);
        return view();
    }

    /*
     * 人气美食排行
     */
    public function rank()
    {
        $article_list = Db::name('article')
            ->field('article_id,click_count,title,add_time,content,cover_image')
            ->order('click_count', 'DESC')
            ->select();
        foreach ($article_list as &$value) {
            $value['content'] =trim(strip_tags(preg_replace('/&nbsp;/is', '', $value['content'])));
        }

        $this->assign('list', $article_list);
        return view();
    }

    public function mrsclst()
    {
        $mrsc_id = request()->param('mrscid');
        $where =[
            'mrsc'=>$mrsc_id,
            'article_status'=>1,
        ];
        $model_article = new ModelArticle();
        $list = $model_article->getList($where);
        foreach ($list as &$value3) {
            $value3['content'] = strip_tags(preg_replace('/&nbsp;/is', '', $value3['content']));
        }

        //排行榜
        $rank_list = Db::name('article')->where([
            'mrsc' => $mrsc_id,
            'article_status' => 1,
        ])
            ->order('click_count', 'DESC')
            ->limit(0, 8)
            ->select();
        foreach ($rank_list as &$value) {
            $value['content'] = mb_substr(strip_tags(preg_replace('/&nbsp;/is', '', $value['content'])), 0, 20);
        }

        $category_info = Db::name('mrsc')->where('c_id', $mrsc_id)->find();
        $this->assign('list', $list);
        $this->assign('category_info', $category_info);
        $this->assign('rank_list', $rank_list);
        return view('article/lst');
    }
}
