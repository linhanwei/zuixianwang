$(function(){
  
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
})