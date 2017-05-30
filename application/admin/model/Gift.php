<?php
namespace app\admin\model;

use think\Model;
use think\Db;

class Gift extends Model
{
	public function addGift($data=array())
	{
		$res = Db::execute("INSERT INTO hg_gift SET gift_name = :gift_name, gift_photo = :gift_photo, credits = :credits, num = :num, sales_num = :sales_num,create_time = :create_time, status = :status, update_time = :update_time, validity_time = :validity_time, is_delete = :is_delete", $data);
		
		return $res;
	}
	
	public function getGift($data=array())
	{
		$where_condition = "";
		$where_ary = array();
		//Æ´½Ó²ÎÊı
		foreach($data as $key => $val)
		{
			array_push($where_ary, $key."= :".$key);
		}
		if (!empty($where_ary))
		{
			$where_condition = implode(' AND ', $where_ary);
			if (!empty($where_condition))
			{
				$where_condition = " WHERE {$where_condition} ";
			}
		}

		$order = " ORDER BY gift_id DESC";
		if ($order=='1')
		{
			$order = " ORDER BY admin_id ASC";
		}
		$where_condition .= $order;
		if (!empty($page))
		{
			$start = $page['current_page'] - 1;
			$page_size = $page['page_size'];
			$start = ($start > 0) ? intval($start) : 0;
			$limit = " LIMIT $start, $page_size";
			$where_condition .= $limit;
		}
		
		$res = Db::query("select *  from hg_gift $where_condition", $data);
		return $res;
	}
	
	public function delete_gift($gift_id=0)
	{
		$res = 0;
		if (!empty($gift_id))
		{
			$res = Db::execute("DELETE FROM hg_gift WHERE gift_id = :gift_id", ['gift_id'=>$gift_id]);
		}
		return $res;
	}
	
	public function off_shelf($gift_id='')
	{
		$res = 0;
		if (!empty($gift_id))
		{
			$res = Db::table('hg_gift')->where('gift_id','in',$gift_id)->update(['status' => 1]);
		}
		return $res;
	}
}