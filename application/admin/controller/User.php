<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;

class User extends \app\admin\controller\Common
{
    private $page_count = 5;

    public function lst()
    {
        $keyword = Request::instance()->param('keyword', '');
        if (Request::instance()->isGet() && !empty($keyword)) {
            $data = Db::name('user')
                ->alias('u')
                ->field("user_id,avatar_url,u.reg_time,count(article_id) publish_count,nickname")
                ->join('article', 'publish_user_id=user_id')
                ->where([
                    'nickname' => ['like', "%$keyword%"],
                    'is_admin' => 0,
                ])
                ->order('user_id', 'DESC')
                ->paginate($this->page_count);
            $this->assign('user_list', $data);
            return view();
        } else {
            $data = Db::name('user')
                ->alias('u')
                ->field("user_id,avatar_url,u.reg_time,count(article_id) publish_count,nickname")
                ->join('article', 'publish_user_id=user_id', 'left')
                ->where('is_admin', 0)
                ->group('user_id')
                ->order('user_id', 'DESC')
                ->paginate($this->page_count);
            $this->assign('user_list', $data);
            return view();
        }
    }

    public function upgrade()
    {
        $id = request()->param('id');
        Db::name('user')->where('user_id', $id)->update([
            'is_admin' => 1
        ]);
        return true;
    }

    public function reg()
    {
        if (request()->isPost()) {
            $mobile = Request::instance()->param('mobile', '');
            $login_passwd = Request::instance()->param('passwd', '');
            $nickname = Request::instance()->param('nickname', '');
            $sex = Request::instance()->param('sex', 1);
            $file = request()->file('file');

            // 移动到框架应用根目录/public/uploads/ 目录下
            if ($file) {
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                if (!$info) {
                    // 上传失败获取错误信息
                    return json($file->getError());
                } else {
                    $avatar_url = '/uploads' . DS . date('Ymd') . DS . $info->getFilename();
                }
            }
            $data = [
                'mobile' => $mobile,
                'passwd' => $login_passwd,
                'nickname' => $nickname,
                'sex' => $sex,
                'reg_time' => date(('Y-m-d H:i:s')),
                'is_admin' => 0,
                'user_status' => 1,
            ];
            if (!empty($avatar_url)) {
                $data['avatar_url'] = $avatar_url;
            }
            Db::name('user')->insert($data);
            return $this->redirect('/admin/user/lst');
        }
        return view();

    }


    public function remove()
    {
        $id = request()->param('id');
        Db::name('user')->where('user_id', $id)->update([
            'user_status' => 0
        ]);
        return true;
    }
}
