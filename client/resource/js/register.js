var pubEndflag = false; //倒计时结束
var countTokenNum = 0; //记录验证码错误次数
var bindFlag = true; //记录绑定
var timeoutFlag; //定时器标志

$(function() {

    var rootPath = "../m/";

    //  验证码路径
    var getVerNumUrl = rootPath + 'index.php?act=login';

    // 注册路径
    var registerUrl = rootPath + 'index.php?act=login';

    var inviterUrl  = rootPath + 'index.php?act=login';
    //  灰度发布
    var headers = {
        requestSource: 'outsideHtml'
    }; //请求来源

    //  一些公共变量
    var $goBtn = $('#btn_register');
    var $getVerNum = $('#btn_getValidateCode');
    var $phoneNum = $('#phoneNumId');
    var $verifNum = $('#verifNumId');
    var $loading = $('#loadingId');
    var cache_key = '';

    // 恢复验证码按钮初始状态
    function btnResetclsAndAct() {
        $getVerNum[0].innerHTML = '获取验证码';
        $getVerNum.removeAttr('disabled');
        $getVerNum.addClass('btn-warning');
        bindVerAction(); // 再次监听
    }

    //  ***********请求验证码**************
    function getVerAction(getVerNumFun) {
        var tempCheckFlag = false;
        var phoneStatus = InsureValidate.validateMobile($phoneNum.val());
        $.ajax({
            type: 'post',
            url: getVerNumUrl,
            headers: headers,
            dataType:'json',
            beforeSend: function() {
                if (phoneStatus !== true) { //判断手机号格式
                    layer.alert(phoneStatus, {icon: 5}, function(index){
                        $phoneNum.focus();
                        layer.close(index);
                    });
                    return false;
                } else {
                    $getVerNum.unbind('click', getVerNumFun); //    解除绑定
                    $getVerNum.attr('disabled','disabled');
                    $getVerNum.removeClass('btn-warning');
                    countDownSixty($getVerNum[0], 60, function() {
                        btnResetclsAndAct();
                    });
                    tempCheckFlag = true;
                    return true;
                }
            },
            data: {
                mobile_phone: $phoneNum.val(),
                client_type: 'pc',
                op: 'check_phone'
            },
            success: function(result) {
                var tResult = result.datas;
                if (tResult.code == 'success' && tResult.msg) {
                    cache_key = tResult.msg;
                    bindAddCarBtn(); //开始绑定下载按钮
                    countDown(); //开始倒计时 验证码两分钟有效
                    countTokenNum = 0;
                } else if (tResult.code == 'exist') {
                    layer.alert('该手机号已经被注册', {icon: 5}, function(index){
                        pubEndflag = true;
                        btnResetclsAndAct();
                        $phoneNum.focus();
                        layer.close(index);
                    });
                } else {
                    layer.alert('验证码获取失败', {icon: 5}, function(index){
                        pubEndflag = true;
                        btnResetclsAndAct();
                        layer.close(index);
                    });
                }
            },
            error: function() {
                if (tempCheckFlag) {
                    layer.alert('验证码获取失败', {icon: 5}, function(index){
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

    function bindRegAction() {
        doRegAction(function() {
            var index = layer.load(1, {
                shade: [0.1,'#fff'] //0.1透明度的白色背景
            });
        }, function() {

        });
    }

    function doRegAction(ajaxBefore, ajaxAfter) {
        var flagVal = false;
        $.ajax({
            type: 'post',
            url: registerUrl,
            headers: headers,
            dataType:'json',
            beforeSend: function() {
                if ($('#phoneNumId').val() == '') {
                    layer.alert('请输入手机号码', {icon:5}, function(index){
                        $phoneNum.focus();
                        layer.close(index);
                    });
                    return false;
                }  else {
                    $('#formId').attr('disabled','disabled');
                    ajaxBefore();
                    flagVal = true;
                    return true;
                }
            },
            data: {
                mobile_phone: $phoneNum.val(),
                for_platform : 'pc',
                invite_code: $('#txt_invitationCode').val(),
                identifying_code:$('#verifNumId').val(),
                cache_key:cache_key,
                client:'pc',
                op: 'register'
            },
            success: function(dResult) {
                var tResult = dResult;
                if (tResult.datas.error) {
                    layer.alert(tResult.datas.error, {icon:5}, function(index){
                        $('#formId').removeAttr('disabled');
                        layer.close(index);
                    });
                }else{
                    if(typeof(tResult.datas.key) != 'undefine'){
                        layer.alert('注册成功', {icon:1}, function(index){
                            parent.document.location.href = 'index.php?act=login';
                            layer.close(index);
                        });
                    }else{
                        layer.alert('注册失败', {icon:5}, function(index){
                            $('#formId').removeAttr('disabled');
                            layer.close(index);
                        });
                    }

                }
            },
            complete: function() {
                ajaxAfter(); //loading去除
            },
            error: function() {
                if (flagVal) {
                    layer.alert('网络异常', {icon:5});
                }
            }
        });
    }

    //  **********点击下载************
    function downloadAction(ajaxBefore, ajaxAfter) {
        var phoneStatus = InsureValidate.validateMobile($phoneNum.val());
        if (phoneStatus !== true) {
            layer.alert(phoneStatus, {icon: 5}, function(index){
                $phoneNum.focus();
                layer.close(index);
            });
            return false;
        } else if ($.trim($verifNum.val()) == '') {
            layer.alert('请输入正确的验证码1', {icon: 5}, function(index){
                $verifNum.focus();
                layer.close(index);
            });
            return false;
        }else {
            bindRegAction();
            return true;
        }
    }

    //  点击下载按钮
    var carBtnFlag;

    function bindAddCarBtn() {
        $goBtn.removeAttr('disabled');
        $goBtn.bind('click', addClickFun = function() {
            //$goBtn.unbind('click', addClickFun);
            downloadAction(function() {
                var index = layer.load(1, {
                    shade: [0.1,'#fff'] //0.1透明度的白色背景
                });
            }, function() {
                if (bindFlag) {
                    if (carBtnFlag) {
                        clearTimeout(carBtnFlag);
                    }
                    carBtnFlag = setTimeout(bindAddCarBtn, 1500);
                } else {
                    $verifNum.val('');
                    $goBtn.attr('disabled','disabled');
                    bindFlag = true
                    clearTimeout(timeoutFlag); //解除验证码定时器绑定
                    countTokenNum = 0;
                }

            });
        });

    }

    //倒计时120秒
    function countDown() {
        if (timeoutFlag) {
            clearTimeout(timeoutFlag);
        }
        timeoutFlag = setTimeout(function() {
            countTokenNum = 4;
        }, 120000);
    }

})
