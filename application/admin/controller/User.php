<?php
namespace app\admin\controller;
use think\Controller;
use think\View;

class User
{
    public function index()
    {
        $view = new View();
		return $view->fetch('index/user/index');
    }
	
	public function add()
	{
		$view = new View();
		return $view->fetch('index/user/add');
	}
	
	
}