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
		$res = $obj_data->order('code_id desc')->limit(1)->value('security_code');
		return $res;
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
	
	public function insert_code($param=array())
	{
		//事务操作
		if(!empty($param) && is_array($param))
		{
			Db::startTrans();
			try{
				foreach($param as $key => $value)
				{
					Db::table('hg_security_code')->insert($value);
				}
				// 提交事务
				Db::commit();    
			} catch (\Exception $e) {
				// 回滚事务
				Db::rollback();
				return false;
			}
		}
		
		return true;
	}
	
	public function code_list($param=array())
	{
		$obj_data = $obj_data = Db::name('hg_security_code');
		if (!empty($param['_code']))
		{
			$obj_data = $obj_data->where('security_code', $param['_code']);
		}
		
		if (!empty($param['start_time']) && !empty($param['end_time']))
		{
			$obj_data = $obj_data->where('create_time', 'between', [$param['start_time'], $param['end_time']]);
		}
		$obj_data = $obj_data->order('code_id desc')->paginate();
		
		return $obj_data;
	}
	
}