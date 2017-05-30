<?php
namespace app\admin\controller;
use think\Controller;
use think\View;
use think\Session;

class Order
{
	//换领订单列表
    public function index()
    {
		//筛选参数
		$Order = Model("Order");
		$res = $Order->get_order();
		$page = $res->render();
		
		$data = $res->toArray();
		$view = new View();
		$view->assign('order', $data);
		$view->assign('page', $page);
		return $view->fetch('index/order/index');
    }
	
	//添加
	public function patient_detail()
	{
		$patient_id = input("patient_id");
		$patient_id = !empty($patient_id) ? intval($patient_id) : 0;
		
		$Patient = Model("Patient");
		$res = $Patient->patient_detail($patient_id);
		$view = new View();
		$view->assign('detail', $res['data'][0]);
		
		return $view->fetch('index/patient/detail');
	}
	
	public function edit_patient()
	{
		$patient_id = input("patient_id");
		
		//获取详情
		$Patient = Model("Patient");
		$res = $Patient->patient_detail($patient_id);
		

		if ($res['data'][0]['birthday'])
		{
			$year = intval(substr($res['data'][0]['birthday'], 0, 4));
			$month = intval(substr($res['data'][0]['birthday'], 4, 2));
			$day = intval(substr($res['data'][0]['birthday'], 6, 2));
		}
		$res['data'][0]['year'] = $year ? $year : 0;
		$res['data'][0]['month'] = $month ? $month : 0;
		$res['data'][0]['day'] = $day ? $day : 0;
		
		$year_ary = range(1920,2050);
		$month_ary = range(1,12);
		$day_ary = range(1,31);

		$view = new View();
		$view->assign('year', $year_ary);
		$view->assign('month', $month_ary);
		$view->assign('day', $day_ary);
		$view->assign('patient', $res['data'][0]);
		return $view->fetch('index/patient/edit_patient');
	}
	
	
	public function edit()
	{
		$name = input("patient_name");
		$mobile = input("mobile");
		$sex = input("sex");
		$email = input("email");
		$hospital = input("hospital");
		$tooth_position = input("tooth_position");
		$doctor = input("doctor");
		$false_tooth = input("false_tooth");
		$security_code = input("security_code");
		$year = input("year");
		$month = input("month");
		$day = input("day");
		$production_unit = input("production_unit");
		$patient_id = intval(input("id"));
		
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
			'name'			=> $name,
			'mobile'		=> $mobile,
			'sex'			=> $sex,
			'email'			=> $email,
			'hospital'		=> $hospital,
			'doctor'		=> $doctor,
			'tooth_position'=> $tooth_position,
			'false_tooth'	=> $false_tooth,
			'security_code'	=> $security_code,
			'birthday'		=> $year.$month.$day,
			'production_unit'=> $production_unit,
			'update_time'	=> date("Y-m-d H:i:s", time()),
			'patient_id'	=> $patient_id
		);

		$Patient = Model("Patient");
		$res = $Patient->update_patient($params);
		
		if ($res == 1)
		{
			$data = array(
				'admin_id'		=> Session::get('admin_id'),
				'user_id'		=> $patient_id,
				'username'		=> Session::get('username'),
				'content'		=> '编辑患者:'.$name,
				'create_time'	=> date("Y-m-d H:i:s", time()),
				'ip'			=> $_SERVER['REMOTE_ADDR']	
			);
			$User = Model("User");
			$User->insert_user_action($data);
			echo "<script>window.location.href='index';</script>";
		}
		else
		{
			echo "<script>alert('编辑患者失败');history.back();</script>";
		}
	}

	
}