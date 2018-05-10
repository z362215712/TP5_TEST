<?php

namespace app\index\logic;

use think\Db;
use think\Model;

class Category
{
    public static function getSortCategories()
    {
        $list = self::getCategories();
        $sort_list = [];
        foreach ($list as $c) {
            if ($c['parent_id'] == 0) {
                $sort_list[$c['c_id']] = $c;
            } else {
                $sort_list[$c['parent_id']]['child'][] = $c;
            }
        }
        return $sort_list;

    }

    public static function getCategories()
    {
        return Db::name("category")
            ->order('parent_id')
            ->select();

    }


}
