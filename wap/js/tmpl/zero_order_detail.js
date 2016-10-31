var key = getcookie('key');
$(function(){

    //获取订单详细信息
    var oid = GetQueryString("oid");
    if(oid){
        $.ajax({
            type:'get',
            url:ApiUrl+'/index.php?act=zero_order&op=order_detail',
            data:{key:key,oid:oid},
            dataType:'json',
            success:function(result){
                var is_login = result.login;

                if(is_login == 0){
                    window.location.href = WapSiteUrl+'/tmpl/member/login.html';
                }else {
                    var data = result.datas;
                    var html = template.render('home_detail', data);
                    $(".goods_detail").append(html);

                    //初始化轮播图
                    $('.am-slider').flexslider({
                        // slideshowSpeed: 1000,
                    });
                    if (!data.error && data.goods_detail.goods_detail) {
                        $('#detail_content').html(data.goods_detail.goods_detail);

                        //初始折叠面板
                        $.AMUI.accordion.init();
                    }
                }
            }
        });
    }

});
