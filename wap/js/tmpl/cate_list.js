$(function(){
    $('.open_search_btn').each(function() {
        $(this).click(function(){
            var search_url = WapSiteUrl + '/tmpl/search.html';
            if(typeof(app_interface) == 'object'){
                app_interface.openWebView(search_url, 1);return;
            }else{
                document.location.href = search_url;
            }

        });
    });
    ajax_data({},true);
	


    //选择分类
    $('body').on('click' , '.cate-first .swiper-slide' , function(){
        var gc_id = $(this).attr('gc_id');
        $(this).addClass('action').siblings().removeClass('action');
        var data = {is_ajax:1,gc_id:gc_id};
        ajax_data(data,true);
		var l  = $(".cate-first .swiper-slide").length;
		var w  = $(".cate-first .swiper-slide").width();
		var wW = $(window).width();
		var i  = $(this).index();
		var wy = ((i+1)*w - w/2) - wW/2;
		var sy = w*l - wW;
		  if((wy > 0)&&(sy > wy)){			 
			  $("#cate-first .swiper-wrapper").css({"transform":"translate3d("+(-wy)+"px, 0px, 0px)","transition-duration":"0.3s"});
		  }else if((wy > 0)&&(sy < wy)){
			 $("#cate-first .swiper-wrapper").css({"transform":"translate3d("+(-sy)+"px, 0px, 0px)","transition-duration":"0.3s"});
		  }else{
			  $("#cate-first .swiper-wrapper").css({"transform":"translate3d(0px, 0px, 0px)","transition-duration":"0.3s"});
		  }
		});

    //选择价格
    /*$('body').on('click' , '.cate-price-points-show a' , function(){
        var price = $(this).html();
        document.location.href = WapSiteUrl+'/tmpl/product_list.html?price='+price;
    });
    $('.cate-price-points-show a').each(function(){
        $(this).attr('href',WapSiteUrl+'/tmpl/product_list.html?price='+$(this).text());
    });*/

});

function ajax_data(data,is_add){
    //加载进度
    var layer_index = layer.load(2, {shade:false});

    $.ajax({
        url: SiteUrl + "/mall_m/index.php?act=goods_class&op=ajax_data",
        type: 'get',
        data:data,
        dataType: 'json',
        beforeSend:function(){

        },
        success: function(result) {
            var data = result.datas;
            data.WapSiteUrl = WapSiteUrl;
            if(data.top_list.length > 0) {
                var top_html = template.render('top-list', data);
                $("#cate-first").html(top_html);
            }
			
			var mySwiper1 = new Swiper('#cate-first', {
                freeMode: true,
                slidesPerView: 'auto',
             });	
			
            if(data.child_list.length > 0) {
                var　child_html = template.render('child-list',data);
                $("#cate-second").html(child_html);
            }else{
                $('#cate-second').html('<div style="height: 100px;width: 100%;line-height: 100px;text-align: center;font-size: 0.16rem;">暂时没有相关分类</div>');
            }
            $("img.lazy").lazyload({effect: "fadeIn",threshold:"400"});
            layer.close(layer_index);
            bind_openwebview();
        }
    });
}