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
	
	
	//读取excel
	public function  readexcel()
	{
		require_once VENDOR_PATH.'PHPExcel.php';
		//$PHPReader = new \PHPExcel();
		$file_path = ROOT_PATH.'data\1.xlsx';
		//echo $file_path;die;
		/*
		$PHPReader = new \PHPExcel_Reader_Excel2007();
		if(!$PHPReader->canRead($file_path)){
		    $PHPReader = new \PHPExcel_Reader_Excel5();
		    
		    if(!$PHPReader->canRead($file_path)){//print_r('xxx');die;
		        echo 'no Excel';
		        return ;
		  }
	}*/
		
		$extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION) );

		if ($extension =='xlsx') {
		    $PHPReader = new \PHPExcel_Reader_Excel2007();
		    $PHPExcel = $PHPReader->load($file_path);
		} else if ($extension =='xls') {
		    $PHPReader = new \PHPExcel_Reader_Excel5();
		    $PHPExcel = $PHPReader->load($file_path);
		} else if ($extension=='csv') {
		    $PHPReader = new \PHPExcel_Reader_CSV();
		
		    //默认输入字符集
		    $PHPReader->setInputEncoding('GBK');
		
		    //默认的分隔符
		    $PHPReader->setDelimiter(',');
		
		    //载入文件
		    $PHPExcel = $PHPReader->load($file_path);
		}
		
		
		//$PHPExcel = $PHPReader->load($file_path);
		/**读取excel文件中的第一个工作表*/
		$currentSheet = $PHPExcel->getSheet(0);
		/**取得最大的列号*/
		$allColumn = $currentSheet->getHighestColumn();
		/**取得一共有多少行*/
		$allRow = $currentSheet->getHighestRow();
		//echo $allRow;die;
		for($currentRow =2;$currentRow <= $allRow;$currentRow++){
		/**从第A列开始输出*/
			for($currentColumn= 'A';$currentColumn<= $allColumn; $currentColumn++){
    				$val = $currentSheet->getCellByColumnAndRow(ord($currentColumn) - 65,$currentRow)->getValue();/**ord()将字符转为十进制数*/
    				echo $val;echo "<br>";
    			}
    		}
				
		
		
	}
}
	
	
?>