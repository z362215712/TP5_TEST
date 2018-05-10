<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Log;
use think\Request;
use think\Session;

class Article extends Common
{
    private $page_count = 10;

    /*
     * 登录跳转后台
     */
    public function index()
    {
        if ($this->user_info['user_type'] > 0) {
            return $this->fetch("index1");
        }

        //左侧列表的统计
        $sql = "SELECT as.article_id,count(*) view_count,as.add_time,title from t_article_statistics as `as` 
        left join t_article a on a.article_id=as.article_id
        where as.add_time>='" . date('Y-m-d') . "'
        group by as.article_id
        order by view_count desc
        limit 5
        ";
        $statistics = Db::query($sql);


        $total_view_count = Db::name('articleStatistics')
            ->where([
                "add_time" => ["egt", date('Y-m-d')]
            ])
            ->count();

        $pv = Db::name("statistics")
            ->where([
                "addtime" => ["egt", date('Y-m-d')]
            ])
            ->count();

        $total_user_count = 10;
        $new_user_count = 1;
        $click_count = 15;


        $this->assign('article_list', $statistics);
        $this->assign('pv_count', $pv);
        $this->assign('total_user_count', $total_user_count);
        $this->assign('new_user_count', $new_user_count);
        $this->assign('click_count', $click_count);
        //$this->assign('uv_count', $uv);
        $this->assign('total_view_count', $total_view_count);
        return view();
    }


    public function index1()
    {
        return view();
    }

    public function getFlow()
    {
        return [
            'ip' => [
                [0, 10], [1, 8], [2, 5], [3, 10], [4, 4], [5, 16], [6, 5], [7, 30], [8, 48], [9, 56],
                [10, 70], [11, 79], [12, 100], [13, 123], [14, 183], [15, 203], [16, 186], [17, 141], [18, 150], [19, 110],
                [20, 91], [21, 88], [22, 60], [23, 30]
            ],
            'users' => [
                [0, 1], [1, 0], [2, 2], [3, 0], [4, 1], [5, 3], [6, 1], [7, 5], [8, 2], [9, 3], [10, 2], [11, 1], [12, 0], [13, 2], [14, 8], [15, 0], [16, 0]
            ]
        ];
    }

    public function lst()
    {
        $keyword = Request::instance()->param('keyword', '');
        $status = Request::instance()->param('status', '');
        if (Request::instance()->isGet() && !empty($keyword)) {
            $where = ['title' => ['like', "%$keyword%"],];

        } elseif (Request::instance()->isGet() && $status != '') {
            $where = ['article_status' => $status];

        } else {
            $where = ['article_status' => 0];
        }

        $user_id = session("user_id");
        $user_info = Db::name("user")->where("user_id", $user_id)->find();
        if ($user_info['user_type'] != 0) {
            $where['publish_user_id'] = $user_id;
        }

        $article_list = Db::name('article')->alias('a')
            ->join('user u', 'u.user_id=a.publish_user_id')
            ->where($where)
            ->order('article_id', 'DESC')
            ->paginate($this->page_count);

        $this->assign('article_list', $article_list);
        $this->assign('status', $status);
        return view('article/lst1');
    }

    public function mlst()
    {
        $keyword = Request::instance()->param('keyword', '');
        $status = \request()->param("status", 0);
        $user_id = session("user_id");
        $user = Db::name("user")->where("user_id", $user_id)->find();

        if (!empty($keyword)&&$user['user_type']>0) {
            $where = [
                "article_status" => $status,
                'title' => ['like', "%$keyword%"],
                'article_status' => 0,
                "m.user_id" => $user_id
            ];

        }elseif(!empty($keyword)&&$user['user_type']==0){
            $where = [
                "article_status" => $status,
                'title' => ['like', "%$keyword%"],
                'article_status' => 0,
            ];

        } elseif ($user['user_type'] == 0) {
            $where = [
                "article_status" => $status,
            ];

        } else {

            $where = [
                "article_status" => $status,
                "m.user_id" => $user_id,
            ];
        }


        $article_list = Db::name('marticle')->alias('a')
            ->join('user u', 'u.user_id=a.publish_user_id')
            ->join("merchant m ", "m.user_id=u.user_id")
            ->where($where)
            ->order('ma_id', 'DESC')
            ->paginate($this->page_count);

        $this->assign('article_list', $article_list);
        return view();
    }


    public function newslst()
    {
        $keyword = Request::instance()->param('keyword', '');
        $status = Request::instance()->param('status', '');
        if (Request::instance()->isGet() && !empty($keyword)) {
            $where = [
                'title' => ['like', "%$keyword%"],
            ];
            $order = ['news_id' => 'DESC'];

        } elseif (Request::instance()->isGet() && $status != '') {
            $where = ['article_status' => $status,];
            $order = ['news_id' => 'DESC'];

        } else {
            $where = ['article_status' => 0,];
            $order = ['news_id' => 'DESC'];

        }
        $article_list = Db::name('news')
            ->alias('n')
            ->join('user u', 'u.user_id=n.publish_user_id')
            ->where($where)
            ->order($order)
            ->paginate($this->page_count);

        $this->assign('article_list', $article_list);
        $this->assign('status', $status);
        return view('article/newslst');
    }

    public function recycle()
    {
        $article_list = Db::name('article')->alias('a')
            ->join('user u', 'u.user_id=a.publish_user_id')
            ->where('article_status', 2)
            ->order('article_id', 'DESC')
            ->paginate($this->page_count);
        $this->assign('article_list', $article_list);
        return view();
    }

    public function mpublish()
    {
        if (request()->isPost()) {
            $title = request()->param('title');
            $add_time = request()->param('pubtime');
//            $publish_user_name = request()->param('username');
            $file = request()->file('file');
            $content = request()->param('content');
            $longitude = request()->param('longitude');
            $latitude = request()->param('latitude');
            $address = request()->param('address');

            // 移动到框架应用根目录/public/uploads/ 目录下
            if ($file) {
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                if (!$info) {
                    // 上传失败获取错误信息
                    return json($file->getError());
                } else {
                    $cover_image = '/uploads' . DS . date('Ymd') . DS . $info->getFilename();
                }
            }

            $user = Db::name("user")->where("user_id", session("user_id"))->find();

            $result = Db::name('marticle')->insert([
                'title' => $title,
                'add_time' => $add_time,
                #'merchant_name' => $publish_user_name,
                'cover_image' => $cover_image,
                'content' => $content,
                'publish_user_id' => Session::get('user_id'),
                'publish_user_name' => $user['nickname'],
                'add_time' => date('Y-m-d H:i:s'),
                'verify_time' => date('Y-m-d H:i:s'),
                'longitude' => $longitude,
                'latitude' => $latitude,//纬度
                'address' => $address,//纬度
            ]);
            Log::info(print_r($result, true));
            if ($result) {
                return true;
            }
            return false;
        }


        $merchant = Db::name("merchant")->where([
            "user_id" => session("user_id")
        ])
            ->find();
        $this->assign("merchant", $merchant);
        return view();
    }


    public function publish()
    {
        if (request()->isPost()) {
            $title = request()->param('title');
            $add_time = request()->param('pubtime');
            $publish_user_name = request()->param('username');
            $file = request()->file('file');
            $content = request()->param('content');
            $mrsc = request()->param('mrsc');//每日三餐
            $type = request()->param('type');

            // 移动到框架应用根目录/public/uploads/ 目录下
            if ($file) {
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                if (!$info) {
                    // 上传失败获取错误信息
                    return json($file->getError());
                } else {
                    $cover_image = '/uploads' . DS . date('Ymd') . DS . $info->getFilename();
                }
            }

            $result = Db::name('article')->insert([
                'title' => $title,
                'add_time' => $add_time,
                'publish_user_name' => $publish_user_name,
                'cover_image' => $cover_image,
                'content' => $content,
                'publish_user_id' => Session::get('user_id'),
                'add_time' => date('Y-m-d H:i:s'),
                'verify_time' => date('Y-m-d H:i:s'),
                'mrsc' => $mrsc,
                'c_id' => $type,
            ]);
            Log::info(print_r($result, true));
            if ($result) {
                return true;
            }
            return false;
        }
        return view();
    }

    /*public function del()
    {
        $id = request()->param('id');
        Db::name('article')
            ->where('article_id', $id)
            ->update(['article_status' => 2]);
        return true;
    }*/

    public function verify()
    {
        $act = request()->param('act');
        $id = request()->param('id');
        if ($act == 'del') {
            $data = ['article_status' => 2];
        } elseif ($act == 'up') {
            $data = ['article_status' => 1];
        } elseif ($act == 'down') {
            $data = ['article_status' => 0];
        } else {
            return false;
        }

        Db::name('article')->where('article_id', $id)->update($data);
        return true;
    }

    /**
     * 编辑文章
     * @return bool|\think\response\View
     */
    public function edit()
    {
        if (request()->isPost()) {
            $id = request()->param('id');
            $title = request()->param('title');
            $add_time = request()->param('pubtime');
            $publish_user_name = request()->param('username');
            $content = request()->param('content');
            $file = request()->file('file');
            $mrsc = request()->param('mrsc');//每日三餐
            $type = request()->param('type');

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
                'title' => $title,
                'add_time' => $add_time,
                'publish_user_name' => $publish_user_name,
                'content' => $content,
                'publish_user_id' => Session::get('user_id'),
                'mrsc' => $mrsc,
                'c_id' => $type,
            ];
            if (!empty($cover_image)) {
                $data['cover_image'] = $cover_image;
            }

            $result = Db::name('article')
                ->where('article_id', $id)->update($data);
            Log::info(print_r($result, true));
            if ($result) {
                return true;
            }
            return false;
        } else {
            $id = request()->param('id');
            $data = Db::name('article')
                ->where('article_id', $id)
                ->join('user u ', 'publish_user_id = user_id')
                ->find();
            $this->assign('data', $data);
            return view();
        }
        //$this->fetch('/article/edit');
    }


    public function newsverify()
    {
        $act = request()->param('act');
        $id = request()->param('id');
        if ($act == 'del') {
            $data = ['article_status' => 2];
        } elseif ($act == 'up') {
            $data = ['article_status' => 1];
        } elseif ($act == 'down') {
            $data = ['article_status' => 0];
        } else {
            return false;
        }

        Db::name('news')->where('news_id', $id)->update($data);
        return true;
    }

    /**
     * 编辑文章
     * @return bool|\think\response\View
     */
    public function newsedit()
    {
        if (request()->isPost()) {
            $id = request()->param('id');
            $title = request()->param('title');
            $add_time = request()->param('pubtime');
            $publish_user_name = request()->param('username');
            $content = request()->param('content');

            $data = [
                'title' => $title,
                'add_time' => $add_time,
                'publish_user_name' => $publish_user_name,
                'article_status' => 0,
                'content' => $content,
                'publish_user_id' => Session::get('user_id'),
            ];
            if (!empty($cover_image)) {
                $data['cover_image'] = $cover_image;
            }

            $result = Db::name('news')
                ->where('news_id', $id)->update($data);
            Log::info(print_r($result, true));
            if ($result) {
                return true;
            }
            return false;
        } else {
            $id = request()->param('id');
            $data = Db::name('news')
                ->where('news_id', $id)
                ->join('user u ', 'publish_user_id = user_id')
                ->find();
            $this->assign('data', $data);

            return view();
        }
        //$this->fetch('/article/edit');
    }


    //美食资讯
    public function newspublish()
    {
        if (request()->isPost()) {
            $title = request()->param('title');
            $add_time = request()->param('pubtime');
            $publish_user_name = request()->param('username');
            $content = request()->param('content');


            $result = Db::name('news')->insert([
                'title' => $title,
                'add_time' => $add_time,
                'publish_user_name' => $publish_user_name,
                'content' => $content,
                'publish_user_id' => Session::get('user_id'),
                'add_time' => date('Y-m-d H:i:s'),
                'verify_time' => date('Y-m-d H:i:s'),
                'article_status' => '0',
            ]);

            Log::info(print_r($result, true));
            if ($result) {
                return true;
            }
            return false;
        }
        return view();
    }

    public function mverify()
    {
        $act = request()->param('act');
        $id = request()->param('id');
        if ($act == 'del') {
            $data = ['article_status' => 2];
        } elseif ($act == 'up') {
            $data = ['article_status' => 1];
        } elseif ($act == 'down') {
            $data = ['article_status' => 0];
        } else {
            return false;
        }

        Db::name('marticle')->where('ma_id', $id)->update($data);
        return true;
    }

    public function medit()
    {
        if (request()->isPost()) {
            $id = request()->param('id');
            $title = request()->param('title');
            $add_time = request()->param('pubtime');
            $m_name = request()->param('m_name');
            $content = request()->param('content');
            $file = request()->file('file');
            $longitude = request()->param('longitude', "");
            $latitude = request()->param('latitude', "");
            $address = request()->param('address', "");

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
                'title' => $title,
                //'add_time' => $add_time,
                //'publish_user_id' => session("user_id"),
                'content' => $content,
//                'longitude' => $longitude,
//                'latitude' => $latitude,//纬度
                //'address' => $address,
            ];
            if (!empty($cover_image)) {
                $data['cover_image'] = $cover_image;
            }
            if (!empty($longitude)) {
                $data['longitude'] = $longitude;
                $data['latitude'] = $latitude;
                $data['address'] = $address;
            }

            $result = Db::name('marticle')
                ->where('ma_id', $id)->update($data);
            Log::info(print_r($result, true));
            if ($result) {
                return true;
            }
            return false;
        } else {
            $id = request()->param('id');
            $data = Db::name('marticle')
                ->alias("ma")
                ->join("merchant m", "m.user_id=ma.publish_user_id")
                ->where('ma_id', $id)
                ->join('user u ', 'publish_user_id = u.user_id')
                ->find();
            $this->assign('data', $data);
            return view();
        }
        //$this->fetch('/article/edit');
    }
}
