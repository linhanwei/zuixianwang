$(function(){
    var referurl = document.referrer;//上级网址
    $("input[name=referurl]").val(referurl);
    $('#btn_login').click(function(){//会员登陆
        var username = $('#txt_userName').val();
        var pwd = $('#txt_password').val();
        var t =  $("input[name=t]").val();
        var token =  $("input[name=token]").val();
        var client = 'pc';

        if(!isphone(username) || pwd == ''){
            alert('请输入手机或密码');
        }else{
            $.ajax({
                type:'post',
                url:ApiUrl+"/index.php?act=login",
                data:{mobile_phone:username,password:pwd,client:client,form_submit:'ok',t:t,token:token},
                dataType:'json',
                beforeSend:function(){
                    loading();
                },
                success:function(result){
                    if(!result.datas.error){
                        if(typeof(result.datas.key)=='undefined'){
                            return false;
                        }else{
                            addcookie('member_id',result.datas.member_info.member_id,1);
                            addcookie('member_name',result.datas.member_info.member_name,1);
                            addcookie('agent_area',result.datas.member_info.agent_area,1);
                            addcookie('available_predeposit',result.datas.member_info.available_predeposit,1);
                            addcookie('grade_name',result.datas.member_info.grade_name,1);
                            addcookie('grade_id',result.datas.member_info.grade_id,1);
                            addcookie('yestoday_redeemable',result.datas.member_info.yestoday_redeemable,1);
                            addcookie('key',result.datas.key,1);

                            location.href = 'index.php';
                        }
                    }else{
                        close_loading();
                        alert(result.datas.error);
                    }
                },
                error:function(){
                    ajax_error();
                }
            });
        }
    });
    $('#btn_register').click(function(){
        layer.open({
            title:'用户注册',
            type: 2,
            area: ['400px', '300px'],
            fix: false,
            maxmin: false,
            content: ClientSiteUrl + '/index.php?act=register'
        });
    });
});