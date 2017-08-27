<?php
namespace app\admin\model;

use think\Model;
use think\Db;

/**
 * 批量操作
 */
class Batch extends Model
{
	
	//防伪码入库
	public function insert_code($code)
	{
		//$a = Db::name('hg_security_code');
		//var_dump($a);die;
		try
		{
			Db::name('hg_security_code')->insertAll($code);
		}
		catch(\Exception $e)
		{
			echo $e->getMessage();
			return false;
		}
		
		return true;
	}	
	public function stock_list($param=array())
	{
		$res = array();
		$obj = Db::name('hg_stock');
		if (!empty($param['keyword']))
		{
			$obj = $obj->where('user_id|user_name|stock_no','like',$param['keyword'].'%');
		}
		if (!empty($param['start_time']) && !empty($param['end_time']))
		{
			$obj = $obj->where('stock_time',['>=',$param['start_time']],['<=',$param['end_time']],'and');
		}
		
		$res = $obj->where('is_delete', 0)->paginate(10);
		return $res;
	}
}