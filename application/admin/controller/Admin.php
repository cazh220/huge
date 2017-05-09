<?php
namespace app\admin\controller;
use think\Controller;
use think\View;
use think\Db;

class Admin extends Controller
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
		$username 	= !empty($_POST['username']) ? trim($_POST['username']) : '';
		$password 	= !empty($_POST['password']) ? trim($_POST['password']) : '';
		$role_id 	= !empty($_POST['role_id']) ? intval($_POST['role_id']) : 0;
		$mobile 	= !empty($_POST['mobile']) ? trim($_POST['mobile']) : '';
		$is_frozen	= !empty($_POST['is_frozen']) ? intval($_POST['is_frozen']) : 0;
		$permission	= !empty($_POST['permission']) ? $_POST['permission'] : '';
		
		$action_list 		= !empty($permission) ? json_encode($permission) : '';
		$add_time 			= date("Y-m-d H:i:s", time());
		$last_login_time 	= date("Y-m-d H:i:s", time());
		$last_ip 			= $_SERVER['REMOTE_ADDR'];

		$res = Db::execute('insert into hg_admin_users(username, password, mobile, role_id, action_list, is_frozen, add_time, last_login_time, last_ip)values(:username, :password, :mobile, :role_id, :action_list, :is_frozen, :add_time, :last_login_time, :last_ip)', ['username'=>$username, 'password'=>md5($password), 'role_id'=>$role_id, 'mobile'=>$mobile, 'action_list'=>$action_list, 'is_frozen'=>$is_frozen, 'add_time'=>$add_time, 'last_login_time'=>$last_login_time, 'last_ip'=>$last_ip]);
		
		if ($res == 1)
		{
			$data = Db::query('select * from hg_admin_users');
			$view = new View();
			$view->assign('data', $data);
			return $view->fetch('index/admin/index');
		}
		else
		{
			echo "<script>alert('新增管理员失败');history.back();</script>";
		}
		
	}
	
	public function edit()
	{
		$admin_id = !empty($_GET['admin_id']) ? intval($_GET['admin_id']) : 0;
		//获取管理员信息
		$data = Db::query('select * from hg_admin_users where admin_id = :id', ['id'=>$admin_id]);
		if (!empty($data[0]['action_list']))
		{
			$data[0]['action_list'] = json_decode($data[0]['action_list'], true);
		}

		$view = new View();
		$view->assign('data', $data[0]);
		return $view->fetch('index/admin/edit');
	}
	
	public function edit_admin_user()
	{
		$admin_id	= !empty($_POST['admin_id']) ? trim($_POST['admin_id']) : 0;
		$username 	= !empty($_POST['username']) ? trim($_POST['username']) : '';
		$password 	= !empty($_POST['password']) ? trim($_POST['password']) : '';
		$role_id 	= !empty($_POST['role_id']) ? intval($_POST['role_id']) : 0;
		$mobile 	= !empty($_POST['mobile']) ? trim($_POST['mobile']) : '';
		$is_frozen 	= !empty($_POST['is_frozen']) ? intval($_POST['is_frozen']) : 0;
		$permission	= !empty($_POST['permission']) ? $_POST['permission'] : '';
		
		$action_list 		= !empty($permission) ? json_encode($permission) : '';
		$last_login_time 	= date("Y-m-d H:i:s", time());
		$last_ip 			= $_SERVER['REMOTE_ADDR'];
		
		$res = Db::execute('update hg_admin_users set username = :username, password = :password, mobile = :mobile, role_id = :role_id, action_list = :action_list, is_frozen = :is_frozen, last_login_time = :last_login_time, last_ip = :last_ip where admin_id = :admin_id', ['username'=>$username, 'password'=>$password, 'mobile'=>$mobile, 'role_id'=>$role_id, 'action_list'=>$action_list, 'is_frozen'=>$is_frozen, 'last_login_time'=>$last_login_time, 'last_ip'=>$last_ip, 'admin_id'=>$admin_id]);
		if ($res == 1)
		{
			$data = Db::query('select * from hg_admin_users');
			$view = new View();
			$view->assign('data', $data);
			return $view->fetch('index/admin/index');
		}
		else
		{
			echo "<script>alert('编辑管理员失败');history.back();</script>";
		}
	}
	
	public function edit_password()
	{
		$admin_name = !empty($_GET['admin_name']) ? trim($_GET['admin_name']) : '';
		$admin_id   = !empty($_GET['admin_id']) ? trim($_GET['admin_id']) : '';
		$view = new View();
		$view->assign('data', array('admin_id'=>$admin_id, 'admin_name'=>$admin_name));
		return $view->fetch('index/admin/edit_password');
	}
	
	public function edit_password_action()
	{
		$admin_id = !empty($_POST['admin_id']) ? intval($_POST['admin_id']) : 0;
		$password = !empty($_POST['password']) ? trim($_POST['password']) : '';
		$repassword = !empty($_POST['repassword']) ? trim($_POST['repassword']) : '';
		
		$res = Db::execute('update hg_admin_users set password = :password where admin_id = :admin_id', ['password'=>md5($password), 'admin_id'=>$admin_id]);
		
		if ($res == 1)
		{
			$data = Db::query('select * from hg_admin_users');
			$view = new View();
			$view->assign('data', $data);
			return $view->fetch('index/admin/index');
		}
		else
		{
			echo "<script>alert('重置密码失败');history.back();</script>";
		}
	}
	
	public function delete()
	{
		$admin_id = !empty($_GET['admin_id']) ? intval($_GET['admin_id']) : 0;
		$res = Db::execute('delete from hg_admin_users where admin_id = :admin_id', ['admin_id'=>$admin_id]);
		
		if ($res == 1)
		{
			$data = Db::query('select * from hg_admin_users');
			$view = new View();
			$view->assign('data', $data);
			return $view->fetch('index/admin/index');
		}
		else
		{
			echo "<script>alert('删除失败');history.back();</script>";
		}
	}
	
	
}