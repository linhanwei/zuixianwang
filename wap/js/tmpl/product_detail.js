var key = getcookie('key');//登录标记
var goods_id = GetQueryString("goods_id");
$(function () {

    var unixTimeToDateString = function (ts, ex) {
        ts = parseFloat(ts) || 0;
        if (ts < 1) {
            return '';
        }
        var d = new Date();
        d.setTime(ts * 1e3);
        var s = '' + d.getFullYear() + '-' + (1 + d.getMonth()) + '-' + d.getDate();
        if (ex) {
            s += ' ' + d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds();
        }
        return s;
    };

    var buyLimitation = function (a, b) {
        a = parseInt(a) || 0;
        b = parseInt(b) || 0;
        var r = 0;
        if (a > 0) {
            r = a;
        }
        if (b > 0 && r > 0 && b < r) {
            r = b;
        }
        return r;
    };

    template.helper('isEmpty', function (o) {
        for (var i in o) {
            return false;
        }
        return true;
    });

    // 图片轮播
    function picSwipe() {
        new Swiper('.swiper-container', {
            pagination: '.swiper-pagination',
            paginationClickable: true
        });
    }

    var goods_cache_key = 'goods_'+goods_id;
    //渲染页面
    var goods_html = getCache(goods_cache_key);
    if(goods_html)  $("#product_detail_wp").html(goods_html);

    $.ajax({
        url: ApiUrl + "/index.php?act=goods&op=goods_detail",
        type: "get",
        data: {goods_id: goods_id,key:key},
        dataType: "json",
        success: function (result) {
            var data = result.datas;
            if (!data.error) {

                data.favorites_img_url = '../images/ico/nosc.png';
                if(result.datas.is_favorites == 1){
                    data.favorites_img_url = '../images/ico/shoucang.png';
                }
                //商品图片格式化数据
                if (data.goods_image) {
                    var goods_image = data.goods_image.split(",");
                    data.goods_image = goods_image;
                } else {
                    data.goods_image = [];
                }
                //商品规格格式化数据
                if (data.goods_info.spec_name) {
                    var goods_map_spec = $.map(data.goods_info.spec_name, function (v, i) {
                        var goods_specs = {};
                        goods_specs["goods_spec_id"] = i;
                        goods_specs['goods_spec_name'] = v;
                        goods_specs['goods_spec_value'] = [];
                        if (data.goods_info.spec_value) {
                            $.map(data.goods_info.spec_value, function (vv, vi) {
                                if (i == vi) {
                                    goods_specs['goods_spec_value'] = $.map(vv, function (vvv, vvi) {
                                        var specs_value = {};
                                        specs_value["specs_value_id"] = vvi;
                                        specs_value["specs_value_name"] = vvv;
                                        return specs_value;
                                    });
                                }
                            });
                            return goods_specs;
                        } else {
                            data.goods_info.spec_value = [];
                        }
                    });
                    data.goods_map_spec = goods_map_spec;
                } else {
                    data.goods_map_spec = [];
                }

                // 虚拟商品限购时间和数量
                if (data.goods_info.is_virtual == '1') {
                    data.goods_info.virtual_indate_str = unixTimeToDateString(data.goods_info.virtual_indate, true);
                    data.goods_info.buyLimitation = buyLimitation(data.goods_info.virtual_limit, data.goods_info.upper_limit);
                }

                // 预售发货时间
                if (data.goods_info.is_presell == '1') {
                    data.goods_info.presell_deliverdate_str = unixTimeToDateString(data.goods_info.presell_deliverdate);
                }

                //渲染模板
                data.WapSiteUrl = WapSiteUrl;
                var html = template.render('product_detail', data);
                if(goods_html != html){
                    setCache(goods_cache_key,html);
                    if(goods_html==''){
                        $("#product_detail_wp").html(html);
                    }
                }

                // @add 手机端详情
                if (data.goods_info.mobile_body) {
                    $('#mobile_body').html(data.goods_info.mobile_body);
                }

                //图片轮播
                picSwipe();
                //商品描述
                $(".pddcp-arrow").click(function () {
                    $(this).parents(".pddcp-one-wp").toggleClass("current");
                });
                //规格属性
                var myData = {};
                myData["spec_list"] = data.spec_list;
                $(".spec_items_value").click(function () {
                    var self = this;
                    arrowClick(self, myData);
                });
                //购买数量，减
                $(".minus-wp").click(function () {
                    var buynum = $(".buy-num").val();
                    if (buynum > 1) {
                        $(".buy-num").val(parseInt(buynum - 1));
                    }
                });
                //购买数量加
                $(".add-wp").click(function () {
                    var buynum = parseInt($(".buy-num").val());
                    if (buynum < data.goods_info.goods_storage) {
                        $(".buy-num").val(parseInt(buynum + 1));
                    }
                });
                // 一个F码限制只能购买一件商品 所以限制数量为1
                if (data.goods_info.is_fcode == '1') {
                    $('.minus-wp').hide();
                    $('.add-wp').hide();
                    $(".buy-num").attr('readOnly', true);
                }
                $(".add-cart").click(function () {
                    $(".cover-bg").show(10);
                    $("#spec-box").show(10);
                    $("#spec-box").css({'transform': 'translateY(0)'});
                });
                $("#spec-box .close").click(function () {
                    $(".cover-bg").hide();
                    $("#spec-box").hide();
                });

                //优惠卷
                $(".mb_coupon").click(function () {
                    app_toast('敬请期待');
                });
                //收藏
                $(".pd-collect").click(function () {
                    key = getcookie('key');
                    app_check_login(key);
                    if(key){
                        $.ajax({
                            url: ApiUrl + "/index.php?act=member_favorites&op=favorites_add",
                            type: "post",
                            dataType: "json",
                            data: {goods_id: goods_id, key: key},
                            success: function (fData) {
                                if (!fData.datas.error) {
                                    if(fData.datas == 1){
                                        $('.pd-collect img').attr('src','../images/ico/shoucang.png');
                                    }else{
                                        $('.pd-collect img').attr('src','../images/ico/nosc.png');
                                    }
                                } else {
                                    app_toast(fData.datas.error);
                                }
                            }
                        });
                    }

                });
                //加入购物车
                $(".add-to-cart").click(function () {
                    key = getcookie('key');
                    app_check_login(key);
                    if(key) {
                        var quantity = parseInt($(".buy-num").val());
                        $.ajax({
                            url: ApiUrl + "/index.php?act=member_cart&op=cart_add",
                            data: {key: key, goods_id: goods_id, quantity: quantity},
                            type: "post",
                            success: function (result) {
                                var rData = $.parseJSON(result);
                                if (!rData.datas.error) {
                                    app_toast('加入购物车成功');
                                    $(".cover-bg").hide();
                                    $("#spec-box").hide();
                                } else {
                                    app_toast(rData.datas.error);
                                }
                            }
                        })
                    }
                });

                //立即购买
                if (data.goods_info.is_virtual == '1') {
                    $(".buy-now").click(function () {
                        key = getcookie('key');
                        app_check_login(key);
                        if(key) {
                            var buynum = parseInt($('.buy-num').val()) || 0;

                            if (buynum < 1) {
                                app_toast('参数错误！');
                                return;
                            }
                            //购买大于1时 提示库存不足
                            if (parseInt(buynum) > data.goods_info.goods_storage) {
                                app_toast('库存不足！');
                                return;
                            }

                            // 虚拟商品限购数量
                            if (data.goods_info.buyLimitation > 0 && buynum > data.goods_info.buyLimitation) {
                                app_toast('超过限购数量！');
                                return;
                            }

                            var json = {};
                            json.key = key;
                            json.cart_id = goods_id;
                            json.quantity = buynum;
                            $.ajax({
                                type: 'post',
                                url: ApiUrl + '/index.php?act=member_vr_buy&op=buy_step1',
                                data: json,
                                dataType: 'json',
                                success: function (result) {
                                    if (result.datas.error) {
                                        app_toast(result.datas.error);
                                    } else {
                                        if (typeof(app_interface) == 'object') {
                                            app_interface.openWebView(WapSiteUrl + '/tmpl/order/vr_buy_step1.html?goods_id=' + goods_id + '&quantity=' + buynum, 1);
                                        } else {
                                            window.location.href = WapSiteUrl + '/tmpl/order/vr_buy_step1.html?goods_id=' + goods_id + '&quantity=' + buynum;
                                        }
                                    }
                                }
                            });
                        }
                    });
                } else {
                    $(".buy-now").click(function () {
                        key = getcookie('key');
                        app_check_login(key);
                        if(key) {
                            var buynum = $('.buy-num').val();

                            if (buynum < 1) {
                                app_toast('参数错误！');
                                return;
                            }
                            //修复
                            if (parseInt(buynum) > data.goods_info.goods_storage) {
                                app_toast('库存不足！');
                                return;
                            }

                            var json = {};
                            json.key = key;
                            json.cart_id = goods_id + '|' + buynum;
                            $.ajax({
                                type: 'post',
                                url: ApiUrl + '/index.php?act=member_buy&op=buy_step1',
                                data: json,
                                dataType: 'json',
                                success: function (result) {
                                    if (result.datas.error) {
                                        app_toast(result.datas.error);
                                    } else {

                                        if (typeof(app_interface) == 'object') {
                                            app_interface.openWebView(WapSiteUrl + '/tmpl/order/buy_step1.html?goods_id=' + goods_id + '&buynum=' + buynum, 1);
                                        } else {
                                            location.href = WapSiteUrl + '/tmpl/order/buy_step1.html?goods_id=' + goods_id + '&buynum=' + buynum;
                                        }
                                        return false;

                                    }
                                }
                            });
                        }

                    });
                }

                //浏览评论图片
                $('.evaluate_info .info_img').click(function(){

                    var eval_info = {};
                    var img_url_list = {};
                    var eval_cont = '';
                    var img_length = $(this).find('img').length;
                    eval_info.img_list = img_url_list;
                    eval_info.eval_cont = eval_cont;

                    if(img_length > 0){
                        eval_cont = $(this).siblings('.info_cont').text();
                        for(var i=0;i<img_length;i++){
                            img_url_list[i] = $(this).children('a').eq(i).find('img').attr('src');

                        }

                        eval_info.img_list = img_url_list;
                        eval_info.eval_cont = eval_cont;
                    }

                    if(is_app()){
                        app_interface.showEvaluateInfo(eval_info);
                    }
                    console.log(eval_info);
                });
            } else {
                app_toast(data.error);
            }

            //验证购买数量是不是数字
            $("#buynum").blur(buyNumer);
            AddView();
        }

});
    //点击商品规格，获取新的商品
    function arrowClick(self, myData) {
        $(self).addClass("checked").siblings().removeClass("checked");
        //拼接属性
        var curEle = $(".sku-control").find("label.checked");
        var curSpec = [];
        var goods_spec_value_list_html = '';
        $.each(curEle, function (i, v) {
            curSpec.push($(v).attr("specs_value_id"));
            goods_spec_value_list_html += '<span>'+$(v).html()+'</span>';
        });
        var spec_string = curSpec.sort().join("|");
        //获取商品ID
        var spec_goods_id = myData.spec_list[spec_string];

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
                    app_alert(result.datas.error);
                }

                return false;
            }
        });
        //window.location.href = "product_detail.html?goods_id=" + spec_goods_id;
    }

    function AddView() {//增加浏览记录
        var goods_info = getcookie('goods');
        var goods_id = GetQueryString('goods_id');
        if (goods_id < 1) {
            return false;
        }

        if (goods_info == '') {
            goods_info += goods_id;
        } else {

            var goodsarr = goods_info.split('@');
            if (contains(goodsarr, goods_id)) {
                return false;
            }
            if (goodsarr.length < 5) {
                goods_info += '@' + goods_id;
            } else {
                goodsarr.splice(0, 1);
                goodsarr.push(goods_id);
                goods_info = goodsarr.join('@');
            }
        }

        addcookie('goods', goods_info);
        return false;
    }

    function contains(arr, str) {//检测goods_id是否存入
        var i = arr.length;
        while (i--) {
            if (arr[i] === str) {
                return true;
            }
        }
        return false;
    }

    //检测商品数目是否为正整数
    function buyNumer() {
        $.sValid();
    }
});