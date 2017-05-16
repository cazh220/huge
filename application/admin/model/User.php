<?php
namespace app\admin\model;

use think\Model;
use think\Db;
use think\Paginator;

class User extends Model
{
	public function insert_user($data=array())
	{
		$res = 0;
		if (!empty($data))
		{
			$res = Db::execute("INSERT INTO hg_user SET username = :username, mobile = :mobile, realname = :realname, password = :password, user_type = :user_type, sex = :sex,email = :email, birthday = :birthday, company_name = :company_name, company_addr = :company_addr, company_phone = :company_phone, department = :department, position = :position, persons_num = :persons_num, zipcode = :zipcode, create_time = :create_time", $data);
		}
		return $res;
	}
	
	public function user_list($param=array())
	{
		$obj_data = Db::name('hg_user');
		
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
		}
		
		return $obj_data->paginate(10);
	}
	
	public function user_detail($user_id=0)
	{
		$res = Db::query("SELECT * FROM hg_user WHERE user_id = :user_id", ['user_id'=>$user_id]);
		
		return !empty($res) ? $res : array();
	}
	
	public function update_user($param = array())
	{
		$res = 0;
		if (!empty($param))
		{
			$res = Db::execute("UPDATE hg_user SET username = :username, mobile = :mobile, realname = :realname, user_type = :user_type, sex = :sex,email = :email, birthday = :birthday, company_name = :company_name, company_addr = :company_addr, company_phone = :company_phone, department = :department, position = :position, persons_num = :persons_num, zipcode = :zipcode, create_time = :create_time WHERE user_id = :user_id", $param);
		}
		return $res;
	}
	
	public function delete_user($user_id=0)
	{
		$res = 0;
		if (!empty($user_id))
		{
			$res = Db::execute("DELETE FROM hg_user WHERE user_id = :user_id", ['user_id'=>$user_id]);
		}
		return $res;
	}
}