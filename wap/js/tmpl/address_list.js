$(function(){
		bind_openwebview();
		var key = getcookie('key');
		app_check_login(key);
		//初始化列表
		function initPage(){
			$.ajax({
				type:'post',
				url:ApiUrl+"/index.php?act=member_address&op=address_list",	
				data:{key:key},
				dataType:'json',
				success:function(result){
					if(result.datas.address_list==null){
						return false;
					}
					var data = result.datas;
					var html = template.render('saddress_list', data);
					$("#address_list").empty();
					$("#address_list").append(html);
					//点击删除地址
					$('.deladdress').click(delAddress);
				}
			});
		}
		initPage();
		//点击删除地址
		function delAddress(){
			var address_id = $(this).attr('address_id');
			$.ajax({
				type:'post',
				url:ApiUrl+"/index.php?act=member_address&op=address_del",
				data:{address_id:address_id,key:key},
				dataType:'json',
				success:function(result){
					if(result){
						initPage();
					}
				}
			});
		}

});