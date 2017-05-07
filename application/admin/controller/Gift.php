<?php
namespace app\admin\controller;
use think\Controller;
use think\View;

class Gift 
{
	public function index()
    {
		$Gift = model('Gift');
		$data = $Gift->getGift();
        $view = new View();
		$view->assign('data', $data);
		return $view->fetch('index/gift/index');
    }
	
	public function add()
	{
		$view = new View();
		return $view->fetch('index/gift/add');
	}
	
	public function doAdd()
	{
		$gift_name = input('gift_name');
		$gift_intro = input('gift_intro');
		$credit = input('credit');

		$param = array(
			'gift_name'		=> $gift_name,
			'gift_photo'	=> '',
			'credits'		=> $credit,
			'num'			=> 30,
			'sales_num'		=> 123,
			'create_time'	=> date("Y-m-d H:i:s", time()),
			'status'		=> 0,
			'update_time'	=> date("Y-m-d H:i:s", time()),
			'validity_time'	=> date("Y-m-d H:i:s", time()),
			'is_delete'		=> 0
		);
		$Gift = model('Gift');
		$ressult = $Gift->addGift($param);
		$url = $_SERVER['HTTP_ORIGIN']."/public/admin.php/admin/gift/index";
		if ($ressult == 0)
		{
			echo "<script>alert('新建礼品失败');history.back();</script>";
		}
		else
		{
			echo "<script>windows.location.href='".$url."';</script>";
		}
	}
}