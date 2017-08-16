<?php
namespace app\admin\controller;
use app\admin\model;
use think\Controller;
use think\View;
use think\Config;
use think\Session;
use think\Log;

class Weixin extends Controller
{
    public function index()
    {
		//print_r(Config::get('host'));die;
        $view = new View();
		//$view->assign('menu', Session::get('menu'));
		return $view->fetch('index/Weixin/index');
    }
	
	public function login()
	{
		$view = new View();
		return $view->fetch('index/public/login');
	}
	


	
	
	
}
