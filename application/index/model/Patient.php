<?php
namespace app\index\model;

use think\Model;
use think\Db;

class Patient extends Model
{
	public function patient_list($param)
	{
		$start = ($param['page']-1)*$param['page_size'];
		$page_size = $param['page_size'];
		$res = Db::query("SELECT * FROM hg_patient a left join hg_false_tooth b on a.false_tooth = b.false_tooth_id ORDER BY patient_id DESC LIMIT $start, $page_size");
		
		return !empty($res) ? $res : array();
	}

	
}

	