<?php
namespace app\index\controller;

use app\index\model\Article as ModelArticle;


use app\index\logic\Article as LogicArticle;
use app\index\logic\Category as LogicCategory;
use app\index\logic\News as LogicNews;
use app\index\logic\Merchant as LogicMerchant;

class Index extends Base
{
    public function index()
    {
        $model_article = new ModelArticle();
        $list = $model_article->getList(['type'=>1]);
        foreach ($list as &$value1) {
            $value1['content'] = strip_tags(preg_replace('/&nbsp;/is', '', $value1['content']));
        }


        //$mlist = $model_article->getMList();
        $merchant_list = LogicMerchant::getList();
        //dump($merchant_list);
        foreach ($merchant_list as &$value2) {
            $value2['content'] = strip_tags(preg_replace('/&nbsp;/is', '', $value2['m_introduce']));
        }


        $recommend_list =LogicArticle::getRecomendList();
        $category_list =LogicCategory::getSortCategories();
        $newslist =LogicNews::getList();


        $this->assign('list', $list);
        $this->assign('mlist', $merchant_list);

        $this->assign('newslst', $newslist);
        $this->assign('recommend_list', $recommend_list);
        $this->assign('category_list', $category_list);
        return view();
    }
}
