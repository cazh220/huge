<?php
namespace app\admin\model;

use think\Model;
use think\Db;
use think\Paginator;

class Order extends Model
{	
	public function get_order($param=array())
	{
		$obj_data = Db::name('hg_order');
		
		/*
		if (!empty($param['keyword']))
		{
			$obj_data = $obj_data->where('username', 'like', '%'.$param['keyword'])->whereOr('mobile', 'like', '%'.$param['keyword'])->whereOr('company_name', 'like', '%'.$param['keyword']);
		}
		
		if (!empty($param['dental']))
		{
			$obj_data = $obj_data->where('company_name', 'like', '%'.$param['dental']);
		}
		
		if (!empty($param['hospital']))
		{
			$obj_data = $obj_data->where('hospital', 'like', '%'.$param['hospital']);
		}*/
		
		return $obj_data->paginate(10);
	}
	
	public function order_detail($order_id)
	{
		$res = Db::query("SELECT * FROM hg_order a left join hg_order_gift b ON a.order_id = b.order_id left join hg_user c ON a.user_id = c.user_id WHERE a.order_id = :order_id", ['order_id'=>$order_id]);
		
		return !empty($res) ? $res : array();
	}
	
}