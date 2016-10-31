<?php
/**
 * @param $mobile
 * @param $data
 * @param int $templateId
 * @return array
 */
function send_sms($mobile,$data,$templateId = 0){
    $serverIP='http://www.106008.com/WebAPI/SmsAPI.asmx/SendSmsExt';

    switch($templateId){
        case 80280:
            $content = "【车族宝】尊敬的车族宝会员,手机注册验证码是：" . $data[0] . "，请完成验证。如非本人操作，请忽略本条短信。";
            break;
        case 80283:
            $content = "【车族宝】尊敬的车族宝会员,新登录密码是：" . $data[1] . "，请修改。如非本人操作，请忽略本条短信。";
            break;
        case 80284:
            $content = "【车族宝】尊敬的车族宝会员,新支付密码是：" . $data[1] . "，请修改。如非本人操作，请忽略本条短信。";
            break;
        case 80286:
            $content = "【车族宝】尊敬的车族宝会员,验证码是：" . $data[0] . "，请完成验证。如非本人操作，请忽略本条短信。";
            break;
        case 80288:
            $content = "【车族宝】尊敬的车族宝会员,注册成功,登录密码码是：" . $data[1] . "，请完成验证。如非本人操作，请忽略本条短信。";
            break;
        default:
            //直接发内容
            $content = $data;
            break;
    }

    //Log::record($content);
    //file_get_contents($serverIP ."?user=tomato1988&pwd=197315&mobiles={$mobile}&contents={$content}&chid=0&sendtime=".date('Y-m-d H:i:s'));

    $post_data = "user=tomato1988&pwd=197315&mobiles={$mobile}&contents={$content}&chid=0&sendtime=".date('Y-m-d H:i:s');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $serverIP);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // post数据
    curl_setopt($ch, CURLOPT_POST, 1);
    // post的变量
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    $output = curl_exec($ch);
    curl_close($ch);

    return array("code" => 'success' ,"msg");
}

