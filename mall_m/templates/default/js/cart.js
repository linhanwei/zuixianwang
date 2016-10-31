$(function(){
	$(".edit-btn").on('click',function(){
		var shopid = $(this).attr('data-shopid');
		var status = $(this).attr('data-status');
		if(status == 1){
			$("#shop-list-"+shopid).find(".info").hide();
			$("#shop-list-"+shopid).find(".col").show();
			$(this).attr('data-status',0);
		}else{
			$("#shop-list-"+shopid).find(".info").show();
			$("#shop-list-"+shopid).find(".col").hide();
			$(this).attr('data-status',1);
		}
	});

	$("#cart-statement-select-btn").on('click',function(){
		if($(this).hasClass('selected')){
			$(".cart-shop-pro-item").find('.select').removeClass('selected');
			$(".cart-shop-pro-item").find('.select').children('img').attr('src','./templates/default/images/cart/ico-789446.png');
			$(".select-btn").removeClass('selected');
			$(".select-btn").find('img').attr('src','./templates/default/images/cart/ico-789446.png');

			$(this).removeClass('selected');
			$(this).find('span img').attr('src','./templates/default/images/cart/ico-789446.png');
		}else{
			$(".cart-shop-pro-item").find('.select').addClass('selected');
			$(".cart-shop-pro-item").find('.select').children('img').attr('src','./templates/default/images/cart/ico-789447.png');
			$(".select-btn").addClass('selected');
			$(".select-btn").find('img').attr('src','./templates/default/images/cart/ico-789447.png');
			$(this).addClass('selected');
			$(this).find('span img').attr('src','./templates/default/images/cart/ico-789447.png');
		}
		countTotal();
	});

	//去结算
	$('#cart-statement-btn').click(function(){
		var total_money = parseInt($(this).find('#pro-num').text());

		if(total_money == 0){
			layer.alert('请选择商品', {title: '信息提示'});
			return false;
		}
		//购物车ID
		var cartIdArr = [];
		var cartIdEl = $(".cart-shop-pro-item");
		for (var i = 0; i < cartIdEl.length; i++) {
			if($(cartIdEl[i]).find('.select').hasClass('selected')) {
				var cartId = $(cartIdEl[i]).attr("data-proid");
				var cartNum = parseInt($(cartIdEl[i]).attr("data-num"));
				var cartIdNum = cartId + "|" + cartNum;
				cartIdArr.push(cartIdNum);
			}
		}
		var cart_id = cartIdArr.toString();
		window.location.href = WapSiteUrl + "/tmpl/order/buy_step1.html?ifcart=1&cart_id=" + cart_id;
	});
})

function cartMinus(id){
	edit_cart_num(id,'minus');
}

function cartAdd(id){
	edit_cart_num(id,'add');
}

//删除购物车的商品
function cartItemDel(cart_id){
	var　layer_index = layer.confirm('您确定要删除该商品吗?', {
		title:'提示信息',
		btn: ['确定','取消'] //按钮
	}, function(){
		var key = getcookie('key');
		$.ajax({
			url: ApiUrl + "/index.php?act=member_cart&op=cart_del",
			type: "post",
			data: {key: key, cart_id: cart_id},
			dataType: "json",
			success: function (res) {
				if (checklogin(res.login)) {
					if (!res.datas.error && res.datas == "1") {
						$("#pro-item-"+cart_id).remove();
						countTotal();
						layer.close(layer_index);
					} else {
						layer.alert(res.datas.error, {title: '信息提示'});
					}
				}
			}
		});
	}, function(){

	});

}

function cartSelectItem(id){
	var shopid =  $("#pro-item-"+id).parent('.cart-shop-pro-list').attr('data-shopid');
	if(!$("#pro-item-"+id).find(".select").hasClass('selected')){
		$("#pro-item-"+id).find(".select img").attr('src','./templates/default/images/cart/ico-789447.png');
		$("#pro-item-"+id).find(".select").addClass('selected');
		$("#pro-item-"+id).addClass('selected');
	}else{
		$("#pro-item-"+id).find(".select img").attr('src','./templates/default/images/cart/ico-789446.png');
		$("#pro-item-"+id).find(".select").removeClass('selected');
		$("#pro-item-"+id).removeClass('selected');
	}


	var cl = $("#pro-item-"+id).parent(".cart-shop-pro-list").children('.cart-shop-pro-item').length;
	var sl = $("#pro-item-"+id).parent(".cart-shop-pro-list").children('.selected').length;

	countTotal();
	if(cl == sl){

		$("#shop-select-all-"+shopid).find('img').attr('src','./templates/default/images/cart/ico-789447.png');
		$("#shop-select-all-"+shopid).addClass('selected');

	}else{

		$("#shop-select-all-"+shopid).find('img').attr('src','./templates/default/images/cart/ico-789446.png');
		$("#shop-select-all-"+shopid).removeClass('selected');

	}



}

function shopAllSelect(shopid){
	if($("#shop-select-all-"+shopid).hasClass('selected')){
		$("#shop-list-"+shopid).find(".select").removeClass('selected');
		$("#shop-list-"+shopid).find(".select").children("img").attr('src','./templates/default/images/cart/ico-789446.png');
		$("#shop-select-all-"+shopid).removeClass('selected');
		$("#shop-select-all-"+shopid).find('img').attr('src','./templates/default/images/cart/ico-789446.png');
		$("#shop-list-"+shopid).children(".cart-shop-pro-item").removeClass('selected');
	}else{
		$("#shop-list-"+shopid).find(".select").addClass('selected');
		$("#shop-list-"+shopid).find(".select").children("img").attr('src','./templates/default/images/cart/ico-789447.png');
		$("#shop-select-all-"+shopid).addClass('selected');
		$("#shop-select-all-"+shopid).find('img').attr('src','./templates/default/images/cart/ico-789447.png');
		$("#shop-list-"+shopid).children(".cart-shop-pro-item").addClass('selected');
	}

	countTotal();
}

//统计选择的商品总价格
function countTotal(){
	var totalNum   = 0;
	var totalMoney = 0;
	$(".cart-shop-pro-list").each(function(i){
		$(this).eq(i).children('.cart-shop-pro-item').each(function(j) {
			if($(this).find('.select').hasClass('selected')){
				totalNum++;
				totalMoney += parseInt($(this).attr("data-price"))*parseInt($(this).attr("data-num"));
			}
		});
	});
	$("#pro-num").html(totalNum);
	$("#total-show").html(totalMoney);
}

//修改购物车的商品数量
function edit_cart_num(cart_id,type){

	var key = getcookie('key');
	var num = parseInt($("#num-"+cart_id).val());
	var addLimitNum = 999;
	var minusLimitNum = 1;

	if(type == 'add' && num < addLimitNum ){
		num++;
	}else if(type == 'minus' && num > minusLimitNum ){
		num--;
	}else{
		return false;
	}

	//加载进度
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
	});
}
