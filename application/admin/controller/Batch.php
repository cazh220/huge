<?php
namespace app\admin\controller;
use think\Controller;
use think\View;
use think\Session;

set_time_limit(0);
ini_set('memory_limit', '128M');
/**
 * 批量操作
 */
class Batch
{
	//批量导入防伪码
	public function batch_import_security_code()
	{
		$file_type = $_GET['file_type'];
		$file_type = 'txt';
		$file_path = ROOT_PATH.'data\code.txt';
		//读取防伪码
		//echo $file_path;die;
		if($file_type == 'txt')
		{
			$contents = file_get_contents($file_path);
		}
		
		$code = explode("\n", $contents);
		
		//入库
		foreach($code as $key => $val)
		{
			if($val)
			{
				$data[$key] = array(
					'security_code'	=> $val,
					'create_time'	=> date("Y-m-d H:i:s", time()),
					'update_time'	=> date("Y-m-d H:i:s", time()),
				);
			}
			
		}
		
		$Batch = Model("Batch");
		$res = $Batch->insert_code($data);
		if($res)
		{
			echo "导入成功";
		}
		else
		{
			echo "导入失败";
		}
		//print_r($data);die;
	}
	
}
	
	
?>