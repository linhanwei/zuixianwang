$(function(){
    var key = getcookie('key');

    if(key==undefined || key == ''){
        var data = {};
        data.WapSiteUrl = WapSiteUrl;
        var html = template.render('member_center', data);
        $('#content_main').html(html);
        $('a').each(function(){
            $(this).click(function(){
                app_interface.openWebView(WapSiteUrl + '/tmpl/member/login.html',8);
                return false;
            });
        });

        var t = setInterval(function(){
            if(getcookie('key')){
                document.location.href = document.location.href;
            }
        },500);
    }else{
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
        });
    }


    //积分
    $('body').on('click','#member_integrate',function(){
        layer.msg('敬请期待');
    });

    //优惠卷
    $('body').on('click','#member_coupons',function(){
        layer.msg('敬请期待');
    });

});