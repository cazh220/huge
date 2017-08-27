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
		$view->assign('user', Session::get('user.mobile'));
		return $view->fetch('index');
	}
	
	//积分明细
	public function detail()
	{
		$user_id = Session::get('user.user_id');
		$Credits = model('Credits');
		$res  = $Credits->get_credits_detil($user_id);
		if($res)
		{
			foreach($res as $key => $val)
			{
				if($val['type'] == 0)
				{
					$res[$key]['type_name'] = '注册';
				}
				elseif($val['type'] == 1)
				{
					$res[$key]['type_name'] = '录入信息';
				}
				elseif($val['type'] == 2)
				{
					$res[$key]['type_name'] = '兑换';
				}
				else
				{
					$res[$key]['type_name'] = '其他';
				}
			}
		}
		//print_r($res);die;
		
		$view = new View();
		$view->assign('list', $res);
		$view->assign('users', Session::get('user.mobile'));
		return $view->fetch('detail');
	}

	

}
