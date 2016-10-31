$(function(){
   var swiper = new Swiper('.swiper-container', {
	//pagination: '.swiper-pagination',
	nextButton: '.swiper-button-next',
	prevButton: '.swiper-button-prev',
	paginationClickable: true,
	spaceBetween: 30,
	centeredSlides: true,
	autoplay: 2500,
	autoplayDisableOnInteraction: false
   });	
   
   //图片延迟加载
   $("img.lazy").lazyload({effect: "fadeIn",threshold:"400"});
   
   $(".tab-nav").find('li').click(function(){
	  if($(this).hasClass('action')){
		  return false;
	  }else{
		  $(".tab-nav li").removeClass('action');
		  $(".detail-content-box .content-item").removeClass('action');
		  var index = $(this).index();
		  $(".tab-nav li").eq(index).addClass("action");
		  $(".detail-content-box .content-item").eq(index).addClass("action");
	  }  
   })

   $(".chanle-list .more").click(function(){ 
	   var cb = $(".chanle-list .cate-child-box");
	   var cv = $(".chanle-list .cate-child-v");
	   var w = $(".chanle-list .cate-child-box").width();
	   var h = $(".chanle-list .cate-child-box").height();
	   var cw = $(".chanle-list .cate-child-v").width();
	   var ch = $(".chanle-list .cate-child-v").height();
	   var index = parseInt(cb.attr('data-index'));	
	   var cl    = Math.ceil(ch/h);
	   if(index == cl){
	       cv.animate({'margin-top':0});
		   cb.attr('data-index',1);
	   }else{
		   cv.animate({'margin-top':-h*index});
		   cb.attr('data-index',index+1);
	   }  
   })
   
   //返回顶部按钮
   $("#go-top").click(function(){

          $('body,html').animate({scrollTop:0},500);
              return false;
   });

	//搜索
	$('.search-btn').click(function(){
		var keyword = encodeURIComponent($('#keyword').val());
		location.href = SiteUrl+'/mall_m/index.php?act=goods&op=list&keyword='+keyword;
	});

	//修改a链接增加key值
	add_key();
});

//修改a链接增加key值
function add_key(){
	$('a').each(function(k,v){
		var key = getcookie('key'),
			a_href = $(v).attr('href'),
			new_href = '';

		if(a_href && a_href.indexOf("javascript") < 0){
			var key_index = a_href.indexOf("?");
			if(key_index >= 0){
				if(a_href.indexOf("=") >= 0){
					new_href = a_href+'&key='+key;
				}else{
					new_href = a_href+'key='+key;
				}
			}else{
				new_href = a_href+'?key='+key;
			}

			$(v).attr('href',new_href);
			//console.log(key,key_index,k,a_href,111);
		}
	});
}

