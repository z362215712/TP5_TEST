<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;

class Admin extends Common
{
    private $salt = 'asd';
    private $page_count = 2;

    public function login()
    {
        if (Request::instance()->isPost()) {
            $mobile = Request::instance()->param('mobile', '');
            $login_passwd = Request::instance()->param('passwd', '');
            if (empty($login_passwd)) {
                return json('参数缺失');
            }

            /*$mobile = '18218526325';
            $login_passwd = 'qweqwe';
            $nickname = 'yoga';*/

            $user_info = Db::name('user')->where([
                'mobile' => $mobile,
            ])->find();
            if (empty($user_info)) {
                return json('不存在该用户');

            } elseif ($user_info['passwd'] == $login_passwd) {
                Session::set('user_id', $user_info['user_id']);
                return json(true);

            } else {
                return json('账号或密码错误');

            }

            $user_id = Session::get('user_id');
            if (!empty($user_id)) {
                $user_info = LogicUser::getUser($user_id);
                $this->assign('user', $user_info);
            }

        }
        return view();

    }


    public function register()
    {
        if (Request::instance()->isPost()) {
            $mobile = Request::instance()->param('mobile', '');
            $login_passwd = Request::instance()->param('password', '');
            $sex = Request::instance()->param('sex', 0);
            $nickname = Request::instance()->param('nickname', "");


            $manager = db('User')->where('mobile', $mobile)->find();
            if ($manager !== Null) {
                return '该账号已注册';
            }


            if (empty($nickname)) {
                return '请填写昵称';
            }

            // 移动到框架应用根目录/public/uploads/ 目录下
            if (!empty($file)) {
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                Log::info(print_r($info, true));
                if (!$info) {
                    // 上传失败获取错误信息
                    return json($file->getError());
                } else {
                    $cover_image = '/uploads' . DS . date('Ymd') . DS . $info->getFilename();
                }
            }


            $data = [
                'mobile' => $mobile,
                'passwd' => $login_passwd,
                'nickname' => $nickname,
                'sex' => $sex,
                'introduce' => "吃货一枚",
                "reg_time" => date("Y-m-d H:i:s"),
                "user_status" => 1,
                "user_type"=>2,
            ];

            if (!empty($cover_image)) {
                $data['avatar_url'] = $cover_image;
            } else {
                $data['avatar_url'] = "/f_static/images/default.jpeg";
            }


            $user_id = Db::name('user')->insert($data);
            if ($user_id > 0) {
                return true;
            } else {
                return '添加失败';
            }
        }
        return view();

    }

    public function cancel()
    {
        $id = request()->param('id');
        Db::name('user')->where('user_id', $id)->update([
            'is_admin' => 0
        ]);
        return true;
    }

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
                    'is_admin' => 1,
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
                ->where('is_admin', 1)
                ->group('user_id')
                ->order('user_id', 'DESC')
                ->paginate($this->page_count);
            $this->assign('user_list', $data);
            return view();
        }
    }

    public function update()
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
                'is_admin' => 1,
                'user_status' => 1,
            ];
            if (!empty($avatar_url)) {
                $data['avatar_url'] = $avatar_url;
            }
            Db::name('user')->insert($data);
            return $this->redirect('/admin/admin/lst');
        }
        $user_id = request()->param('id');
        $user = Db::name('user')->where('user_id', $user_id)->find();
        $this->assign('user', $user);

        return view();
    }
}
