$(function() {
    var r = getcookie("key");
    app_check_login(r);

    $.getJSON(ApiUrl + "/index.php?act=member_order&op=order_info", {
        key: r,
        order_id: GetQueryString("order_id")
    },
    function(t) {
        t.datas.order_info.WapSiteUrl = WapSiteUrl;
        $("#order-info-container").html(template.render("order-info-tmpl", t.datas.order_info));
        $(".cancel-order").click(e);
        $(".sure-order").click(o);
        $(".evaluation-order").click(d);
        $(".evaluation-again-order").click(a);
        $(".all_refund_order").click(n);
        $(".goods-refund").click(c);
        $(".goods-return").click(_);
        $(".viewdelivery-order").click(l);
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?act=member_order&op=get_current_deliver",
            data: {
                key: r,
                order_id: GetQueryString("order_id")
            },
            dataType: "json",
            success: function(r) {
                checklogin(r.login);
                var e = r && r.datas;
                if (e.deliver_info) {
                    $("#delivery_content").html(e.deliver_info.context);
                    $("#delivery_time").html(e.deliver_info.time)
                }
            }
        })
    });
});