<?php
namespace app\index\model;

use think\Model;
use think\Db;

class Shop extends Model
{
	
	//校验短信验证码
	public function goods_list($param)
	{
		$start = ($param['page']-1)*$param['page_size'];
		$size = $param['page_size'];
		$res = Db::query("SELECT * FROM hg_gift ORDER BY gift_id DESC LIMIT $start, $size");
		
		return !empty($res) ? $res : array();
	}
	

	
}

	