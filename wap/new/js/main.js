//轮播图片
$(document).ready(function () {
     var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        paginationClickable: true
     });
	 
	 $(".tejia-box").width( (($(window).width()-30)/3 + 10) *$(".tejia-box").find(".item").length  );
	 $(".tejia-box").find(".item").width(($(window).width()-30)/3);
	 
	 $("#index-tj-arrow-left").hide();
	 $("#index-tj-arrow-left").on('click',function(){
		 var tjl = $(".tejia-box").offset().left;
		 var il  = $(".tejia-box").find(".item").length;
		 var iw  = $(".tejia-box").find(".item").width();
		 
		 if(tjl < 0 )
		    $(".tejia-box").animate({marginLeft:(iw+10+tjl)+'px'});
			
		 if(tjl > -1*(iw+10)){
		     $("#index-tj-arrow-left").hide(); 
		 }
		 
		 if(tjl > -1*(iw+10)*(il-1)){
			 $("#index-tj-arrow-right").show(); 
		 }
	 })
	 
	 $("#index-tj-arrow-right").on('click',function(){
		 var tjl = $(".tejia-box").offset().left;
		 var il  = $(".tejia-box").find(".item").length;
		 var iw  = $(".tejia-box").find(".item").width();
		 if(tjl > -1*(iw+10)*(il-3))
		     $(".tejia-box").animate({marginLeft:(-1*(iw+10)+tjl)+'px'});
		 
		 if(tjl == 0){
		     $("#index-tj-arrow-left").show(); 
		 }
		 
		 if(tjl == -1*(iw+10)*(il-4)){
			 $("#index-tj-arrow-right").hide(); 
		 }
	 })
	 
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
		var keyword = encodeURIComponent($('#keyword').val());
		location.href = WapSiteUrl+'/tmpl/product_list.html?keyword='+keyword;
	});

})