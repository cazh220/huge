<?php
namespace app\index\model;

use think\Model;
use think\Db;

class User extends Model
{
	public function register($param)
	{
		$sql = "INSERT INTO hg_user SET ";
		if(is_array($param) && !empty($param))
		{
			foreach($param as $key => $val)
			{
				$sql .= $key ." = :".$key.",";
			}
		}
		$sql = rtrim($sql, ',');
		$res = Db::execute($sql, $param);
		return $res;
	}
	
	//校验短信验证码
	public function check_sms($mobile, $code)
	{
		$res = Db::query("SELECT count(*) as count FROM hg_sms_code WHERE mobile = :mobile AND code = :code", ['mobile'=>$mobile, 'code'=>$code]);
		
		return !empty($res) ? $res : array();
	}
	
	//验证登录
	public function check_user($mobile, $password)
	{
		$res = Db::query("SELECT * FROM hg_user WHERE mobile = :mobile AND password = :password", ['mobile'=>$mobile, 'password'=>$password]);
		
		return !empty($res) ? $res : array();
	}
	
}

	