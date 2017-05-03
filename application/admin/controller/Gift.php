<?php
namespace app\admin\controller;
use think\Controller;
use think\View;

class Gift 
{
	public function index()
    {
        $view = new View();
		return $view->fetch('index/gift/index');
    }
	
	public function add()
	{
		$view = new View();
		return $view->fetch('index/gift/add');
	}
}