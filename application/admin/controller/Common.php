<?php
namespace app\admin\controller;

use app\admin\model\Logvisit;
use app\admin\Logic\User as LogicUser;
use think\Controller;
use think\Db;
use think\Request;
use think\Session;

class Common extends Controller
{
    protected $user_info ;
    public function _initialize()
    {
//        Session::set('user_id',1);
        $user_id = Session::get('user_id');
        if(!empty($user_id)){

            $this->user_info =LogicUser::getUser($user_id);
            $this->assign('user',$this->user_info);
        }
        //$this->LogVisit();
    }

    /**
     * 添加日志
     */
    public function LogVisit()
    {
        $LogVisit=new Logvisit();
        $ip=$_SERVER['REMOTE_ADDR'];
        $res=$LogVisit->get(['visit_time'=>date('Y-m-d'),'ip_address'=>$ip]);
        if(empty($res)){
            $LogVisit->save(['ip_address'=>$ip]);
        }
    }



}
