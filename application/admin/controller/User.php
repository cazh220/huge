<?php
namespace app\admin\controller;
use think\Controller;
use think\View;

class User
{
    public function index()
    {
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
	
	public function add_user()
	{
		$username = input("username");
		$password = input("password");
		$mobile = input("mobile");
		$realname = input("realname");
		$user_type = input("user_type");
		$sex = input("sex");
		$email = input("email");
		$company_name = input("company_name");
		$company_addr = input("company_addr");
		$company_phone = input("company_phone");
		$department = input("department");
		$position = input("position");
		$year = input("year");
		$month = input("month");
		$day = input("day");
		$person_num = input("person_num");
		$zipcode = input("zipcode");
		
		//生日验证
		if ($month < 10)
		{
			$month = "0".$month;
		}
		if ($day < 10)
		{
			$day = "0".$day;
		}
		
		$time = $year."-".$month."-".$day." 00:00:00";
		if (strtotime($time) == false)
		{
			echo "<script>alert('生日日期选择错误');history.back();</script>";
		}
		
		$params = array(
			'username'		=> $username,
			'password'		=> md5($password),
			'mobile'		=> $mobile,
			'realname'		=> $realname,
			'user_type'		=> $user_type,
			'sex'			=> $sex,
			'email'			=> $email,
			'company_name'	=> $company_name,
			'company_phone'	=> $company_phone,
			'company_addr'	=> $company_addr,
			'department'	=> $department,
			'position'		=> $position,
			'birthday'		=> $year.$month.$day,
			'persons_num'	=> $person_num,
			'zipcode'		=> $zipcode,
			'create_time'	=> date("Y-m-d H:i:s", time())
		);
		

		$User = Model("User");
		$res = $User->insert_user($params);
		
		if ($res == 1)
		{
			echo "<script>window.location.href='index';</script>";
		}
		else
		{
			echo "<script>alert('新增会员失败');history.back();</script>";
		}
		
		
	}
	
	public function user_detail()
	{
		$user_id = input("user_id");
		$user_id = !empty($user_id) ? intval($user_id) : 0;
		
		$User = Model("User");
		$res = $User->user_detail($user_id);
		
		$view = new View();
		$view->assign('detail', $res[0]);
		return $view->fetch('index/user/detail');
	}
	
	public function edit_user()
	{
		$user_id = input("user_id");
		//var_dump($user_id);
		
		//获取详情
		$User = Model("User");
		$res = $User->user_detail($user_id);
		
		if ($res[0]['birthday'])
		{
			$year = intval(substr($res[0]['birthday'], 0, 4));
			$month = intval(substr($res[0]['birthday'], 4, 2));
			$day = intval(substr($res[0]['birthday'], 6, 2));
		}
		$res[0]['year'] = $year ? $year : 0;
		$res[0]['month'] = $month ? $month : 0;
		$res[0]['day'] = $day ? $day : 0;
		
		$year_ary = range(1920,2050);
		$month_ary = range(1,12);
		$day_ary = range(1,31);

		$view = new View();
		$view->assign('year', $year_ary);
		$view->assign('month', $month_ary);
		$view->assign('day', $day_ary);
		$view->assign('user', $res[0]);
		return $view->fetch('index/user/edit_user');
	}
	
	public function edit()
	{
		$username = input("username");
		$mobile = input("mobile");
		$realname = input("realname");
		$user_type = input("user_type");
		$sex = input("sex");
		$email = input("email");
		$company_name = input("company_name");
		$company_addr = input("company_addr");
		$company_phone = input("company_phone");
		$department = input("department");
		$position = input("position");
		$year = input("year");
		$month = input("month");
		$day = input("day");
		$person_num = input("person_num");
		$zipcode = input("zipcode");
		$user_id = intval(input("id"));
		
		//生日验证
		if ($month < 10)
		{
			$month = "0".$month;
		}
		if ($day < 10)
		{
			$day = "0".$day;
		}
		
		$time = $year."-".$month."-".$day." 00:00:00";
		if (strtotime($time) == false)
		{
			echo "<script>alert('生日日期选择错误');history.back();</script>";
		}
		
		$params = array(
			'username'		=> $username,
			'mobile'		=> $mobile,
			'realname'		=> $realname,
			'user_type'		=> $user_type,
			'sex'			=> $sex,
			'email'			=> $email,
			'company_name'	=> $company_name,
			'company_phone'	=> $company_phone,
			'company_addr'	=> $company_addr,
			'department'	=> $department,
			'position'		=> $position,
			'birthday'		=> $year.$month.$day,
			'persons_num'	=> $person_num,
			'zipcode'		=> $zipcode,
			'create_time'	=> date("Y-m-d H:i:s", time()),
			'user_id'		=> $user_id
		);

		$User = Model("User");
		$res = $User->update_user($params);
		
		if ($res == 1)
		{
			echo "<script>window.location.href='index';</script>";
		}
		else
		{
			echo "<script>alert('编辑会员失败');history.back();</script>";
		}
	}
	
	public function delete_user()
	{
		$user_id = input("user_id");
		if (!empty($user_id))
		{
			$User = Model("User");
			$user_arr = explode(",", $user_id);
			foreach($user_arr as $key => $val)
			{
				$res = $User->delete_user($val);
			}
		}
		
		if ($res == 1)
		{
			echo "<script>window.location.href='index';</script>";
		}
		else
		{
			echo "<script>alert('删除会员失败');history.back();</script>";
		}
	}
	
	
	public function test()
	{
		var_dump(strtotime("2017-05-31 00:00:00"));
	}
	
	
	
}