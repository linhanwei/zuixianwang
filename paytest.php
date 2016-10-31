<?php
$app_id = "e048509e-efa7-4831-bd9c-3ea70615cf59";
$app_secret = "fbbb462a-0dc0-4e78-bcfe-d5b2066b3b0a";
$title = "你的订单标题";
$amount = 1;//支付总价
$out_trade_no = "bc" . time();//订单号，需要保证唯一性
//1.生成sign
$sign = md5($app_id . $title . $amount . $out_trade_no . $app_secret);
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <!--用于移动端H5页面适配，若PC端页面可不引用-->
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <title>demo js button</title>
</head>
<body>
<button id="test">test online</button>


<!--2.添加控制台->APP->设置->秒支付button项获得的script标签-->
<script id='spay-script' src='https://jspay.beecloud.cn/1/pay/jsbutton/returnscripts?appId=e048509e-efa7-4831-bd9c-3ea70615cf59'></script>
<script>
    //3. 需要发起支付时(示例中绑定在一个按钮的click事件中),调用BC.click方法
    document.getElementById("test").onclick = function() {
        asyncPay();
    };
    function bcPay() {
        BC.click({
            "title": "<?php echo $title; ?>",
            "amount": <?php echo $amount; ?>,
            "out_trade_no": "<?php echo $out_trade_no;?>", //唯一订单号
                "sign" : "<?php echo $sign;?>",
        /**
         * optional 为自定义参数对象，目前只支持基本类型的key ＝》 value, 不支持嵌套对象；
         * 回调时如果有optional则会传递给webhook地址，webhook的使用请查阅文档
         */
                "optional": {"test": "willreturn"}
    });

    }
    // 这里不直接调用BC.click的原因是防止用户点击过快，BC的JS还没加载完成就点击了支付按钮。
    // 实际使用过程中，如果用户不可能在页面加载过程中立刻点击支付按钮，就没有必要利用asyncPay的方式，而是可以直接调用BC.click。
    function asyncPay() {
        if (typeof BC == "undefined") {
            if (document.addEventListener) { // 大部分浏览器
                document.addEventListener('beecloud:onready', bcPay, false);
            } else if (document.attachEvent) { // 兼容IE 11之前的版本
                document.attachEvent('beecloud:onready', bcPay);
            }
        } else {
            bcPay();
        }
    }
</script>
</body>
</html>
