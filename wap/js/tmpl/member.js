function load_member_info(){
    var key = getcookie('key');
    var data = {};
    data.WapSiteUrl = WapSiteUrl;
    var html = template.render('member_center', data);
    $('#content_main').html(html);
    //积分
    $('body').on('click','#member_integrate',function(){
        toast('敬请期待');
    });

    //优惠卷
    $('body').on('click','#member_coupons',function(){
        toast('敬请期待');
    });
    if(key==undefined || key == ''){
        $('a').each(function(){
            $(this).unbind('click').bind('click',function(){
                if(getcookie('key')==undefined || getcookie('key') == ''){
                    app_interface.openWebView(WapSiteUrl + '/tmpl/member/login.html?func=load_member_info',8);
                    return false;
                }else{
                    load_member_info();
                }
            });
        });
    }else{
        bind_openwebview();
        /*
         $.ajax({
         type:'post',
         url:ApiUrl+"/index.php?act=member_index",
         data:{key:key},
         dataType:'json',
         //jsonp:'callback',
         success:function(result){
         var data = result.datas;
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
         });*/
    }


}
$(function(){

    load_member_info();



});

