$(function(){
    var data = {
            reg_url:SiteUrl+'/invite/',
            forget_pass_url:WapSiteUrl+'/tmpl/member/forget.html'
        };
    var login_html = template.render('login_html', data);
    $('#content_main').html(login_html);
    bind_openwebview();
	var referurl = document.referrer;//上级网址

	$("input[name=referurl]").val(referurl);

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
        //alert(getQueryString('to_root'));
        //alert(getQueryString('func'));
        var username = $('#username').val();
        var pwd = $('#userpwd').val();
        var client = 'wap';
        if(username == '') {app_toast('请输入用户名');$('#username').focus();return;}
        if(pwd == '') {app_toast('请输入密码');$('#userpwd').focus();return;}
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
                        if(typeof(app_interface) == 'object'){
                            if(getQueryString('to_root')){
                                app_interface.closeToRootWebView(8,getQueryString('func') + '();');
                            }else{
                                app_interface.closeWebView(8,getQueryString('func') + '();');
                            }
                        }else{
                            location.href = referurl;
                        }
                    }
                }else{
                    app_toast(result.datas.error);
                }
            }
         });
    });

});
