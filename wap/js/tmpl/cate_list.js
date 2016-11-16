$(function(){

    ajax_data({},true);

    //选择分类
    $('body').on('click' , '.cate-first a' , function(){
        var gc_id = $(this).attr('gc_id');
        $(this).addClass('action').siblings().removeClass('action');
        var data = {is_ajax:1,gc_id:gc_id};
        ajax_data(data,true);
    });

    //选择价格
    $('body').on('click' , '.cate-price-points-show a' , function(){
        var price = $(this).html();
        document,location.href = SiteUrl+'/wap/tmpl/product_list.html?price='+price;
    });

});

function ajax_data(data,is_add){
    //加载进度
    var layer_index = layer.load(0, {shade:false});

    $.ajax({
        url: SiteUrl + "/mall_m/index.php?act=goods_class&op=ajax_data",
        type: 'get',
        data:data,
        dataType: 'json',
        beforeSend:function(){

        },
        success: function(result) {
            var data = result.datas;

            if(data.top_list.length > 0) {
                var top_html = template.render('top-list', data);
                $("#cate-first").html(top_html);
            }
            if(data.child_list.length > 0) {
                data.WapSiteUrl = WapSiteUrl;
                var　child_html = template.render('child-list',data);
                $("#cate-second").html(child_html);
            }else{
                $('#cate-second').html('<div style="height: 100px;width: 100%;line-height: 100px;text-align: center;font-size: 0.16rem;">暂时没有相关分类</div>');
            }
            $("img.lazy").lazyload({effect: "fadeIn",threshold:"400"});
            layer.close(layer_index);
        }
    });
}