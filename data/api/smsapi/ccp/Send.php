<?php
/*
 *  Copyright (c) 2014 The CCP project authors. All Rights Reserved.
 *
 *  Use of this source code is governed by a Beijing Speedtong Information Technology Co.,Ltd license
 *  that can be found in the LICENSE file in the root of the web site.
 *
 *   http://www.yuntongxun.com
 *
 *  An additional intellectual property rights grant can be found
 *  in the file PATENTS.  All contributing project authors may
 *  be found in the AUTHORS file in the root of the source tree.
 */

include_once("CCPRestSDK.php");


/**
 * @param $mobile
 * @param $content
 * @param $templateId
 * @return array|string
 */
function send_sms($mobile,$content,$templateId){
    // 初始化REST SDK
    //主帐号
    $accountSid= C('mobile_sid');

//主帐号Token
    $accountToken= C('mobile_token');

//应用Id
    $appId=C('mobile_appid');

//请求地址，格式如下，不需要写https://
    $serverIP='app.cloopen.com';

//请求端口
    $serverPort='8883';

//REST版本号
    $softVersion='2013-12-26';

    $rest = new REST($serverIP,$serverPort,$softVersion);
    $rest->setAccount($accountSid,$accountToken);
    $rest->setAppId($appId);

    // 发送模板短信
    $result = $rest->sendTemplateSMS($mobile,$content,$templateId);

    if($result == NULL ) {
        return "result error!";
    }
    if($result->statusCode!=0) {
        return array("code" => $result->statusCode ,"msg" . $result->statusMsg);
    }else{
        // 获取返回信息
        $smsmessage = $result->TemplateSMS;

        return array("code" => 'success' ,"msg" . $smsmessage->smsMessageSid);
    }
}


