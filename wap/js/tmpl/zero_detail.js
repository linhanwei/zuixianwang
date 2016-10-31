$(function(){

    //获取商品详细信息
    var goods_id = GetQueryString("goods_id");
    if(goods_id){
        $.ajax({
            type:'get',
            url:ApiUrl+'/index.php?act=zero_goods&op=goods_detail',
            data:{goods_id:goods_id},
            dataType:'json',
            success:function(result){

                var data = result.datas;
                var html = template.render('home_detail', data);
                $(".goods_detail").append(html);

                //初始化轮播图
                $('.am-slider').flexslider();
                if (data.goods_detail.goods_detail) {
                    $('#detail_content').html(data.goods_detail.goods_detail);

                    //初始折叠面板
                    $.AMUI.accordion.init();
                }
            }
        });
    }

});
