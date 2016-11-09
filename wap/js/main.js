//轮播图片
$(document).ready(function () {
     var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        paginationClickable: true
     });

	 $("#index-search-btn").on('click',function(){
		 if($(this).hasClass('action')){
			     $(this).removeClass('action')
			     $("#index-search-box").animate({'left':'-100%'});
		 }else{
			     $(this).addClass('action')
			     $("#index-search-box").animate({'left':0});
		 } 
	 });

	//搜索
	$('.search-btn').click(function(){
		var cache_key = 'search_key_list';
		var search_list = getCache(cache_key);
		var keyword_val = $('#keyword').val();
		var keyword = encodeURIComponent(keyword_val);
		search_list = search_list ? search_list : [];

		if(search_list.length > 0){
			for (var f1 in search_list) {
				if (search_list[f1].indexOf(keyword_val) == -1) {
					search_list.push(keyword_val);
					setCache(cache_key,search_list);
				}
			}
		}else{
			search_list.push(keyword_val);
			setCache(cache_key,search_list);
		}

		location.href = WapSiteUrl+'/tmpl/product_list.html?keyword='+keyword;
	});

})