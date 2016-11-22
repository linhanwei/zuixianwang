$(function(){
    var data = {
            reg_url:SiteUrl+'/invite/',
            forget_pass_url:WapSiteUrl+'/tmpl/member/forget.html'
        };
    var login_html = template.render('login_html', data);
    $('#content_main').html(login_html);

	var referurl = document.referrer;//上级网址

	$("input[name=referurl]").val(referurl);
	$.sValid.init({
        rules:{
            username:"required",
            userpwd:"required"
        },
        messages:{
            username:"用户名必须填写！",
            userpwd:"密码必填!"
        },
        callback:function (eId,eMsg,eRules){
            if(eId.length >0){
                var errorHtml = "";
                $.map(eMsg,function (idx,item){
                    errorHtml += "<p>"+idx+"</p>";
                });
                $(".error-tips").html(errorHtml).show();
            }else{
                 $(".error-tips").html("").hide();
            }
        }  
    });

    //记住密码
    $('.rember-pass-box').click(function(){
        if($(this).hasClass('action')){
            $(this).removeClass('action');
        }else{
            $(this).addClass('action');
        }
    });

    $('#username').val(getCache('user_name')?getCache('user_name'):'');
    $('#userpwd').val(getCache('user_password')?getCache('user_password'):'');
	$('#loginbtn').click(function(){//会员登陆
		var username = $('#username').val();
		var pwd = $('#userpwd').val();
		var client = 'wap';
		if($.sValid()){
	          $.ajax({
				type:'post',
				url:ApiUrl+"/index.php?act=login",	
				data:{username:username,password:pwd,client:client},
				dataType:'json',
				success:function(result){
					if(!result.datas.error){
						if(typeof(result.datas.key)=='undefined'){
							return false;
						}else{
							addcookie('username',result.datas.username);
							addcookie('key',result.datas.key);
                            setCache('user_name',username);
                            if($('.rember-pass-box').hasClass('action')){
                                setCache('user_password',pwd);
                            }else{
                                setCache('user_password','');
                            }

							location.href = referurl;
						}
						$(".error-tips").hide();
					}else{
						$(".error-tips").html(result.datas.error).show();
					}
				}
			 });  
        }
	});

});
