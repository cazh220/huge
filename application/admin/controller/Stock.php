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
		$view = new View();
		return $view->fetch('index/stock/create');
	}
	

	
}