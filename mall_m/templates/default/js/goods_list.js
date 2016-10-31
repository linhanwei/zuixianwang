$(function(){

    //商品排序
    $('.product-filter .keyorder').click(function(e){
        var key = parseInt($(this).attr('key')),
            gc_id = GetQueryString('gc_id'),
            keyword = GetQueryString('keyword'),
            order = 0;
        console.log(keyword);
        $(this).addClass('current').siblings().removeClass('current');

        //价格按高低排序
        if(key == 3){
            if($(this).find('.fleft').hasClass("desc")){
                $(this).find('.fleft').removeClass('desc').addClass('asc');
                order = 1;
            }else{
                $(this).find('.fleft').removeClass('asc').addClass('desc');
            }
        }
		
		if(key == 2){
		  if( $(".prolist-box").hasClass('action')	){
			   $(".prolist-box").removeClass('action');
			   $(this).find('span').removeClass('action');
		  }else{
			   $(".prolist-box").addClass('action');
			   $(this).find('span').addClass('action');
		  }
		  return false;
		}

        var data = {gc_id:gc_id,key:key,order:order,keyword:keyword,is_ajax:1};
        ajax_data(data,true);

    });

    //商品滑动分页加载
    window.page_scrolling = false;
    window.page_hasmore = false;
    window.curpage = 2;
    $(window).scroll(function () {
        var __current = $('.product-filter .current'),
            key = parseInt(__current.attr('key')),
            gc_id = GetQueryString('gc_id'),
            keyword = GetQueryString('keyword'),
            order = 0;

        //价格按高低排序
        if(key == 3 && __current.find('.fleft').hasClass("asc")){
            order = 1;
        }

        var bot = 50,//bot是底部距离的高度
            data = {gc_id:gc_id,key:key,order:order,keyword:keyword,curpage:window.curpage,is_ajax:1};
        if ((bot + $(window).scrollTop()) >= ($(document).height() - $(window).height())) {
                //正在请求当中或者没有更多数据的时候就不再请求数据
                if(window.page_scrolling | window.page_hasmore | page_count == 1) return;
                window.page_scrolling = true;
                ajax_data(data,false);
        }
    });
});

function ajax_data(data,is_add){
    //加载进度
    var layer_index = layer.load(0, {shade:false});

    $.ajax({
        url: SiteUrl + "/mall_m/index.php?act=goods&op=list",
        type: 'get',
        data:data,
        dataType: 'json',
        beforeSend:function(){

        },
        success: function(result) {
            var data = result.datas.goods_list,
                html = '';
            //console.log(gc_id,data);
            if(data.length > 0){
                for(var i in data){
                    html += '<a href="'+SiteUrl+'/mall_m/index.php?act=goods&op=detail&goods_id='+data[i].goods_id+'" class="prolist-item">';
                    html += '<div class="prolist-item-inline">';
                    html += '<div class="img">';
                    html += '<img class="lazy" src="'+SiteUrl+'/mall_m/templates/default/images/default_grey.png" data-original="'+data[i].goods_image_url+'">';
                    html += '</div>';
                    html += '<div class="info">';
                    html += '<h2>'+data[i].goods_name+'</h2>';
                    html += '<p>￥'+data[i].goods_price+'</p>';
                    html += '</div>';
                    html += '</div>';
                    html += '</a>';
                }

                if(is_add){
                    $('.prolist-box').html(html);
                }else{
                    $('.prolist-box').append(html);
                }
            }else{
                $('.prolist-box').html('<div style="height: 100px;width: 100%;line-height: 100px;text-align: center;font-size: 1.5rem;">暂时没有相关商品</div>');
            }
            $("img.lazy").lazyload({effect: "fadeIn",threshold:"400"});
            layer.close(layer_index);
            window.page_scrolling = false;
            window.page_hasmore = !result.hasmore;
            window.curpage++;
        }
    });
}