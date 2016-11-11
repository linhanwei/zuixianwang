var order_id, order_goods_id, goods_pay_price, goods_num;
$(function () {
    var e = getcookie("key");
    if (!e) {
        window.location.href = WapSiteUrl + "/tmpl/member/login.html"
    }
    $.getJSON(ApiUrl + "/index.php?act=member_refund&op=refund_form", {
        key: e,
        order_id: GetQueryString("order_id"),
        order_goods_id: GetQueryString("order_goods_id")
    }, function (o) {
        o.datas.WapSiteUrl = WapSiteUrl;
        $("#order-info-container").html(template.render("order-info-tmpl", o.datas));
        order_id = o.datas.order.order_id;
        order_goods_id = o.datas.goods.order_goods_id;
        var a = "";
        for (var r in o.datas.reason_list) {
            a += '<option value="' + r + '">' + o.datas.reason_list[r].reason_info + "</option>"
        }
        $("#refundReason").append(a);
        goods_pay_price = o.datas.goods.goods_pay_price;
        $('input[name="refund_amount"]').val(goods_pay_price);
        $("#returnAble").html("￥" + goods_pay_price);
        goods_num = o.datas.goods.goods_num;
        $('input[name="goods_num"]').val(goods_num);
        $("#goodsNum").html("最多" + goods_num + "件");
        $('input[name="refund_pic"]').ajaxUploadImage({
            url: ApiUrl + "/index.php?act=member_refund&op=upload_pic",
            data: {key: e},
            start: function (e) {
                e.parent().after('<div class="upload-loading"><i></i></div>');
                e.parent().siblings(".pic-thumb").remove()
            },
            success: function (e, o) {
                checklogin(o.login);
                if (o.datas.error) {
                    e.parent().siblings(".upload-loading").remove();
                    $.sDialog({skin: "red", content: "图片尺寸过大！", okBtn: false, cancelBtn: false});
                    return false
                }
                e.parent().after('<div class="pic-thumb"><img src="' + o.datas.pic + '"/></div>');
                e.parent().siblings(".upload-loading").remove();
                e.parents("a").next().val(o.datas.file_name)
            }
        });
        $(".btn-l").click(function () {
            var o = $("form").serializeArray();
            var a = {};
            a.key = e;
            a.order_id = order_id;
            a.order_goods_id = order_goods_id;
            a.refund_type = 2;
            for (var r = 0; r < o.length; r++) {
                a[o[r].name] = o[r].value
            }

            if (isNaN(parseFloat(a.refund_amount)) || parseFloat(a.refund_amount) > parseFloat(goods_pay_price.replace(',','')) || parseFloat(a.refund_amount) == 0) {
                $.sDialog({skin: "red", content: "退款金额不能为空，或不能超过可退金额", okBtn: false, cancelBtn: false});
                return false
            }
            if (a.buyer_message.length == 0) {
                $.sDialog({skin: "red", content: "请填写退款说明", okBtn: false, cancelBtn: false});
                return false
            }
            if (isNaN(a.goods_num) || parseInt(a.goods_num) == 0 || parseInt(a.goods_num) > parseInt(goods_num)) {
                $.sDialog({skin: "red", content: "退货数据不能为空，或不能超过可退数量", okBtn: false, cancelBtn: false});
                return false
            }
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?act=member_refund&op=refund_post",
                data: a,
                dataType: "json",
                async: false,
                success: function (e) {
                    checklogin(e.login);
                    if (e.datas.error) {
                        $.sDialog({skin: "red", content: e.datas.error, okBtn: false, cancelBtn: false});
                        return false
                    }
                    window.location.href = WapSiteUrl + "/tmpl/member/member_return.html"
                }
            })
        })
    })
});