<?php
namespace app\admin\controller;
use think\Controller;
use think\View;
use think\Session;

class Patient
{
    public function index()
    {
		echo "Test";die;11
		//筛选参数
		$keyword = input("keyword");
		$dental = input("dental");
		$hospital = input("hospital");
		$keyword = !empty($keyword) ? addslashes(trim($keyword)) : '';
		$dental = !empty($dental) ? addslashes(trim($dental)) : '';
		$hospital = !empty($hospital) ? addslashes(trim($hospital)) : '';
		$param = array(
			'keyword'	=> $keyword,
			'dental'	=> $dental,
			'hospital'	=> $hospital
		);
		
		$User = Model("User");
		$res = $User->user_list($param);
		$page = $res->render();
		
		$data = $res->toArray();
		$view = new View();
		$view->assign('user', $data);
		$view->assign('page', $page);
		return $view->fetch('index/user/index');
    }
	
	public function add()
	{
		$year = range(1920,2050);
		$month = range(1,12);
		$day = range(1,31);
		$view = new View();
		$view->assign('year', $year);
		$view->assign('month', $month);
		$view->assign('day', $day);
		return $view->fetch('index/user/add');
	}

	
}