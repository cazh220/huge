$(function(){
	
	//省市联动
	$("#province").change(function(){
		var pid = $(this).val();
		$.ajax({
			url:'http://huge.com/public/index.php/index/region/get_region',
			type:'get',
			data:'id='+pid,
			dataType:'json',
			success:function(msg){
				var txt='<option value="">请选择</option>';
				$.each(msg, function(i, n){
					txt += '<option value="'+n.id+'">'+n.name+'</option>';
				});
				$("#city").html(txt);
			}
		});
	});
	
	//市县联动
	$("#city").change(function(){
		var pid = $(this).val();
		$.ajax({
			url:'http://huge.com/public/index.php/index/region/get_region',
			type:'get',
			data:'id='+pid,
			dataType:'json',
			success:function(msg){
				var txt='';
				$.each(msg, function(i, n){
					txt += '<option value="'+n.id+'">'+n.name+'</option>';
				});
				$("#district").html(txt);
			}
		});
	});
	
	
});

//发短信校验
function send_sms()
{
	//判断手机是否填写
	var mobile = $("#mobile").val();
	if(mobile=='')
	{
		alert('请填写手机号');
		return false;
	}
	
	//发短息ajax
	$.ajax({
		url:'http://huge.com/public/index.php/index/sms/sendsms',
			type:'get',
			data:'mobile='+mobile,
			dataType:'json',
			success:function(msg){
				//console.log(msg);
				alert(msg.message);
				return false;
			}
	});
	
}
//注册校验
function check_user()
{
	//校验手机
	var mobile = $("#mobile").val();
	if(mobile=='')
	{
		alert('请填写手机号');
		return false;
	}
	//校验短信验证码ajax
	var code = $("#code").val();
	if(code == '')
	{
		alert('请填写短信验证码');
		return false;
	}
	else
	{
		$.ajax({
			url:'http://huge.com/public/index.php/index/sms/check_sms',
				type:'get',
				data:'mobile='+mobile+'&code='+code,
				dataType:'json',
				success:function(msg){
					if(msg.status==1)
					{
						alert(msg.message);
						return false;
					}
					else
					{
						//校验密码
						var fpassword = $("#password").val();
						var repassword = $("#repassword").val();
						if(fpassword == '')
						{
							alert('请填写密码');
							return false;
						}
						
						if(repassword == '')
						{
							alert('请填写确认密码');
							return false;
						}
						
						if(fpassword != repassword)
						{
							alert('密码不一致');
							return false;
						}
						
						//请填写单位名
						var company_name = $("#company_name").val();
						if(company_name == '')
						{
							alert('请填写姓名');
							return false;
						}
						
						//单位地址
						var company_addr = $("#company_addr").val();
						if(company_addr == '')
						{
							alert('请填写单位地址');
							return false;
						}
						
						
						$("#userinfoform").submit();
					}
					//console.log(msg);
				}
		});
	}
	
}

//登录
function login_s()
{
	var mobile = $("#user").val();
	if(mobile=='')
	{
		alert('请填写手机号');
		return false;
	}
	var password = $("#password").val();
	if(password == '')
	{
		alert('请填写密码');
		return false;
	}
	
	$.ajax({
		url:'http://huge.com/public/index.php/index/index/do_login',
			type:'get',
			data:'mobile='+mobile+'&password='+password,
			dataType:'json',
			success:function(msg){
				if(msg.status==1)
				{
					alert(msg.message);
					return false;
				}
				else
				{
					window.location.href='index';
				}
			}
	});
	
	
}