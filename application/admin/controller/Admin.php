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
	
	public function add_admin_user()
	{
		$username = !empty($_POST['username']) ? trim($_POST['username']) : '';
		$password = !empty($_POST['password']) ? trim($_POST['password']) : '';
		$role_id = !empty($_POST['role_id']) ? intval($_POST['role_id']) : 0;
		$mobile = !empty($_POST['mobile']) ? trim($_POST['mobile']) : '';
		$status = !empty($_POST['status']) ? intval($_POST['status']) : 0;
		$node	= !empty($_POST['node']) ? $_POST['node'] : '';
		
		$action_list = !empty($node) ? json_encode($node) : '';
		$add_time = date("Y-m-d H:i:s", time());
		$last_login_time = date("Y-m-d H:i:s", time());
		$last_ip = $_SERVER['REMOTE_ADDR'];
		/*
		$params = [
			'username'			=> '"$username"',
			'password'			=> md5($password),
			'role_id'			=> $role_id,
			'mobile'			=> $mobile,
			'status'			=> $status,
			'action_list'		=> !empty($node) ? json_encode($node) : '',
			'is_frozen'			=> 0,
			'add_time'			=> date("Y-m-d H:i:s", time()),
			'last_login_time'	=> date("Y-m-d H:i:s", time()),
			'last_ip'			=> $_SERVER['REMOTE_ADDR']
		];*/
		//print_r($params);die;
		//$json_params = json_encode($params);
		//替换｛｝为[]
		//$a = str_replace('{', '[', $json_params);
		//$b = str_replace('}', ']', $a);

		$res = Db::execute('insert into hg_admin_users(username, password, mobile, role_id, action_list, is_frozen, add_time, last_login_time, last_ip)values(:username, :password, :mobile, :role_id, :action_list, :is_frozen, :add_time, :last_login_time, :last_ip)', ['username'=>$username, 'password'=>md5($password), 'role_id'=>$role_id, 'mobile'=>$mobile, 'action_list'=>$action_list, 'is_frozen'=>$status, 'add_time'=>$add_time, 'last_login_time'=>$last_login_time, 'last_ip'=>$last_ip]);
		
		if ($res == 1)
		{
			$data = Db::query('select * from hg_admin_users');
			$view = new View();
			$view->assign('data', $data);
			return $view->fetch('index/admin/index');
		}
		
	}
	
	
}