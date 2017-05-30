<?php
namespace app\admin\controller;
use think\Controller;
use think\View;

class Gift 
{
	public function index()
    {
		$Gift = model('Gift');
		$where['status'] = 0;
		$data = $Gift->getGift($where);
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
	
	public function delete_gift()
	{
		$gift_id = input("gift_id");
		if (!empty($gift_id))
		{
			$Gift = Model("Gift");
			$gift_arr = explode(",", $gift_id);
			foreach($gift_arr as $key => $val)
			{
				$res = $Gift->delete_gift($val);
			}
		}
		
		if ($res == 1)
		{
			echo "<script>window.location.href='index';</script>";
		}
		else
		{
			echo "<script>alert('删除礼品失败');history.back();</script>";
		}
	}
	
	public function offline()
	{
		$gift_id = input("gift_id");
		if (!empty($gift_id))
		{
			$Gift = Model("Gift");
			$res = $Gift->off_shelf($gift_id);
		}
		
		if ($res)
		{
			echo "<script>window.location.href='index';</script>";
		}
		else
		{
			echo "<script>alert('下架礼品失败');history.back();</script>";
		}
	}
}