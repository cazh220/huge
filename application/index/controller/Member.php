<?php
namespace app\index\controller;

use think\Controller;
use think\Model;
use think\Db;
use think\View;
use think\Session;

class Member
{	
	public function index()
	{
		//获取我的信息
		$user_id = Session::get('user.user_id');
		$Member = model('Member');
		$user = $Member->get_my_detail($user_id);
		//print_r($user);die;
		if($user[0]['user_type'] == 1)
		{
			$user[0]['user_type_name'] = '技工';
		}
		else
		{
			$user[0]['user_type_name'] = '医生';
		}
		//print_r($user);die;
		//获取区域名称
		$Region = model('Region');
		$province_name = $Region->get_area($user[0]['province']);
		$city_name = $Region->get_area($user[0]['city']);
		$district_name = $Region->get_area($user[0]['district']);
		
		$user[0]['province_name'] = $province_name ? $province_name : '';
		$user[0]['city_name'] = $city_name ? $city_name : '';
		$user[0]['district_name'] = $district_name ? $district_name : '';
		
		$view = new View();
		$view->assign('user', $user[0]);
		return $view->fetch('index');
	}

	public function edit_member()
	{
		$user_id = Session::get('user.user_id');
		$Member = model('Member');
		$user = $Member->get_my_detail($user_id);
		
		//年
		$year = range(1950, 2040);
		$month = range(1, 12);
		$day = range(1, 31);
		
		//获取年月日
		//print_r($user[0]['birthday']);die;
		$temp = $user[0]['birthday'] ? explode('-', $user[0]['birthday']) : array();
		$year_s = !empty($temp[0]) ? intval($temp[0]) : '';
		$month_s = !empty($temp[1]) ? intval($temp[1]) : '';
		$day_s = !empty($temp[2]) ? intval($temp[2]) : '';
		
		$view = new View();
		$view->assign('user', $user[0]);
		$view->assign('year', $year);
		$view->assign('month', $month);
		$view->assign('day', $day);
		$view->assign('year_s', $year_s);
		$view->assign('month_s', $month_s);
		$view->assign('day_s', $day_s);
		return $view->fetch('edit_member');
	}
	
	public function search_security_code()
	{
		$security_code = $_POST['security_code'];
		
		//查询
		$param['security_code'] = $security_code;
		$Security = model('Security');
		$result = $Security->serach_security_code($param);
		$data = !empty($result) ? $result[0] : array();
		$patient = !empty($data['name']) ? $data['name'] : '';
		
		$view = new View();
		$view->assign('data', $data);
		$view->assign('patient', $patient);
		return $view->fetch('security_code');
	}
	
	//查询质保卡信息
	public function query_security_code()
	{
		$security_code = $_POST['security_code'];
		
		//查询
		$param['security_code'] = $security_code;
		$Security = model('Security');
		$result = $Security->serach_security_code($param);
		$data = !empty($result) ? $result[0] : array();
		$patient = !empty($data['name']) ? $data['name'] : '';
		
		if(!empty($data))
		{
			$return_data = array('status'=>1, 'data'=>$data, 'patient'=>$patient);
		}
		else
		{
			$return_data = array('status'=>0, 'data'=>array(), 'patient'=>'');
		}
		exit(json_encode($return_data));
	}
	

}
