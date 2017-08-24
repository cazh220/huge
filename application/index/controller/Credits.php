<?php
namespace app\index\controller;

use think\Controller;
use think\Model;
use think\Db;
use think\View;
use think\Session;

class Credits
{	
	public function search_credits()
	{
		$Credits = model('Credits');
		$mine_credits = $Credits->query_credists(Session::get('user.user_id'));
		$view = new View();
		$view->assign('credits', $mine_credits[0]);
		return $view->fetch('index');
	}

	

}
