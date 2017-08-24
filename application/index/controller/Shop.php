<?php
namespace app\index\controller;

use think\Controller;
use think\Model;
use think\Db;
use think\View;
use think\Session;

class Shop
{	
	public function index()
	{
		$param = array('page'=>1, 'page_size'=>10);
		$Shop = model('Shop');
		$goods_list = $Shop->goods_list($param);
		print_r($goods_list);die;
		$view = new View();
		$view->assign('goods', $goods_list);
		return $view->fetch('index');
	}

	

}
