<?php
namespace app\admin\controller;
use think\Controller;
use think\View;
use think\Db;

class Admin
{
    public function index()
    {
		$data = Db::query('select * from hg_admin_users');

        $view = new View();
		$view->assign('data', $data);
		return $view->fetch('index/admin/index');
    }
	
	public function add()
	{
		$view = new View();
		//$view->assign('data', $data);
		return $view->fetch('index/admin/add');
		//$res = Db::execute('insert into hg_users(user, name, create_time, credits)values(:user, :name, :create_time, :credits)', ['user'=>'baoyu', 'name'=>'����', 'create_time'=>date('Y-m-d H:i:s', time()), 'credits'=>50]);
	}
	
	//ajax
	public function check_username()
	{
		$username = !empty($_POST['username']) ? trim($_POST['username']) : '';
		$data = Db::query("select count(*) as 'count' from hg_admin_users where username = :username", ['username' => $username]);
		$count = !empty($data[0]['count']) ? $data[0]['count'] : 0;
		//print_r($return);die;
		if ($count > 0)
		{
			$return = array('status'=>1, 'message'=>'账号已存在,请换一个');
			exit(json_encode($return));
		}
		else
		{
			$return = array('status'=>0, 'message'=>'ok');
			
			exit(json_encode($return));
		}
	}
	
	
}