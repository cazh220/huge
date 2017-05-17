<?php
namespace app\admin\controller;
use think\Controller;
use think\View;
use think\Session;

class User
{
    public function index()
    {
		//筛选参数
		$keyword = input("keyword");
		$dental = input("dental");
		$hospital = input("hospital");
		$keyword = !empty($keyword) ? addslashes(trim($keyword)) : '';
		$dental = !empty($dental) ? addslashes(trim($dental)) : '';
		$hospital = !empty($hospital) ? addslashes(trim($hospital)) : '';
		$param = array(
			'keyword'	=> $keyword,
			'dental'	=> $dental,
			'hospital'	=> $hospital
		);
		
		$User = Model("User");
		$res = $User->user_list($param);
		$page = $res->render();
		
		$data = $res->toArray();
		$view = new View();
		$view->assign('user', $data);
		$view->assign('page', $page);
		return $view->fetch('index/user/index');
    }
	
	public function add()
	{
		$year = range(1920,2050);
		$month = range(1,12);
		$day = range(1,31);
		$view = new View();
		$view->assign('year', $year);
		$view->assign('month', $month);
		$view->assign('day', $day);
		return $view->fetch('index/user/add');
	}
	
	public function add_user()
	{
		$username = input("username");
		$password = input("password");
		$mobile = input("mobile");
		$realname = input("realname");
		$user_type = input("user_type");
		$sex = input("sex");
		$email = input("email");
		$company_name = input("company_name");
		$company_addr = input("company_addr");
		$company_phone = input("company_phone");
		$department = input("department");
		$position = input("position");
		$year = input("year");
		$month = input("month");
		$day = input("day");
		$person_num = input("person_num");
		$zipcode = input("zipcode");
		
		//生日验证
		if ($month < 10)
		{
			$month = "0".$month;
		}
		if ($day < 10)
		{
			$day = "0".$day;
		}
		
		$time = $year."-".$month."-".$day." 00:00:00";
		if (strtotime($time) == false)
		{
			echo "<script>alert('生日日期选择错误');history.back();</script>";
		}
		
		$params = array(
			'username'		=> $username,
			'password'		=> md5($password),
			'mobile'		=> $mobile,
			'realname'		=> $realname,
			'user_type'		=> $user_type,
			'sex'			=> $sex,
			'email'			=> $email,
			'company_name'	=> $company_name,
			'company_phone'	=> $company_phone,
			'company_addr'	=> $company_addr,
			'department'	=> $department,
			'position'		=> $position,
			'birthday'		=> $year.$month.$day,
			'persons_num'	=> $person_num,
			'zipcode'		=> $zipcode,
			'create_time'	=> date("Y-m-d H:i:s", time())
		);
		

		$User = Model("User");
		$res = $User->insert_user($params);
		
		if ($res == 1)
		{
			$data = array(
				'admin_id'		=> Session::get('admin_id'),
				'user_id'		=> 0,
				'username'		=> Session::get('username'),
				'content'		=> '新增会员:'.$username,
				'create_time'	=> date("Y-m-d H:i:s", time()),
				'ip'			=> $_SERVER['REMOTE_ADDR']	
			);
			$User->insert_user_action($data);
			echo "<script>window.location.href='index';</script>";
		}
		else
		{
			echo "<script>alert('新增会员失败');history.back();</script>";
		}
		
		
	}
	
	public function user_detail()
	{
		$user_id = input("user_id");
		$user_id = !empty($user_id) ? intval($user_id) : 0;
		
		$User = Model("User");
		$res = $User->user_detail($user_id);
		
		$view = new View();
		$view->assign('detail', $res[0]);
		return $view->fetch('index/user/detail');
	}
	
	public function edit_user()
	{
		$user_id = input("user_id");
		//var_dump($user_id);
		
		//获取详情
		$User = Model("User");
		$res = $User->user_detail($user_id);
		
		if ($res[0]['birthday'])
		{
			$year = intval(substr($res[0]['birthday'], 0, 4));
			$month = intval(substr($res[0]['birthday'], 4, 2));
			$day = intval(substr($res[0]['birthday'], 6, 2));
		}
		$res[0]['year'] = $year ? $year : 0;
		$res[0]['month'] = $month ? $month : 0;
		$res[0]['day'] = $day ? $day : 0;
		
		$year_ary = range(1920,2050);
		$month_ary = range(1,12);
		$day_ary = range(1,31);

		$view = new View();
		$view->assign('year', $year_ary);
		$view->assign('month', $month_ary);
		$view->assign('day', $day_ary);
		$view->assign('user', $res[0]);
		return $view->fetch('index/user/edit_user');
	}
	
	public function edit()
	{
		$username = input("username");
		$mobile = input("mobile");
		$realname = input("realname");
		$user_type = input("user_type");
		$sex = input("sex");
		$email = input("email");
		$company_name = input("company_name");
		$company_addr = input("company_addr");
		$company_phone = input("company_phone");
		$department = input("department");
		$position = input("position");
		$year = input("year");
		$month = input("month");
		$day = input("day");
		$person_num = input("person_num");
		$zipcode = input("zipcode");
		$user_id = intval(input("id"));
		
		//生日验证
		if ($month < 10)
		{
			$month = "0".$month;
		}
		if ($day < 10)
		{
			$day = "0".$day;
		}
		
		$time = $year."-".$month."-".$day." 00:00:00";
		if (strtotime($time) == false)
		{
			echo "<script>alert('生日日期选择错误');history.back();</script>";
		}
		
		$params = array(
			'username'		=> $username,
			'mobile'		=> $mobile,
			'realname'		=> $realname,
			'user_type'		=> $user_type,
			'sex'			=> $sex,
			'email'			=> $email,
			'company_name'	=> $company_name,
			'company_phone'	=> $company_phone,
			'company_addr'	=> $company_addr,
			'department'	=> $department,
			'position'		=> $position,
			'birthday'		=> $year.$month.$day,
			'persons_num'	=> $person_num,
			'zipcode'		=> $zipcode,
			'create_time'	=> date("Y-m-d H:i:s", time()),
			'user_id'		=> $user_id
		);

		$User = Model("User");
		$res = $User->update_user($params);
		
		if ($res == 1)
		{
			$data = array(
				'admin_id'		=> Session::get('admin_id'),
				'user_id'		=> $user_id,
				'username'		=> Session::get('username'),
				'content'		=> '编辑会员:'.$username,
				'create_time'	=> date("Y-m-d H:i:s", time()),
				'ip'			=> $_SERVER['REMOTE_ADDR']	
			);
			$User->insert_user_action($data);
			echo "<script>window.location.href='index';</script>";
		}
		else
		{
			echo "<script>alert('编辑会员失败');history.back();</script>";
		}
	}
	
	public function delete_user()
	{
		$user_id = input("user_id");
		if (!empty($user_id))
		{
			$User = Model("User");
			$user_arr = explode(",", $user_id);
			foreach($user_arr as $key => $val)
			{
				$res = $User->delete_user($val);
			}
		}
		
		if ($res == 1)
		{
			echo "<script>window.location.href='index';</script>";
		}
		else
		{
			echo "<script>alert('删除会员失败');history.back();</script>";
		}
	}
	
	public function export()
	{
		require_once VENDOR_PATH.'PHPExcel.php';
		$objPHPExcel = new \PHPExcel();
        $name = '会员名单';
        $name = iconv('UTF-8', 'GBK', $name);
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
        
		//获取列表
		$User = Model("User");
		$list = $User->user_list()->toArray();

        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('N1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('O1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('P1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('Q1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('R1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('S1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('T1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)//Excel的第A列，uid是你查出数组的键值，下面以此类推
        ->setCellValue('A1', '账户名')
        ->setCellValue('B1', '手机号')
        ->setCellValue('C1', '真实姓名')
        ->setCellValue('D1', '密码')
        ->setCellValue('E1', '会员类型')
        ->setCellValue('F1', '性别')
		->setCellValue('G1', '电子邮箱')
		->setCellValue('H1', '出生年月')
		->setCellValue('I1', '单位名称')
		->setCellValue('J1', '单位地址')
		->setCellValue('K1', '单位电话')
		->setCellValue('L1', '部门')
		->setCellValue('M1', '职位')
		->setCellValue('N1', '累计积分')
		->setCellValue('O1', '已兑换积分')
		->setCellValue('P1', '积分余额')
		->setCellValue('Q1', '人员数')
		->setCellValue('R1', '邮编')
		->setCellValue('S1', '审核状态')
        ->setCellValue('T1', '创建时间');
		
        $num = 0;
        if (!empty($list['data']) && is_array($list['data']))
        {
            foreach($list['data'] as $k => $v){
                $num=$k+2;
				if($v['user_type'] == 1)
				{
					$user_type = '技工所';
				}
				else if($v['user_type'] == 1)
				{
					$user_type = '医生';
				}
				else
				{
					$user_type = '其他';
				}
				
				if ($v['sex'] == 1)
				{
					$sex = '男';
				}
				else if ($v['sex'] == 2)
				{
					$sex = '女';
				}
				else
				{
					$sex = '未知';
				}
                $objPHPExcel->setActiveSheetIndex(0)//Excel的第A列，uid是你查出数组的键值，下面以此类推
                ->setCellValue('A'.$num, $v['username'])
                ->setCellValue('B'.$num, $v['mobile'])
                ->setCellValue('C'.$num, $v['realname'])
				->setCellValue('D'.$num, $v['password'])
                ->setCellValue('E'.$num, $user_type)
                ->setCellValue('F'.$num, $v['sex'])
                ->setCellValue('G'.$num, $v['email'])
                ->setCellValue('H'.$num, $v['birthday'])
				->setCellValue('I'.$num, $v['company_name'])
				->setCellValue('J'.$num, $v['company_addr'])
				->setCellValue('K'.$num, $v['company_phone'])
				->setCellValue('L'.$num, $v['department'])
				->setCellValue('M'.$num, $v['position'])
				->setCellValue('N'.$num, $v['total_credits'])
				->setCellValue('O'.$num, $v['exchanged_credits'])
				->setCellValue('P'.$num, $v['left_credits'])
				->setCellValue('Q'.$num, $v['persons_num'])
				->setCellValue('R'.$num, $v['zipcode'])
				->setCellValue('S'.$num, $v['status'])
				->setCellValue('T'.$num, $v['create_time']);
            }
        }

        $objPHPExcel->getActiveSheet()->setTitle('会员名单');
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: applicationnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$name.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
        $objWriter->save('php://output');
        exit;
	}
	
	public function history()
	{
		$user_id = input("user_id");
		$user_id = !empty($user_id) ? intval($user_id) : 0;
		
		$User = Model("User");
		$list = $User->getHistory($user_id);
		
		$view = new View();
		$view->assign('list', $list->toArray());
		$view->assign('page', $list->render());
		return $view->fetch('index/user/user_action');
	}
	
	
	public function test()
	{
		var_dump(strtotime("2017-05-31 00:00:00"));
	}
	
	
	
}