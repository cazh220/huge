<?php
namespace app\admin\model;

use think\Model;
use think\Db;
use think\Paginator;

class Patient extends Model
{
	//获取患者
	public function get_patient()
	{
		$obj_data = Db::name('hg_patient')->alias('a')->join('hg_false_tooth b', 'a.false_tooth = b.false_tooth_id');
		return $obj_data->paginate();
	}
	
	//患者详情
	public function patient_detail($patient_id=0)
	{
		$obj_data = Db::name('hg_patient')->alias('a')->join('hg_false_tooth b', 'a.false_tooth = b.false_tooth_id');
		if (!empty($patient_id))
		{
			$obj_data->where('patient_id', $patient_id);
		}
		return $obj_data->paginate()->toArray();
	}
	
	public function update_patient($param = array())
	{
		$res = 0;
		if (!empty($param))
		{
			$res = Db::execute("UPDATE hg_patient SET name = :name, mobile = :mobile, sex = :sex,email = :email, birthday = :birthday, hospital = :hospital, doctor = :doctor, tooth_position = :tooth_position, false_tooth = :false_tooth, security_code = :security_code, production_unit = :production_unit, update_time = :update_time WHERE patient_id = :patient_id", $param);
		}
		return $res;
	}
	
	
	
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