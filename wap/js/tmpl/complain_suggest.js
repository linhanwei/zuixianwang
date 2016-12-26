
$(function () {
    var e = getcookie("key");
    app_check_login(e);

    $('.input-box .upload').click(function(){
        if(is_app()){
            var index_key = $(this).index();
            var upload_url = ApiUrl+'/index.php?act=complain_suggest&op=upload_pic';
            $('input[name="complain_pic"]').css('display','none');
            app_interface.picUpload(e,upload_url,index_key);
            return false;
        }
    });
    if(!is_app()) {
        $('input[name="complain_pic"]').ajaxUploadImage({
            url: ApiUrl + "/index.php?act=complain_suggest&op=upload_pic",
            data: {key: e},
            start: function (e) {
                e.parent().after('<div class="upload-loading"><i></i></div>');
                e.parent().siblings(".pic-thumb").remove()
            },
            success: function (e, o) {
                if (o.datas.error) {
                    e.parent().siblings(".upload-loading").remove();
                    app_toast('图片尺寸过大!');
                    return false
                }
                console.log(e, 24111);
                e.parent().after('<div class="pic-thumb"><img src="' + o.datas.pic + '"/></div>');
                e.parent().siblings(".upload-loading").remove();
                e.parents("a").next().val(o.datas.file_name)
            }
        });
    }
    $(".btn-l").click(function () {
        var o = $("form").serializeArray();
        var a = {};
        a.key = e;
        for (var r = 0; r < o.length; r++) {
            a[o[r].name] = o[r].value
        }

        if (isNaN(parseFloat(a.phone))) {
            app_toast('联系电话不能为空!');
            return false
        }

        if (a.phone.length != 11) {
            app_toast('手机号码不正确!');
            return false
        }

        if (a.message.length == 0) {
            app_toast('请填写建议内容!');
            return false
        }

        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?act=complain_suggest&op=cs_post",
            data: a,
            dataType: "json",
            async: false,
            success: function (e) {
                if (e.datas.error) {
                    app_toast(e.datas.error);
                    return false
                }
                app_alert('提交成功!');
                return true;
            }
        })
    })
});

//app图片上传完成调用函数
function picAddVal(index_key,data){
    data = JSON.parse(data);
    var __this = $('.form-box .input-box .upload').eq(parseInt(index_key));
    __this.find('a').append('<div class="pic-thumb"><img src="' + data.pic + '"/></div>');
    __this.find('input[type="hidden"]').val(data.file_name);
}
