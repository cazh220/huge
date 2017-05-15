<?php
namespace app\admin\controller;
use think\Controller;
use think\View;

class User
{
    public function index()
    {
		//
		$User = Model("User");
		$res = $User->user_list();
		$data = $res->toArray();
		$view = new View();
		$view->assign('user', $data['data']);
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
		
		//print_r($params);die;

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
	
	public function test()
	{
		var_dump(strtotime("2017-05-31 00:00:00"));
	}
	
	
	
}