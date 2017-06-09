<?php
namespace app\admin\model;

use think\Model;
use think\Db;

class Stock extends Model
{
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