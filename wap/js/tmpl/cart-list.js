$(function () {
    var key = getcookie('key');
    if (key == '') {
        window.location.href = WapSiteUrl + '/tmpl/member/login.html';
    } else {
        //初始化页面数据
        function initCartList() {
            $.ajax({
                url: ApiUrl + "/index.php?act=member_cart&op=cart_list",
                type: "post",
                dataType: "json",
                data: {key: key},
                success: function (result) {
                    if (checklogin(result.login)) {
                        if (!result.datas.error) {
                            var rData = result.datas;
                            console.log(rData);
                            rData.WapSiteUrl = WapSiteUrl;
                            var html = template.render('cart-list', rData);
                            $("#cart-list-wp").html(html);
                            //删除购物车
                            $(".cart-list-del").click(delCartList);
                            //购买数量，减
                            $(".minus-wp").click(minusBuyNum);
                            //购买数量加
                            $(".add-wp").click(addBuyNum);
                            //去结算
                            $(".goto-settlement").click(goSettlement);
                            $(".buynum").blur(buyNumer);

                            //新页面
                            $(function () {
                                $(".edit-btn").on('click', function () {
                                    var shopid = $(this).attr('data-shopid');
                                    var status = $(this).attr('data-status');
                                    if (status == 1) {
                                        $("#shop-list-" + shopid).find(".info").hide();
                                        $("#shop-list-" + shopid).find(".num-box").hide();
                                        $("#shop-list-" + shopid).find(".col").show();
                                        $(this).attr('data-status', 0);
                                    } else {
                                        $("#shop-list-" + shopid).find(".info").show();
                                        $("#shop-list-" + shopid).find(".num-box").show();
                                        $("#shop-list-" + shopid).find(".col").hide();
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
                            })
                        } else {
                            layer.alert(result.datas.error);
                        }
                    }
                }
            });
        }

        initCartList();
        //删除购物车
        function delCartList() {
            var cart_id = $(this).attr("cart_id");
            $.ajax({
                url: ApiUrl + "/index.php?act=member_cart&op=cart_del",
                type: "post",
                data: {key: key, cart_id: cart_id},
                dataType: "json",
                success: function (res) {
                    if (checklogin(res.login)) {
                        if (!res.datas.error && res.datas == "1") {
                            initCartList();
                        } else {
                            alert(res.datas.error);
                        }
                    }
                }
            });
        }

        //购买数量减
        function minusBuyNum() {
            var self = this;
            editQuantity(self, "minus");
        }

        //购买数量加
        function addBuyNum() {
            var self = this;
            editQuantity(self, "add");
        }

        //购买数量增或减，请求获取新的价格
        function editQuantity(self, type) {
            var sPrents = $(self).parents(".cart-litemw-cnt")
            var cart_id = sPrents.attr("cart_id");
            var numInput = sPrents.find(".buy-num");
            var buynum = parseInt(numInput.val());
            var quantity = 1;
            if (type == "add") {
                quantity = parseInt(buynum + 1);
                // 
            } else {
                if (buynum > 1) {
                    quantity = parseInt(buynum - 1);
                } else {
                    $.sDialog({
                        skin: "red",
                        content: '购买数目必须大于1',
                        okBtn: false,
                        cancelBtn: false
                    });
                    return;
                }
            }
            $.ajax({
                url: ApiUrl + "/index.php?act=member_cart&op=cart_edit_quantity",
                type: "post",
                data: {key: key, cart_id: cart_id, quantity: quantity},
                dataType: "json",
                success: function (res) {

                    if (checklogin(res.login)) {
                        if (!res.datas.error) {
                            numInput.val(quantity);
                            sPrents.find(".goods-total-price").html(res.datas.total_price);
                            var goodsTotal = $(".goods-total-price");
                            var totalPrice = parseFloat("0.00");
                            for (var i = 0; i < goodsTotal.length; i++) {
                                totalPrice += parseFloat($(goodsTotal[i]).html().replace(',',''));
                            }
                            $(".total_price").html("￥" + totalPrice.toFixed(2));
                        } else {
                            $.sDialog({
                                skin: "red",
                                content: res.datas.error,
                                okBtn: false,
                                cancelBtn: false
                            });
                        }
                    }
                }
            });
        }

        //去结算
        function goSettlement() {
            //购物车ID
            var cartIdArr = [];
            var cartIdEl = $(".cart-litemw-cnt");
            for (var i = 0; i < cartIdEl.length; i++) {
                var cartId = $(cartIdEl[i]).attr("cart_id");
                var cartNum = parseInt($(cartIdEl[i]).find(".buy-num").val());
                var cartIdNum = cartId + "|" + cartNum;
                cartIdArr.push(cartIdNum);
            }
            var cart_id = cartIdArr.toString();
            window.location.href = WapSiteUrl + "/tmpl/order/buy_step1.html?ifcart=1&cart_id=" + cart_id;
        }

        function buyNumer() {
            $.sValid();
        }
    }
});


//新页面
function cartMinus(id) {
    var num = parseInt($("#num-" + id).val());
    var limitNum = 1;
    if (num > limitNum) {
        num--;
        $("#num-" + id).val(num);
        $("#num-show-" + id).html(num);
        $("#pro-item-" + id).attr("data-num", num);
    }

    countTotal();
}

function cartAdd(id) {
    var num = parseInt($("#num-" + id).val());
    var limitNum = 999;
    if (num < limitNum) {
        num++;
        $("#num-" + id).val(num);
        $("#num-show-" + id).html(num);
        $("#pro-item-" + id).attr("data-num", num);
    }

    countTotal();
}

function cartItemDel(id) {
    $("#pro-item-" + id).remove();
    countTotal();
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