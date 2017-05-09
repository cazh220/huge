<?php
namespace app\admin\model;

use think\Model;
use think\Db;

class Menu extends Model
{
	public function getMenu($data)
	{
		$str = "";
		if (!empty($data))
		{
			foreach ($data['action_code'] as $key => $val)
			{
				$str .= "'".$val."',";
			}
			$str = rtrim($str, ',');
		}
		echo $str;die;
		$sql = "SELECT * FROM hg_admin_action WHERE status = ".$data['status']." AND action_code IN ('admin','admin_list','user','user_list')";
		//echo $sql;die;
		$res = Db::query($sql);
		return $res;
	}
}