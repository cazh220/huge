<?php
namespace app\admin\model;

use think\Model;
use think\Db;
use think\Paginator;

class Security extends Model
{
	public function last_code()
	{
		$obj_data = Db::name('hg_security_code');
		$res = $obj_data->order('code_id desc')->find(1);
		return $res['security_code'];
	}
	
	public function get_order($param=array())
	{
		$obj_data = Db::name('hg_order');
		$res = $obj_data->order('code_id desc')->toArray();
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
		
		return $res;
	}
	
}