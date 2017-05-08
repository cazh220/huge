<?php
namespace app\admin\controller;
use app\admin\model;
use think\Controller;
use think\View;
use think\Config;
use think\Session;
use think\Log;

class Index extends Controller
{
	static $verify = 0;
	
    public function index()
    {
		//print_r(Config::get('host'));die;
        $view = new View();
		return $view->fetch();
    }
	
	public function login()
	{
		$view = new View();
		return $view->fetch('index/public/login');
	}
	
	public function doLogin()
	{
		$username	= input('username');
		$password	= input('password');
		$code		= input('verify_code');
		
		$username 	= !empty($username) ? addslashes(trim($username)) : '';
		$password 	= !empty($password) ? addslashes(trim($password)) : '';
		$code 		= !empty($code) ? addslashes(trim($code)) : '';

		if(!captcha_check($code) && self::$verify == 1)
		{
			header("Content-type:text/html;charset=utf-8");
            exit("<script>alert('验证码错误！');window.history.go(-1);</script>");
		}
		
		$Admin = model('Admin');
		$admin_detail = $Admin->getAdmin(array('username'=>$username, 'password'=>md5($password)));
		//密码校验
		if (empty($admin_detail))
		{
			header("Content-type:text/html;charset=utf-8");
            exit("<script>alert('用户名或密码错误！');window.history.go(-1);</script>");
		}
		
		
		
		
		
	}
	
	
	
}
