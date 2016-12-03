function load_member_info(){
    var key = getcookie('key');
    var data = {};
    var avatar_tit_cont = '登录/注册';

    if(key){
        avatar_tit_cont = '编辑资料';
         $.ajax({
             type:'post',
             url:ApiUrl+"/index.php?act=member_index",
             data:{key:key},
             dataType:'json',
             //jsonp:'callback',
             success:function(result){
                 data = result.datas;
                 data.avatar_tit_cont = avatar_tit_cont;
                 data.WapSiteUrl = WapSiteUrl;

                 var html = template.render('member_center', data);
                 $('#content_main').html(html);
                     bind_openwebview();
                 //$('#username').html(result.datas.member_info.user_name);
                 //$('#point').html(Number(result.datas.member_info.point).toFixed(2));
                 //$('#predepoit').html(result.datas.member_info.predepoit);
                 //$('#available_rc_balance').html(result.datas.member_info.available_rc_balance);
                 //$('#avatar').attr("src",result.datas.member_info.avator);
                 //$('#member_server').attr("href",'tel:'+result.datas.web_config.site_tel400);
                 return false;
             }
         });
    }

    //积分
    $('body').on('click','#member_integrate',function(){
        app_toast('敬请期待');
    });

    //优惠卷
    $('body').on('click','#member_coupons',function(){
        app_toast('敬请期待');
    });
    if(key==undefined || key == ''){
        $('a').each(function(){
            $(this).unbind('click').bind('click',function(){
                if(getcookie('key')==undefined || getcookie('key') == ''){
                    app_check_login(getcookie('key'));
                    return false;
                }else{
                    load_member_info();
                }
            });
        });
    }else{
        bind_openwebview();

    }
}
$(function(){
    //load_member_info();
});

