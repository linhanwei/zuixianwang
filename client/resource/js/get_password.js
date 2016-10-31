
var pubEndflag = false; //倒计时结束

$(function(){
    var pass_type = get_query_string('pass_type');
    //  验证码路径
    var getVerNumUrl = ApiUrl + '/index.php?act=login&op=send_code';
    var $phoneNum = $('#mobile_phone');
    var $getVerNum = $('#btn_getValidateCode_findPass');
    var cache_key = '';

    if(pass_type=='getpaywd'){
        $($("input[name^='password_type']")[1]).attr("checked","checked");
    }

    // 恢复验证码按钮初始状态
    function btnResetclsAndAct() {
        $getVerNum[0].innerHTML = '获取验证码';
        $getVerNum.removeAttr('disabled');
        $getVerNum.addClass('btn-warning');
        bindVerAction(); // 再次监听
    }


    function getVerAction(getVerNumFun) {

        var tempCheckFlag = false;
        var phoneStatus = InsureValidate.validateMobile($phoneNum.val());
        $.ajax({
            type: 'post',
            url: getVerNumUrl,
            dataType: 'json',
            beforeSend: function () {
                if (phoneStatus !== true) { //判断手机号格式
                    layer.alert(phoneStatus, {icon: 5}, function (index) {
                        $phoneNum.focus();
                        layer.close(index);
                    });
                    return false;
                } else {
                    $getVerNum.unbind('click', getVerNumFun); //    解除绑定
                    $getVerNum.attr('disabled', 'disabled');
                    $getVerNum.removeClass('btn-warning');
                    countDownSixty($getVerNum[0], 60, function () {
                        btnResetclsAndAct();
                    });
                    tempCheckFlag = true;
                    return true;
                }
            },
            data: {
                mobile_phone: $phoneNum.val(),
                client_type: 'pc',
                op: 'send_code'
            },
            success: function (result) {
                var tResult = result.datas;
                if(tResult.error){
                    layer.alert(tResult.error, {icon: 5}, function (index) {
                        pubEndflag = true;
                        btnResetclsAndAct();
                        layer.close(index);
                    });
                }else if (tResult.cache_key) {
                    cache_key = tResult.cache_key;
                    $('#btn_findPass').removeAttr('disabled');
                }  else {
                    layer.alert('验证码获取失败', {icon: 5}, function (index) {
                        pubEndflag = true;
                        btnResetclsAndAct();
                        layer.close(index);
                    });
                }
            },
            error: function () {
                if (tempCheckFlag) {
                    layer.alert('验证码获取失败', {icon: 5}, function (index) {
                        pubEndflag = true;
                        btnResetclsAndAct();
                        layer.close(index);
                    });
                }
            }
        })
    }

    //  绑定获得验证码按钮
    function bindVerAction() {
        $getVerNum.bind('click', function getVerNumFun() {
            getVerAction(getVerNumFun);
        });
    }
    bindVerAction();

    $('#btn_findPass').click(function(){
        var phoneStatus = InsureValidate.validateMobile($phoneNum.val());
        var phoneStatus = InsureValidate.validateMobile($phoneNum.val());
        $.ajax({
            type: 'post',
            url: ApiUrl + '/index.php?act=login',
            dataType: 'json',
            beforeSend: function () {
                if (phoneStatus !== true) { //判断手机号格式
                    layer.alert(phoneStatus, {icon: 5}, function (index) {
                        $phoneNum.focus();
                        layer.close(index);
                    });
                    return false;
                }

                if($('#identifying_code').val()==''){
                    layer.alert('请输入手机验证码', {icon: 5}, function (index) {
                        $('#identifying_code').focus();
                        layer.close(index);
                    });
                    return false;
                }
            },
            data: {
                mobile_phone: $phoneNum.val(),
                client_type: 'pc',
                cache_key : cache_key,
                identifying_code : $('#identifying_code').val(),
                op: $('input:radio[name=password_type]:checked').val()
            },
            success: function (result) {
                var tResult = result.datas;
                if(tResult.error){
                    layer.alert(tResult.error, {icon: 5}, function (index) {
                        pubEndflag = true;
                        btnResetclsAndAct();
                        layer.close(index);
                    });
                }else{
                    layer.alert('密码已发送至绑定手机号码', {icon: 6});
                }
            },
            error: function () {
                if (tempCheckFlag) {
                    layer.alert('取回密码失败', {icon: 5});
                }
            }
        })
    });
});