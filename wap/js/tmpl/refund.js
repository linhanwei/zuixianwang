var order_id, order_goods_id, goods_pay_price;
$(function () {
    var e = getcookie("key");
    app_check_login(e);

    $.getJSON(ApiUrl + "/index.php?act=member_refund&op=refund_form", {
        key: e,
        order_id: GetQueryString("order_id"),
        order_goods_id: GetQueryString("order_goods_id")
    }, function (r) {

        r.datas.WapSiteUrl = WapSiteUrl;
        $("#order-info-container").html(template.render("order-info-tmpl", r.datas));
        order_id = r.datas.order.order_id;
        order_goods_id = r.datas.goods.order_goods_id;
        var a = "";
        for (var o in r.datas.reason_list) {
            a += '<option value="' + o + '">' + r.datas.reason_list[o].reason_info + "</option>"
        }
        $("#refundReason").append(a);
        goods_pay_price = r.datas.goods.goods_pay_price;
        $('input[name="refund_amount"]').val(goods_pay_price);
        $("#returnAble").html("￥" + goods_pay_price);

        $('.input-box .upload').click(function(){
            if(is_app()){
                var index_key = $(this).index();
                var upload_url = ApiUrl + "/index.php?act=member_refund&op=upload_pic";
                $('input[name="complain_pic"]').css('display','none');
                app_interface.picUpload(e,upload_url,index_key);
                return false;
            }
        });
        if(!is_app()) {
            $('input[name="refund_pic"]').ajaxUploadImage({
                url: ApiUrl + "/index.php?act=member_refund&op=upload_pic",
                data: {key: e},
                start: function (e) {
                    e.parent().after('<div class="upload-loading"><i></i></div>');
                    e.parent().siblings(".pic-thumb").remove()
                },
                success: function (e, r) {
                    checklogin(r.login);
                    if (r.datas.error) {
                        e.parent().siblings(".upload-loading").remove();
                        $.sDialog({skin: "red", content: "图片尺寸过大！", okBtn: false, cancelBtn: false});
                        return false
                    }
                    e.parent().after('<div class="pic-thumb"><img src="' + r.datas.pic + '"/></div>');
                    e.parent().siblings(".upload-loading").remove();
                    e.parents("a").next().val(r.datas.file_name)
                }
            });
        }

        $(".btn-l").click(function () {
            var r = $("form").serializeArray();
            var a = {};
            a.key = e;
            a.order_id = order_id;
            a.order_goods_id = order_goods_id;
            a.refund_type = 1;
            for (var o = 0; o < r.length; o++) {
                a[r[o].name] = r[o].value
            }
            if (parseFloat(a.refund_amount) > parseFloat(goods_pay_price) || isNaN(parseFloat(a.refund_amount)) || parseFloat(a.refund_amount) == 0) {
                $.sDialog({skin: "red", content: "退款金额不能为空，或不能超过可退金额", okBtn: false, cancelBtn: false});
                return false
            }
            if (a.buyer_message.length == 0) {
                $.sDialog({skin: "red", content: "请填写退款说明", okBtn: false, cancelBtn: false});
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
                    window.location.href = WapSiteUrl + "/tmpl/member/member_refund.html"
                }
            })
        })
    })
});


//app图片上传完成调用函数
function picAddVal(index_key,data){
    data = JSON.parse(data);
    if(data.error){
        app_toast(data.error);
    }

    var __this = $('.form-box .input-box .upload').eq(parseInt(index_key));
    __this.find('a').append('<div class="pic-thumb"><img src="' + data.pic + '"/></div>');
    __this.find('input[type="hidden"]').val(data.file_name);
}
