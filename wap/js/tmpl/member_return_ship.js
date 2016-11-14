$(function () {
    var e = getcookie("key");
    var r = GetQueryString("refund_id");
    $.getJSON(ApiUrl + "/index.php?act=member_return&op=ship_form", {key: e, return_id: r}, function (a) {
        checklogin(a.login);
        $("#delayDay").html(a.datas.return_delay);
        $("#confirmDay").html(a.datas.return_confirm);
        for (var n = 0; n < a.datas.express_list.length; n++) {
            $("#express").append('<option value="' + a.datas.express_list[n].express_id + '">' + a.datas.express_list[n].express_name + "</option>")
        }
        $(".btn-l").click(function () {
            var a = $("form").serializeArray();
            var n = {};
            n.key = e;
            n.return_id = r;
            for (var t = 0; t < a.length; t++) {
                n[a[t].name] = a[t].value
            }
            if (n.invoice_no == "") {
                $.sDialog({skin: "red", content: "请填写快递单号", okBtn: false, cancelBtn: false});
                return false
            }
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?act=member_return&op=ship_post",
                data: n,
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