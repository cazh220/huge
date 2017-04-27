<?php
namespace app\admin\controller;
use think\Controller;
use think\View;
use think\Config;

class Index
{
    public function index()
    {
		//print_r(Config::get('host'));die;
        $view = new View();
		return $view->fetch();
    }
	
	
}
