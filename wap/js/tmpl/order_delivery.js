$(function() {
    var key = getcookie('key');
    app_check_login(key);

    var order_id = GetQueryString("order_id");

    $.ajax({
        type: 'post',
        url: ApiUrl + "/index.php?act=member_order&op=search_deliver",
        data:{key:key,order_id:order_id},
        dataType:'json',
        success:function(result) {
            var data = result && result.datas;
            if (!data || data.error) {
                data = {};
                data.err = '暂无物流信息';
            }

            var html = template.render('order-delivery-tmpl', data);
            $("#order-delivery").html(html);
        }
    });

});
