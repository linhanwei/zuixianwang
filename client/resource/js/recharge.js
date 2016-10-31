$(function() {
    $('#rechargebtn').click(function () {
        var pdr_amount = $('#pdr_amount').val();
        var payment_code = $('#payment_code').val();

        if (pdr_amount == '' || pdr_amount == 0) {
            alert('请输入充值金额','pdr_amount');
            return false;
        } else {
            switch (payment_code) {
                case 'alipay':
                case 'wxpay':
                    $('#member_key').val(key);
                    $('#recharge_form').attr('action', ClientSiteUrl + '/index.php');
                    setTimeout(function () {
                        $('#recharge_form').submit();
                        layer.confirm('请您在新打开的页面上完成充值。<br/><span style="color: #999 !important;margin-top:6px;font-size:10px;">充值完成后，根据您的情况点击下面按钮。</span>',
                            {
                            btn: ['充值成功','充值失败'],icon: 0, title:'充值' //按钮
                        }, function(){
                            document.location.href = "index.php";
                        }, function(){

                        });

                    }, 100);
                    break;
            }

        }
    });
});