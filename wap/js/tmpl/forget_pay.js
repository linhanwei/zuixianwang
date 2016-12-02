$(function(){


	var timeoutFlag; //定时器标志
	var countTokenNum = 0; //记录验证码错误次数
	var sendCodeTime = 60; //发送验证码间隔时间
	var isSendCode = true; //是否可以发送验证码
	var getPassSucc = false; //获取新密码是否成功
	var getCodeId = $("#getCode");

	//获取验证码
	getCodeId.click(function(){
		var phone = $("#username").val();

		if(phone == ''){
			app_alert("请输入手机号码!");
			return false;
		}
		if( !(/^1(3|4|5|7|8)\d{9}$/.test(phone))){
			app_alert("请输入正确手机号码!");
			return false;
		}

		if(!isSendCode){
			return false;
		}
		sendCountDown();

		$.ajax({
			type:'post',
			url:SiteUrl+"/m/index.php?act=login&op=send_code",
			data:{mobile_phone:phone,client:'wap'},
			dataType:'json',
			success:function(result){

				if(!result.datas.error){
					$('#cache_key').val(result.datas.cache_key);
				}else{
					app_alert(result.datas.error);
				}
			}
		});
	});

	//取回密码
	$('.getPasswordBtn').click(function(){
		var phone = $("#username").val();
		var code = $("#code").val();
		var cache_key = $('#cache_key').val();

		if(phone == ''){
			app_alert("请输入手机号码!");
			return false;
		}
		if( !(/^1(3|4|5|7|8)\d{9}$/.test(phone))){
			app_alert("请输入正确手机号码!");
			return false;
		}

		if(code == ''){
			app_alert("请输入手机验证码!");
			return false;
		}

		if(getPassSucc){
			app_alert("新密码已发送短信到您的手机上,请注意查收!!");
			return false;
		}

		$.ajax({
			type:'post',
			url:SiteUrl+"/m/index.php?act=login&op=getpaywd",
			data:{mobile_phone:phone,client:'wap',identifying_code:code,cache_key:cache_key},
			dataType:'json',
			success:function(result){

				if(!result.datas.error){
					getPassSucc = true;
					app_alert("新密码已发送短信到您的手机上,请注意查收!");
                    app_interface.closeWebView(2);
				}else{
					app_alert(result.datas.error);
				}
			}
		});
	});

	$('.greybg').click(function(){
		$(".msgbox").hide().remove();
		$(".greybg").hide().remove();
	});

	//倒计时60秒
	function sendCountDown() {
		if (sendCodeTime == 0) {
			getCodeId.html('重新获取手机验证码');
			sendCodeTime = 60;
			isSendCode = true;
			return false;
		}
		setTimeout(function() {
			sendCodeTime--;
			getCodeId.html(sendCodeTime);
			isSendCode = false;
			sendCountDown();
		},1000);
	}

});
