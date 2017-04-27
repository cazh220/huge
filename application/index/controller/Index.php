<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\View;

class Index
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
    }
	
	public function list_users()
	{
		$data = Db::query('select * from hg_users');
		//print_r($data);
		$view = new View();
		$view->assign('data', $data);
		//$view->name = 'caozheng';
		return $view->fetch('list');
	}
	
	public function view_user()
	{
		$data = Db::query('select * from hg_users where id = :id', ['id'=>1]);
		
		print_r($data);
	}
	
	public function insert_user()
	{
		$res = Db::execute('insert into hg_users(user, name, create_time, credits)values(:user, :name, :create_time, :credits)', ['user'=>'baoyu', 'name'=>'薄玉', 'create_time'=>date('Y-m-d H:i:s', time()), 'credits'=>50]);
		var_dump($res);
	}
	
	public function update_user()
	{
		$res = Db::execute('update hg_users set create_time = :create_time where user = :user', ['create_time'=>date('Y-m-d H:i:s', time()), 'user'=>'taiang']);
		var_dump($res);
	}
	
	public function delete_user()
	{
		$res = Db::execute('delete from hg_users where id = :id', ['id'=>7]);
		var_dump($res);
	}
}
