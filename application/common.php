<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

//生成菜单
function get_menu_list($menus)
{
	if(!empty($menus) && is_array($menus))
	{
		$top_menu = array();
		foreach($menus as $key => $val)
		{
			if ($val['parent_id'] == 0)
			{
				$top_menu[] = $val;
			}
		}
		//整合二级目录
		foreach($top_menu as $k => $v)
		{
			foreach($menus as $km => $vm)
			{
				if ($vm['parent_id'] == $v['action_id'])
				{
					$top_menu[$k]['child'][] = $vm;
				}
			}
		}
		//菜单array
		$menu_arr = array();
		foreach ($top_menu as $keys => $value)
		{
			$menu_arr[$keys]['text'] = $value['action_name'];
			if (!empty($value['child']) && is_array($value['child']))
			{
				foreach ($value['child'] as $key => $val)
				{
					$menu_arr[$keys]['items'][$key]['id'] = $val['action_id'];
					$menu_arr[$keys]['items'][$key]['text'] = $val['action_name'];;
					$menu_arr[$keys]['items'][$key]['href'] = $val['action_url'];;
				}
			}
		}
		
		$menu_list = array('id'=>1, 'menu'=>$menu_arr);
		
		return $menu_list ? json_encode($menu_list) : '';
	}
}

//检查权限
/*
function check_admin_priv($priv_action) 
{
	if ($_SESSION['action_list'] == 'all') {
		return true;
	}

	$r = strpos(',' . $_SESSION['action_list'] . ',', ',' . $priv_action . ',');
	
	if($r !== false) return true;

	echo "<script>alert("对不起您没有权限");history.back();</script>";
}
*/