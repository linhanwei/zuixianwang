$(function(){
	//顶部导航
	$(window).scroll(function(){
		var dhtobj = $(".detail-head");
		var dht = dhtobj.offset().top;
		if(dht > 100){
			dhtobj.addClass('action');
		}else{
			dhtobj.removeClass('action');
		}
	})

	//商品收藏
	$(".goods_collect").click(function (){
		var key = getcookie('key');//登录标记
		if(key==''){
			checklogin(0);
		}else {
			$.ajax({
				url: ApiUrl + "/index.php?act=member_favorites&op=favorites_add",
				type: "post",
				dataType: "json",
				data:{goods_id:goods_id,key:key},
				success: function (result) {
					if (!result.datas.error) {
						layer.alert('收藏成功', {title: '信息提示'});
					} else {
						layer.alert(result.datas.error, {title: '信息提示'});
					}
					return false;
				}
			});
		}
	});

	//加入购物车
	$(".add-to-cart").click(function (){

		var key = getcookie('key');//登录标记
		var  goods_num = parseInt($("#goods_spec_num").val());
		if(key==''){
			checklogin(0);
			return false;
		}else{
			var quantity = parseInt($(".buy-num").val()); //购买数量
			$.ajax({
				url:ApiUrl+"/index.php?act=member_cart&op=cart_add",
				data:{key:key,goods_id:goods_id,quantity:goods_num},
				type:"post",
				success:function (result){
					var rData = $.parseJSON(result);
					if(!rData.datas.error){
						layer.confirm('添加购物车成功', {
							title:'提示信息',
							btn: ['去购物车','再逛逛'] //按钮
						}, function(){
							window.location.href = SiteUrl+'/mall_m/index.php?act=member_cart&op=index&key='+key;
						}, function(){

						});
					}else{
						layer.alert(rData.datas.error, {title: '信息提示'});
					}
				}
			})
		}
	});

	//立即购买
	$('.buy-now').click(function(){
		var key = getcookie('key');//登录标记
		var  goods_num = parseInt($("#goods_spec_num").val());
		if(key==''){
			checklogin(0);
			return false;
		}
		window.location.href = WapSiteUrl+'/tmpl/order/buy_step1.html?goods_id='+goods_id+'&buynum='+goods_num;
	});

	//显示规格
	$("#spec-select").on('click',function(){
		$("#spec-box").show().addClass('show').delay(300).animate('top','20%');
		$(".cover-bg").show().addClass('show').delay(300).animate('top','0');
	});

	//关闭规格
	$(".close").on('click',function(){
		$("#spec-box").hide().removeClass('show').delay(300).animate('top','100%');
		$(".cover-bg").hide().removeClass('show').delay(300).animate('top','100%');
	});

	//选择规格确定
	$(".ok-btn").on('click',function(){
		$("#spec-box").hide().removeClass('show').delay(300).animate('top','100%');
		$(".cover-bg").hide().removeClass('show').delay(300).animate('top','100%');
	});

	//点击商品规格，获取新的商品
	$(".sku-control .spec_items_value").on('click',function(){
		$(this).addClass("checked").siblings().removeClass("checked");
		//拼接属性
		var curEle = $(".sku-control").find("label.checked");
		var curSpec = [];
		var goods_spec_value_list_html = '';
		$.each(curEle,function (i,v){
			curSpec.push($(v).attr("data-value"));
			goods_spec_value_list_html += '<span>'+$(v).html()+'</span>';
		});
		var spec_string = curSpec.sort().join("|");
		//获取商品ID
		var spec_goods_id = goods_spec_value[spec_string];
		//console.log(spec_string,spec_goods_id,goods_spec_value);

		$.ajax({
			url: ApiUrl + "/index.php?act=goods&op=ajax_detail",
			type: "get",
			dataType: "json",
			data:{goods_id:spec_goods_id},
			success: function (result) {
				if (!result.datas.error) {
					var goods_info = result.datas;
					$('#goods_spec_img').attr('src',goods_info.goods_image_url);
					$('#goods_spec_price').html('￥'+goods_info.goods_promotion_price);
					$('#stock').html(goods_info.goods_storage);
					$('#goods_spec_value_list').html(goods_spec_value_list_html);
					goods_id = goods_info.goods_id;
				} else {
					layer.alert(result.datas.error, {title: '信息提示'});
				}

				return false;
			}
		});
	});

	//减商品数量
	$(".mui-number .dec").on('click',function(){
		edit_num('minus');
	});

	//增加商品数量
	$(".mui-number .inc").on('click',function(){
		edit_num('add');
	});

});

//修改商品数量
function edit_num(type){

	var key = getcookie('key');
	var num = parseInt($("#goods_spec_num").val());
	var addLimitNum = 999;
	var minusLimitNum = 1;

	if(type == 'add' && num < addLimitNum ){
		num++;
	}else if(type == 'minus' && num > minusLimitNum ){
		num--;
	}else{
		return false;
	}

	$("#goods_spec_num").val(num);
	/*//加载进度
	var layer_index = layer.load(0, {shade:false});

	$.ajax({
		url: ApiUrl + "/index.php?act=member_cart&op=cart_edit_quantity",
		type: "post",
		data: {key: key, cart_id: cart_id, quantity: num},
		dataType: "json",
		success: function (result) {
			if (!result.datas.error) {
				$("#num-"+cart_id).val(num);
				$("#num-show-"+cart_id).html(num);
				$("#pro-item-"+cart_id).attr("data-num",num);

				countTotal();
			} else {
				layer.alert(result.datas.error, {title: '信息提示'});
			}

			layer.close(layer_index);
			return false;
		}
	});*/
}
