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
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?act=member_order&op=get_current_deliver",
            data: {
                key: r,
                order_id: GetQueryString("order_id")
            },
            dataType: "json",
            success: function(r) {
                var e = r && r.datas;
                if (e.deliver_info) {
                    $("#delivery_content").html(e.deliver_info.context);
                    $("#delivery_time").html(e.deliver_info.time)
                }else{
                    $("#delivery_content").html('<div class="deliv_empty">暂时没有物流信息!</div>');
                }
            }
        })
    });
});