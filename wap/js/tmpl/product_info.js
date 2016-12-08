$(function() {
    var goods_id = GetQueryString("goods_id");
    $.ajax({
        url: ApiUrl + "/index.php?act=goods&op=wap_goods_body",
        data: {goods_id: goods_id},
        type: "get",
        dataType:'json',
        success: function(result) {
            //$(".fixed-tab-pannel").html(result);
            $(".fixed-tab-pannel").html(result.datas.mobile_body);
        }
    });
});