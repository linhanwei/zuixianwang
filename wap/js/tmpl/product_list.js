var page_count = 1;

$(function(){
	var keyword = GetQueryString('keyword');
	//搜索赋值
	$('#keyword').val(keyword);

	var data = {keyword:keyword};
	//首次加载
	ajax_data(data,true,1);

	//商品排序
	$('.condition-box .condit').click(function(e){
		var sort_key = parseInt($(this).attr('sort_key')),
			gc_id = GetQueryString('gc_id'),
			keyword = GetQueryString('keyword'),
			order = 0;

		$(this).addClass('action').siblings().removeClass('action');

		//价格按高低排序
		if(sort_key == 3 || sort_key == 1){
			if($(this).find('.fleft').hasClass("desc")){
				$(this).find('.fleft').removeClass('desc').addClass('asc');
				order = 1;
			}else{
				$(this).find('.fleft').removeClass('asc').addClass('desc');
			}
		}
		
		if(sort_key == 6){
		  $(".siftings-box").addClass("action");
		}else{
			var data = {gc_id:gc_id,sort_key:sort_key,order:order,keyword:keyword,is_ajax:1};
			ajax_data(data,true);

		}

	});

	//价格搜索
	$('.cate-price-points-show a').click(function(e){
			var search_price = $(this).text();
			var data = {price:search_price};
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

function ajax_data(data,is_add,is_first){

	data.bug = GetQueryString('bug');
	data.price = data.price ? data.price : GetQueryString('price');

	//加载进度
	//var layer_index = layer.load(0, {shade:false});
	var layer_index = layer.msg('醉仙网加载中...', {
		icon: 16,
		time:0,
		shade:[0.5,'#000']
	});

	if(is_first == 1) {
		$('#good-list-show-box').html(getCache('goods_list'));
	}

	$.ajax({
		url: SiteUrl + "/mall_m/index.php?act=goods&op=goods_list",
		type: 'get',
		data:data,
		dataType: 'json',
		beforeSend:function(){

		},
		success: function(result) {
			var data = result.datas;
			//console.log(gc_id,data);
			var exit_goods_count = $('.good-list-show-box .item').length;

			page_count = result.page_total;
			data.SiteUrl = SiteUrl;
			if(data.goods_list.length > 0 || is_add){

				var goods_list_html = template.render('goods-list', data);
				if(is_add){
					if(is_first == 1){
						setCache('goods_list',goods_list_html);
					}
					$('#good-list-show-box').html(goods_list_html);
				}else{
					$('#good-list-show-box').append(goods_list_html);
				}
			}

			if((data.goods_list.length == 0 && is_add) || (data.goods_list.length == 0 && exit_goods_count == 0)){
				$('#good-list-show-box').html('<div style="height: 100px;width: 100%;line-height: 100px;text-align: center;font-size: 0.16rem;">暂时没有相关商品</div>');
			}
			$("img.lazy").lazyload({effect: "fadeIn",threshold:"400"});
			layer.close(layer_index);
			window.page_scrolling = false;
			window.page_hasmore = !result.hasmore;
			window.curpage++;
		}
	});
}