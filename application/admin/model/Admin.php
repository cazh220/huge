<?php
namespace app\admin\model;

use think\Model;
use think\Db;

class Admin extends Model
{
	public function getAdmin($condition=array(), $order=0, $page=array())
	{
		$where_ary = array();
		//拼接参数
		foreach($condition as $key => $val)
		{
			array_push($where_ary, $key."= :".$key);
		}
		$where_condition = implode(' AND ', $where_ary);
		$order = " ORDER BY admin_id DESC";
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
	
		$res = Db::query("select *  from hg_admin_users where $where_condition", $condition);
		return $res;
	}
	
	public function updateAdmin($param=array(), $where=array())
	{
		if (!empty($param) && !empty($where))
		{
			$set = $this->join2param($param, ',');
			$where_condition = $this->join2param($where, 'AND');
			$params = array_merge($param, $where);
			//echo "update hg_admin_users set $set where $where_condition";print_r($params);die;
			$res = Db::execute("update hg_admin_users set $set where $where_condition", $params);
		}
		
		return $res==1 ? 1 : 0;
	}

}