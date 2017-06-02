<?php
namespace app\admin\controller;
use think\Controller;
use think\View;

class Security 
{
	public function index()
    {	
		//获取参数
		$_code = input("code");
		$start_time = input("start_time");
		$end_time = input("end_time");
		
		$Security = model('Security');
		$where = array(
			'_code'		=> $_code,
			'start_time'=> $start_time,
			'end_time'	=> $end_time
		);

		$data = $Security->code_list($where);
		$page = $data->render();
		
        $view = new View();
		$view->assign('data', $data->toArray());
		$view->assign('page', $page);
		return $view->fetch('index/security/index');
    }
	
	public function create()
	{
		$view = new View();
		return $view->fetch('index/security/create');
	}
	
	public function doCreate()
	{
		$this->num = input('num');
		$this->size = input('size');
		$this->prefix = input('prefix');
		$this->export = input('export');

		$res = $this->security_code();
		if (!$res)
		{
			echo "<script>alert('生成防伪码失败');history.back();</script>";
		}
		else
		{
			if ($this->export == 1)
			{
				//导出
				if (!empty($this->list))
				{
					$this->export_code($this->list);
				}
				
			}
			echo "<script>window.location.href='index';</script>";
		}
	}
	
	public function save_qrcode()
	{
		$data = isset($_GET['data']) ? $_GET['data'] : '1234'; 
		$url = "http://www.kuitao8.com/qr/view?qr=".$data; 
		function GetCurl($url){ 
			$curl = curl_init(); 
			curl_setopt($curl,CURLOPT_RETURNTRANSFER,1); 
			curl_setopt($curl,CURLOPT_URL, $url); 
			curl_setopt($curl,CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
			$resp = curl_exec($curl); 
			curl_close($curl); 
			return $resp; 
		} 
		$content = GetCurl($url); 
		preg_match('/<img.+src=\"?(.+\.(jpg|gif|bmp|bnp|png|jpeg))\"?.+>/i',$content,$arr); 
		
		$imgFile = "http://www.kuitao8.com/".$arr[1];
		$size = isset($_GET['size']) ? $_GET['size'] : '200x200'; 
		$logo = isset($_GET['logo']) ? $_GET['logo'] : ''; 
		header('Content-type: image/png'); 
		$QR = imagecreatefrompng($imgFile);
		
		if($logo){ 
			$logo = imagecreatefromstring(file_get_contents($logo)); 
			$QR_width = imagesx($QR); 
			$QR_height = imagesy($QR); 
			$logo_width = imagesx($logo); 
			$logo_height = imagesy($logo); 
			$logo_qr_width = $QR_width/3; 
			$scale = $logo_width/$logo_qr_width; 
			$logo_qr_height = $logo_height/$scale; 
			imagecopyresampled($QR, $logo, $QR_width/3, $QR_height/3, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height); 
		}
		imagepng($QR);
		$save = "qrq.png"; 
		imagepng($QR, $save); 
		imagedestroy($QR); 
		exit();
	}
	
	public function export()
	{
		//获取列表
		$Security = model('Security');
		$list = $Security->code_list()->toArray();
		$this->export_excel($list);
	}
	
	private function export_excel($list)
	{
		require_once VENDOR_PATH.'PHPExcel.php';
		$objPHPExcel = new \PHPExcel();
        $name = '防伪码列表';
        $name = iconv('UTF-8', 'GBK', $name);
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");


        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)//Excel的第A列，uid是你查出数组的键值，下面以此类推
        ->setCellValue('A1', 'ID')
        ->setCellValue('B1', '防伪码')
        ->setCellValue('C1', '查询方式')
        ->setCellValue('D1', '查询时间')
        ->setCellValue('E1', '查询结果')
        ->setCellValue('F1', '归属地')
		->setCellValue('G1', '二维码');
		
        $num = 0;
        if (!empty($list['data']) && is_array($list['data']))
        {
            foreach($list['data'] as $k => $v){
                $num=$k+2;
                $objPHPExcel->setActiveSheetIndex(0)//Excel的第A列，uid是你查出数组的键值，下面以此类推
                ->setCellValue('A'.$num, $v['code_id'])
                ->setCellValue('B'.$num, $v['security_code'])
                ->setCellValue('C'.$num, '网站查询')
				->setCellValue('D'.$num, $v['query_time'])
                ->setCellValue('E'.$num, '真')
                ->setCellValue('F'.$num, '上海')
                ->setCellValue('G'.$num, '');
            }
        }

        $objPHPExcel->getActiveSheet()->setTitle('防伪码列表');
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: applicationnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$name.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
        $objWriter->save('php://output');
        exit;
	}
	
	private function export_code($list)
	{
		require_once VENDOR_PATH.'PHPExcel.php';
		$objPHPExcel = new \PHPExcel();
        $name = '防伪码';
        $name = iconv('UTF-8', 'GBK', $name);
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");


        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)//Excel的第A列，uid是你查出数组的键值，下面以此类推
        ->setCellValue('A1', '防伪码');
		
        $num = 0;
        if (!empty($list) && is_array($list))
        {
            foreach($list as $k => $v){
                $num=$k+2;
                $objPHPExcel->setActiveSheetIndex(0)//Excel的第A列，uid是你查出数组的键值，下面以此类推
                ->setCellValue('A'.$num, ' '.$v);
            }
        }

        $objPHPExcel->getActiveSheet()->setTitle('防伪码');
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: applicationnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$name.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
        $objWriter->save('php://output');
        exit;
	}
	
	private function security_code()
	{
		$Security = model('Security');
		$code = $Security->last_code();
		if(empty($code))
		{
			$code = 0;
		}
		
		$security_code_arr = array();
		for($i=1; $i<=$this->num; $i++)
		{	
			
			$_code = $code + $i;
			if(strlen($_code) < $this->size)
			{
				//补齐位数
				$l = $this->size - strlen($_code);
				for($m=0; $m<$l; $m++)
				{
					$_code = '0'.$_code;
				}
			}
			array_push($security_code_arr, $_code);
		}
		//$security_code = implode(',',$security_code_arr);
		//插入db
		$data = array();
		foreach($security_code_arr as $key => $val)
		{
			$data[$key] = array(
				'security_code'	=> $val,
				'create_time'	=> date("Y-m-d H:i:s", time()),
				'update_time'	=> date("Y-m-d H:i:s", time()),
				'qrcode'		=> '',
				'prefix'		=> $this->prefix
			);
			$this->list[$key] = $val;
			
		}
		$result = $Security->insert_code($data);
		
		return $result;
	}

}