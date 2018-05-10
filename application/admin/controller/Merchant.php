<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Exception;
use think\Log;
use think\Request;
use think\Session;

class Merchant extends Common
{

    private $page_count = 5;

    public function reg()
    {
        if (\request()->isPost()) {
            Log::info(print_r($_REQUEST, true));
            Log::info(print_r($_FILES, true));

            $merchant_name = Request::instance()->param('merchant_name', '');
            $address = Request::instance()->param('address', '');
            $mobile = Request::instance()->param('mobile', '');
            $merchant_introduce = Request::instance()->param('merchant_introduce', '');
            $user_introduce = Request::instance()->param('user_introduce', '');
            $logo_file = request()->file('merchant_logo');

            $login_passwd = Request::instance()->param('passwd', '');
            $nickname = Request::instance()->param('nickname', '');
            $sex = Request::instance()->param('sex', 1);
            $avatar_file = request()->file('avatar');
            foreach ([$logo_file, $avatar_file] as $file) {
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                if (!$info) {
                    // 上传失败获取错误信息
                    return json($file->getError());
                } else {
                    $file_path[] = '/uploads' . DS . date('Ymd') . DS . $info->getFilename();
                }
            }

            //Db::startTrans();
            try {
                //插入用户
                $data = [
                    'mobile' => $mobile,
                    'passwd' => $login_passwd,
                    'nickname' => $nickname,
                    'avatar_url' => $file_path[1],
                    'sex' => $sex,
                    'reg_time' => date(('Y-m-d H:i:s')),
                    'is_admin' => 0,
                    'user_status' => 1,
                    "introduce" => $user_introduce,
                    "user_type" => 1,
                ];
                Db::name('user')->insert($data);

                $user_id = Db::name("user")->getLastInsID();
                //插入用户
                $data = [
                    'contact' => $mobile,
                    'm_name' => $merchant_name,
                    'address' => $address,
                    'm_introduce' => $merchant_introduce,
                    'become_time' => date(('Y-m-d H:i:s')),
                    'user_id' => $user_id,
                    'cover_image' => $file_path[0],
                ];
                Db::name('merchant')->insert($data);

            } catch (Exception $exception) {
                Log::info("ERROR:" . print_r($exception->getMessage(), true));
                //Db::rollback();
            }

            return $this->redirect("article/mpublish");


        }
        return view();
    }


    public function lst()
    {
        $keyword = Request::instance()->param('keyword', '');
        $fields ="count(*) publish_count,m_name,nickname,become_time,m_id,m.cover_image";
        if (Request::instance()->isGet() && !empty($keyword)) {
            $data = Db::name('merchant')
                ->field($fields)
                ->alias('m')
                ->join("user u", "u.user_id=m.user_id")
                ->join("marticle a", "a.publish_user_id=m.user_id","left")
                ->where([
                    "m_name" => ["like", "%$keyword%"]
                ])
                ->group("m.user_id")
                ->paginate($this->page_count);
        } else {
            $data = Db::name('merchant')
                ->field($fields)
                ->alias('m')
                ->join("user u", "u.user_id=m.user_id")
                ->join("marticle a", "a.publish_user_id=m.user_id","left")
                ->group("m.user_id")
                ->paginate($this->page_count);
        }
        $this->assign('merchant_list', $data);
        return view();
    }

}
