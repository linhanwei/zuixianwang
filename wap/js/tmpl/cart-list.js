var t = null;
function load_cart_list(){
    var key = getcookie('key');
    if (key == '' || key == undefined) {
        $('#cart-list-wp').html('<div style="height: 100px;width: 100%;line-height: 100px;text-align: center;font-size: 0.16rem;">登录后可查看购物车商品 <input type="button" value="登录" id="open_login"> </div>');
        if(client == 'wechat'){
            window.location.href = WapSiteUrl + '/tmpl/member/login.html';
        }else{
            bind_login('load_cart_list');
        }
        return;
    }
    loading();
    //初始化页面数据
    $.ajax({
        url: ApiUrl + "/index.php?act=member_cart&op=cart_list",
        type: "post",
        dataType: "json",
        data: {key: key},
        success: function (result) {
            if (checklogin(result.login)) {
                if (!result.datas.error) {
                    var rData = result.datas;
                    rData.WapSiteUrl = WapSiteUrl;

                    var html = template.render('cart-list', rData);
                    $("#cart-list-wp").html(html);

                    loading(1);
                    bind_openwebview();
                    //去结算
                    $('#cart-statement-btn').click(function () {
                        var total_money = parseInt($(this).find('#pro-num').text());

                        if (total_money == 0) {
                            app_alert('请选择商品');
                            return false;
                        }
                        //购物车ID
                        var cartIdArr = [];
                        var cartIdEl = $(".cart-shop-pro-item");
                        for (var i = 0; i < cartIdEl.length; i++) {
                            if ($(cartIdEl[i]).find('.select').hasClass('selected')) {
                                var cartId = $(cartIdEl[i]).attr("data-proid");
                                var cartNum = parseInt($(cartIdEl[i]).attr("data-num"));
                                var cartIdNum = cartId + "|" + cartNum;
                                cartIdArr.push(cartIdNum);
                            }
                        }
                        var cart_id = cartIdArr.toString();
                        var order_url = WapSiteUrl + "/tmpl/order/buy_step1.html?ifcart=1&cart_id=" + cart_id;
                        if(typeof(app_interface) == 'object'){
                            app_interface.openWebView(order_url,1);
                        }else{
                            window.location.href = order_url;
                        }

                    });

                    //新页面
                    $(".edit-btn").on('click', function () {
                        var shopid = $(this).attr('data-shopid');
                        var status = $(this).attr('data-status');
                        if (status == 1) {
                            $("#shop-list-" + shopid).find(".info").hide();
                            $("#shop-list-" + shopid).find(".num-box").hide();
                            $("#shop-list-" + shopid).find(".col").show();
                            $(this).find('a').html('完成');
                            $(this).attr('data-status', 0);
                        } else {
                            $("#shop-list-" + shopid).find(".info").show();
                            $("#shop-list-" + shopid).find(".num-box").show();
                            $("#shop-list-" + shopid).find(".col").hide();
                            $(this).find('a').html('编辑');
                            $(this).attr('data-status', 1);
                        }
                    })

                    $("#cart-statement-select-btn").on('click', function () {

                        if ($(this).hasClass('selected')) {
                            $(".cart-shop-pro-item").find('.select').removeClass('selected');
                            $(".cart-shop-pro-item").find('.select').children('img').attr('src', '../images/cart/ico-789446.png');
                            $(".select-btn").removeClass('selected');
                            $(".select-btn").find('img').attr('src', '../images/cart/ico-789446.png');

                            $(this).removeClass('selected');
                            $(this).find('span img').attr('src', '../images/cart/ico-789446.png');
                        } else {
                            $(".cart-shop-pro-item").find('.select').addClass('selected');
                            $(".cart-shop-pro-item").find('.select').children('img').attr('src', '../images/cart/ico-789447.png');
                            $(".select-btn").addClass('selected');
                            $(".select-btn").find('img').attr('src', '../images/cart/ico-789447.png');
                            $(this).addClass('selected');
                            $(this).find('span img').attr('src', '../images/cart/ico-789447.png');
                        }
                        countTotal();
                    })

                } else {
                    app_toast(result.datas.error);
                }
            }
        }
    });

    //推荐商品
    ajax_data();
}
load_cart_list()
//新页面
function cartMinus(id) {
    edit_cart_num(id, 'minus');
}

function cartAdd(id) {
    edit_cart_num(id, 'add');
}

//删除购物车的商品
function cartItemDel(cart_id) {
    app_confirm('您确定要删除该商品吗?','', '确定',"del_item('"+cart_id+"')",'取消','');
}

function  del_item(cart_id){
    loading();
    var key = getcookie('key');
    $.ajax({
        url: ApiUrl + "/index.php?act=member_cart&op=cart_del",
        type: "post",
        data: {key: key, cart_id: cart_id},
        dataType: "json",
        success: function (res) {
            if (!res.datas.error && res.datas == "1") {
                $("#pro-item-" + cart_id).remove();
                countTotal();
                loading(1);
            } else {
                app_alert(res.datas.error);
            }
        }
    });
}

function cartSelectItem(id) {
    var shopid = $("#pro-item-" + id).parent('.cart-shop-pro-list').attr('data-shopid');
    if (!$("#pro-item-" + id).find(".select").hasClass('selected')) {
        $("#pro-item-" + id).find(".select img").attr('src', '../images/cart/ico-789447.png');
        $("#pro-item-" + id).find(".select").addClass('selected');
        $("#pro-item-" + id).addClass('selected');
    } else {
        $("#pro-item-" + id).find(".select img").attr('src', '../images/cart/ico-789446.png');
        $("#pro-item-" + id).find(".select").removeClass('selected');
        $("#pro-item-" + id).removeClass('selected');
    }


    var cl = $("#pro-item-" + id).parent(".cart-shop-pro-list").children('.cart-shop-pro-item').length;
    var sl = $("#pro-item-" + id).parent(".cart-shop-pro-list").children('.selected').length;

    countTotal();
    if (cl == sl) {

        $("#shop-select-all-" + shopid).find('img').attr('src', '../images/cart/ico-789447.png');
        $("#shop-select-all-" + shopid).addClass('selected');

    } else {

        $("#shop-select-all-" + shopid).find('img').attr('src', '../images/cart/ico-789446.png');
        $("#shop-select-all-" + shopid).removeClass('selected');

    }


}

function shopAllSelect(shopid) {
    if ($("#shop-select-all-" + shopid).hasClass('selected')) {
        $("#shop-list-" + shopid).find(".select").removeClass('selected');
        $("#shop-list-" + shopid).find(".select").children("img").attr('src', '../images/cart/ico-789446.png');
        $("#shop-select-all-" + shopid).removeClass('selected');
        $("#shop-select-all-" + shopid).find('img').attr('src', '../images/cart/ico-789446.png');
        $("#shop-list-" + shopid).children(".cart-shop-pro-item").removeClass('selected');
    } else {
        $("#shop-list-" + shopid).find(".select").addClass('selected');
        $("#shop-list-" + shopid).find(".select").children("img").attr('src', '../images/cart/ico-789447.png');
        $("#shop-select-all-" + shopid).addClass('selected');
        $("#shop-select-all-" + shopid).find('img').attr('src', '../images/cart/ico-789447.png');
        $("#shop-list-" + shopid).children(".cart-shop-pro-item").addClass('selected');
    }

    countTotal();
}

//统计选择的商品总价格
function countTotal() {
    var totalNum = 0;
    var totalMoney = 0;
    $(".cart-shop-pro-list").each(function (i) {
        $(this).eq(i).children('.cart-shop-pro-item').each(function (j) {
            if ($(this).find('.select').hasClass('selected')) {
                totalNum++;
                totalMoney += parseInt($(this).attr("data-price")) * parseInt($(this).attr("data-num"));
            }
        });
    });
    $("#pro-num").html(totalNum);
    $("#total-show").html(totalMoney);
}

//修改购物车的商品数量
function edit_cart_num(cart_id, type) {

    var key = getcookie('key');
    var num = parseInt($("#num-" + cart_id).val());
    var addLimitNum = 999;
    var minusLimitNum = 1;

    if (type == 'add' && num < addLimitNum) {
        num++;
    } else if (type == 'minus' && num > minusLimitNum) {
        num--;
    } else {
        return false;
    }

    //加载进度
    loading();

    $.ajax({
        url: ApiUrl + "/index.php?act=member_cart&op=cart_edit_quantity",
        type: "post",
        data: {key: key, cart_id: cart_id, quantity: num},
        dataType: "json",
        success: function (result) {
            if (!result.datas.error) {
                $("#num-" + cart_id).val(num);
                $("#num-show-" + cart_id).html(num);
                $("#pro-item-" + cart_id).attr("data-num", num);

                countTotal();
            } else {
                app_alert(result.datas.error);
            }

            loading(1);
            return false;
        }
    });
}


function ajax_data(){

    $('#recommend_goods').html(getCache('cart_recom_goods_list'));

    $.ajax({
        url: SiteUrl + "/mall_m/index.php?act=goods&op=recommend_goods_list",
        type: 'get',
        data:{},
        dataType: 'json',
        beforeSend:function(){

        },
        success: function(result) {
            var data = result.datas;
            data.SiteUrl = SiteUrl;
            if(data.goods_list.length > 0){
                var goods_list_html = template.render('goods-list', data);
                setCache('cart_recom_goods_list',goods_list_html);
                $('#recommend_goods').html(goods_list_html);
            }
            bind_openwebview();
        }
    });
}