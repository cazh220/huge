<?php
namespace app\admin\controller;
use think\Controller;
use think\View;
use think\Session;

class Stock
{
	//出库信息
    public function index()
    {
		//筛选参数
		$keyword = trim(input("keyword"));
		$start_time = input("start_time");
		$end_time = input("end_time");
		
		$param = array(
			'keyword'		=> $keyword,
			'start_time'	=> $start_time ? $start_time." 00:00:00" : '',
			'end_time'		=> $end_time ? $end_time." 23:59:59" : ''
		);

		$Stock = Model("Stock");
		$res = $Stock->stock_list($param);
		//print_r($res);die;
		$data = $res->toArray();
		$page = $res->render();
		
		

		$view = new View();
		$view->assign('stock', $data);
		$view->assign('page', $page);
		return $view->fetch('index/stock/index');
    }
	
	//添加出库
	public function add()
	{
		//获取单位名称
		$Company = Model("Company");
		$company_list = $Company->get_company_list();
		//print_r($company_list);die;
		
		$view = new View();
		//print_r($_SESSION);die;
		$view->assign('record_time', date("Y-m-d H:i:s", time()));
		$view->assign('company', $company_list);
		$view->assign('username', Session::get('username'));
		return $view->fetch('index/stock/create');
	}
	
	//出库操作
	public function doStockOut()
	{
		$company = input("company");
		$other_company = input("other_company");
		$code_list = input("code_list");
		
		$Company = Model("Company");
		if($company == -1 && !empty($other_company))
		{
			//插入新客户单位
			$res = $Company->is_exist_company($other_company);
			if($res)
			{
				echo "<script>alert('此单位已存在');history.go(-1);</script>";
				exit();
			}
			//插入新单位
			$company_id = $Company->insert_company($other_company);
			
			if(empty($company_id))
			{
				echo "<script>alert('此单位写入失败');history.go(-1);</script>";
				exit();
			}
			
			$company = $other_company;
		}
		//获取单位名
		$company_name = $Company->get_company_name($company);
		
		//出库单号
		$Stock = Model("Stock");
		$stock_no = $Stock->create_stock_no();
		$security_code = array();
		//出库
		if(!empty($code_list))
		{
			//分割
			$Security = Model("Security");
			$code_arr = explode(',', $code_list);	
			
			foreach($code_arr as $key => $val)
			{
				$val = trim($val);
				//判断防伪码是否存在，如果存在则处理，如果不存在则跳过
				$res = $Security->stock_out_security_code($val, $stock_no);
				if($res)
				{
					array_push($security_code, $val);
				}
			}
			//print_r($security_code);die;
			$data = array(
					'stock_no'	=> $stock_no,
					'stock_num'	=> count($security_code),
					'code_list' => implode(',', $security_code),
					'company_name'	=> $company_name,
					'user_name'	=> Session::get('username'),
					'stock_time'	=> date("Y-m-dH:i:s", time()),
				);
				
			//插入
			$res = $Stock->insert_stock($data);
			if(empty($res))
			{
				echo "<script>alert('出库失败');history.go(-1);</script>";
				exit();
			}
		}
		
		header("Location:http://huge.com/public/admin.php/admin/stock/index");
		exit();
	}
	
	//确认出库
	public function stockout_confirm()
	{
		$stock_id = $_GET['stock_id'];
		
		//获取防伪码
		$Stock = Model("Stock");
		$stock_info = $Stock->stock_out_detail($stock_id);
		$Security = Model("Security");
		if(isset($stock_info['code_list']))
		{
			$code_arr = explode(',', $stock_info['code_list']);
			//更新防伪码状态
			$res= $Security->update_security_code_status(2,$code_arr);
			var_dump($res);die;
			if(empty($res))
			{
				echo "<script>alert('确认出库失败');history.go(-1);</script>";
			    exit();
			}
		}
		
		//更新出库单
		$res = $Stock->update_stock_out($stock_id);
		
		if(empty($res))
		{
			echo "<script>alert('确认出库失败');history.go(-1);</script>";
		    exit();
		}
	}
	

	
}