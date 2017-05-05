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
		$where_str = '';
		$set = join_params($param);
		if ()
		$where = join_params($where, 'AND');
		
		$sql = "update hg_admin_users set $set ";
		
		$res = Db::execute('update hg_admin_users set username = :username, password = :password, mobile = :mobile, role_id = :role_id, action_list = :action_list, is_frozen = :is_frozen, last_login_time = :last_login_time, last_ip = :last_ip where admin_id = :admin_id', ['username'=>$username, 'password'=>$password, 'mobile'=>$mobile, 'role_id'=>$role_id, 'action_list'=>$action_list, 'is_frozen'=>$is_frozen, 'last_login_time'=>$last_login_time, 'last_ip'=>$last_ip, 'admin_id'=>$admin_id]);
	}
	
	public function join_params($param=array(), $join=',')
	{
		$output = array();
		if (!empty($param))
		{
			foreach($param as $key => $val)
			{
				array_push($output, $key."= :".$key);
			}
			$where_condition = implode(' ".$join." ', $where_ary);
		}
		return $output;
	}

}