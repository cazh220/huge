<?php
namespace app\index\controller;

use think\Controller;
use think\Model;
use think\Db;
use think\View;
use think\Session;

class Index
{
	public function index()
	{
		//var_dump(Session::get('user.mobile'));die;
		$view = new View();
		$view->assign('user', Session::get('user.mobile'));
		return $view->fetch('index');
	}
	
	public function login()
	{
		$view = new View();
		return $view->fetch('login');
	}
	
	public function register()
	{
		//获取区域省
		$Region = model('Region');
		$province = $Region->get_province();

		$view = new View();
		$view->assign('province', $province);
		return $view->fetch('register');
	}
	
	//注册
	public function do_register()
	{
		$mobile = trim($_POST['mobile']);
		$code = trim($_POST['code']);
		$realname = trim($_POST['username']);
		$password = trim($_POST['password']);
		$user_type = intval($_POST['user_type']);
		$company_name = trim($_POST['company_name']);
		$company_addr = trim($_POST['company_addr']);
		$position = trim($_POST['position']);
		$province = intval($_POST['province']);
		$city = intval($_POST['city']);
		$district = intval($_POST['district']);
		
		//上传图片

		$param = array(
			'mobile'	=> $mobile,
			'username'	=> $mobile,
			'realname'	=> $realname,
			'password'	=> $password,
			'user_type'	=> $user_type,
			'company_name'	=> $company_name,
			'company_addr'	=> $company_addr,
			'position'		=> $position,
			'province'		=> $province,
			'city'			=> $city,
			'district'		=> $district,
			'last_login'	=> date("Y-m-d H:i:s", time()),
			'last_ip'		=> $_SERVER["REMOTE_ADDR"]
		);
		//echo ROOT_PATH;die;
		$upload = $this->upload();
		if($upload['status']==0)
		{
			//错误
			echo "<script>alert('注册失败');history.go(-1);</script>";
		}
		else
		{
			$param['head_img'] = $upload['image'];
		}
		
		$User = model('User');
		
		$res = $User->register($param);
		if(empty($res))
		{
			echo "<script>alert('注册失败');history.go(-1);</script>";
			exit();
		}
		
		header("Location:http://huge.com/public/index.php/index/index");
		exit();
		
	}
	
	
	private function upload()
	{
		$files = request()->file('head');
		// 移动到框架应用根目录/public/uploads/ 目录下
		$info = $files->move(ROOT_PATH . 'public' . DS . 'uploads');
		if($info){
			// 成功上传后 获取上传信息
			//return array('name'=>$info->getFilename(), 'status'=>1);
			return array('status'=>1, 'image'=>$info->getSavename());
		}else{
			// 上传失败获取错误信息
			//return array('error'=>$files->getError(), 'status'=>0);
			return array('status'=>0, 'image'=>'');
		} 
		
	}
	
	//登录
	public function do_login()
	{
		$mobile = $_GET['mobile'];
		$password = $_GET['password'];
		
		$User = model('User');
		$res = $User->check_user($mobile, $password);
		//var_dump($res);die;
		if(empty($res))
		{
			exit(json_encode(array('status'=>1, 'message'=>'账号或密码不正确')));
		}
		
		//var_dump($res[0]);die;
		//写入session
		Session::set('user.user_id',$res[0]['user_id']);
		Session::set('user.mobile',$res[0]['mobile']);
		header("Location:http://huge.com/public/index.php/index/index");
		exit();
	}
	
	public function logout()
	{
		//Session::flush();
		Session::delete('user.user_id');
		Session::delete('user.mobile');
		header("Location:http://huge.com/public/index.php/index/index");
		exit();
	}
	
	//重置密码
	public function resetpwd()
	{

		$view = new View();
		$view->assign('mobile', Session::get('user.mobile'));
		return $view->fetch('resetpwd');
	}
	
	//密码重置
	public function update_pwd()
	{
		$mobile = trim($_POST['_mobile']);
		$password = trim($_POST['password']);
		
		$User = model('User');
		$res = $User->update_pwd($mobile, $password);
		
		if(empty($res))
		{
			echo "<script>alert('修改失败');history.go(-1);</script>";
			exit();
		}
		else
		{
			echo "<script>alert('修改成功');window.location.href='http://huge.com/public/index.php/index/index/login';</script>";
			//header("Location:http://huge.com/public/index.php/index/index/login");
			exit();
		}
	}

	
	
	/*
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
    }
	
	public function list_users()
	{
		$data = Db::query('select * from hg_user');
		print_r($data);
		$view = new View();
		$view->assign('data', $data);
		//$view->name = 'caozheng';
		return $view->fetch('list');
	}
	
	public function view_user()
	{
		$data = Db::query('select * from hg_users where id = :id', ['id'=>1]);
		
		print_r($data);
	}
	
	public function insert_user()
	{
		$res = Db::execute('insert into hg_users(user, name, create_time, credits)values(:user, :name, :create_time, :credits)', ['user'=>'baoyu', 'name'=>'薄玉', 'create_time'=>date('Y-m-d H:i:s', time()), 'credits'=>50]);
		var_dump($res);
	}
	
	public function update_user()
	{
		$res = Db::execute('update hg_users set create_time = :create_time where user = :user', ['create_time'=>date('Y-m-d H:i:s', time()), 'user'=>'taiang']);
		var_dump($res);
	}
	
	public function delete_user()
	{
		$res = Db::execute('delete from hg_users where id = :id', ['id'=>7]);
		var_dump($res);
	}
	*/
}
