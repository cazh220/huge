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
		
		if (!empty($param['start_time']) && !empty($param['end_time']))
		{
			$obj_data = $obj_data->where('create_time',['>=',$param['start_time']],['<=',$param['end_time']],'and');
		}
		
		$obj_data = $obj_data->order('user_id desc');
		
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
	
	//审核
	public function audit_user($user_id=0, $status=0)
	{
		if (!empty($user_id))
		{
			$res = Db::execute("UPDATE hg_user SET status = :status  WHERE user_id = :user_id", ['status'=>$status,'user_id'=>$user_id]);
		}
		return $res;
	}
	
	//插入一条操作记录
	public function insert_user_action($param=array())
	{
		$res = 0;
		if (!empty($param))
		{
			$res = Db::execute("INSERT INTO hg_user_actions SET admin_id = :admin_id, user_id = :user_id, username = :username, content = :content, create_time = :create_time, ip = :ip", $param);
		}
		return $res;
	}
	
	public function getHistory($user_id = 0)
	{
		$obj_data = Db::name('hg_user_actions');
		if (!empty($user_id))
		{
			$obj_data = $obj_data->where('user_id', $user_id);
		}
		$res = $obj_data->order('id desc')->paginate();
		
		return $res;
	}
}