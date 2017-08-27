<?php
namespace app\admin\controller;
use think\Controller;
use think\View;
use think\Session;

class Order
{
	public static $ship_way = array(
		'0'	=> '快递',
		'1'	=> '自取'
	);
	public static $ship_company = array(
		'1'	=> '顺丰',
		'2'	=> '申通',
		'3'	=> '韵达',
		'4'	=> '中通',
		'5'	=> '圆通',
		'6'	=> '邮政',
		'7'	=> '其它'
	);
	
	//换领订单列表
    public function index()
    {
		//筛选参数
		$Order = Model("Order");
		$res = $Order->get_order();
		$page = $res->render();
		
		$data = $res->toArray();
		//获取区域
		$Region = Model("Region");
		if(!empty($data['data']) && is_array($data['data']))
		{
			$ship_way = !empty($value['ship_way']) ? $value['ship_way'] : 0;
			$ship_company = !empty($value['ship_company']) ? $value['ship_company'] : 1;
			foreach($data['data'] as $key => $value)
			{
				$data['data'][$key]['province_name'] = $Region->get_area($value['province']);
				$data['data'][$key]['city_name'] = $Region->get_area($value['city']);
				$data['data'][$key]['district_name'] = $Region->get_area($value['district']);
				$data['data'][$key]['ship_way_name'] = self::$ship_way[$ship_way];
				$data['data'][$key]['ship_company_name'] = self::$ship_company[$ship_company];
			}
		}

		$view = new View();
		$view->assign('order', $data);
		$view->assign('page', $page);
		return $view->fetch('index/order/index');
    }
	
	//详情
	public function detail()
	{
		$order_id = input("order_id");
		$Order = Model("Order");
		
		if($_POST)
		{
			//更新收货与物流信息
			$consignee	= input("username");
			$ship_way	= input("ship_way");
			$mobile		= input("mobile");
			$ship_company	= input("ship_name");
			$address	= input("address");
			$ship_no	= input("ship_no");
			//$send_time	= input("send_time");
			$order_id	= input("order_id");
			
			$data = array(
				'consignee'	=> $consignee,
				'ship_way'	=> $ship_way,
				'mobile'	=> $mobile,
				'ship_company'=> $ship_company,
				'address'	=> $address,
				'ship_no'	=> $ship_no,
				'update_ship_time'	=> date("Y-m-d H:i:s"),
				'order_id'	=> $order_id
			);

			$result = $Order->update_ship($data);
			/*
			if(!$result)
			{
				echo "<script>alert('更新失败');</script>";
			}
			*/
		}
		
		
		$res = $Order->order_detail($order_id);
		$view = new View();
		
		if(!empty($res))
		{
			foreach($res as $key => $val)
			{
				$ship_way = !empty($val['ship_way']) ? $val['ship_way'] : 0;
				$ship_company = !empty($val['ship_company']) ? $val['ship_company'] : 1;
				$res[$key]['ship_way_name'] = self::$ship_way[$ship_way];
				$res[$key]['ship_company_name'] = self::$ship_company[$ship_company];
			}
		}

		$view->assign('detail', $res);
		
		return $view->fetch('index/order/detail');
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
	
	public function confirm_order()
	{
		$order_id = input("order_id");
		$order_status = input("order_status");
		
		$Order = Model("Order");
		$result = $Order->update_order_status($order_status, $order_id);
		if($result)
		{
			return json_encode(array('status'=>1, 'message'=>'确认成功'));
		}
		else
		{
			return json_encode(array('status'=>0, 'message'=>'确认失败'));
		}
	}
	
	public function order_status()
	{
		$order_id = input("order_id");
		$order_status = input("order_status");
		$Order = Model("Order");
		$result = $Order->update_order_status($order_status, $order_id);
		echo "<script>window.location.href='index';</script>";
	}

	
}