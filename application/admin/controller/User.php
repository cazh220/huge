<?php
namespace app\admin\controller;
use think\Controller;
use think\View;

class User
{
    public function index()
    {
        $view = new View();
		return $view->fetch('index/user/index');
    }
	
	public function add()
	{
		
		$view = new View();
		return $view->fetch('index/user/add');
	}
	
	public function add_user()
	{
		$username = input("username");
		$mobile = input("mobile");
		$realname = input("realname");
		$user_type = input("user_type");
		$sex = input("sex");
		$email = input("email");
		$birthday = input("birthday");
		$company_name = input("company_name");
		$company_addr = input("company_addr");
		$company_phone = input("company_phone");
		$department = input("department");
		$position = input("position");
		
	}
	
	
	
}