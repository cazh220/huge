<?php
namespace app\admin\controller;
use think\Controller;
use think\View;

class Security 
{
	public function index()
    {
		$Gift = model('Gift');
		$where['status'] = 0;
		$data = $Gift->getGift($where);
        $view = new View();
		$view->assign('data', $data);
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
			echo "<script>windows.location.href='./index';</script>";
		}
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
			$data = array(
				'security_code'	=> $val,
				'create_time'	=> date("Y-m-d H:i:s", time()),
				'update_time'	=> date("Y-m-d H:i:s", time()),
				'qrcode'		=> '',
				'prefix'		=> $this->prefix
			);
			$Security->insert_code($data);
		}
		
		return true;
	}

}