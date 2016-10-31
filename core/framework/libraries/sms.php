<?php
/**
 * 手机短信类
 *
 *
 *
 */
defined('InSystem') or exit('Access Invalid!');

class Sms {
    /**
     * 发送手机短信
     * @param unknown $mobile 手机号
     * @param unknown $content 短信内容
     */
    public function send($mobile,$content,$templateId=0) {
	$plugin = str_replace('\\', '', str_replace('/', '', str_replace('.', '', C('sms_plugin'))));
        if (!empty($plugin)) {
            define('PLUGIN_ROOT', BASE_DATA_PATH . DS .'api/smsapi');
            require_once(PLUGIN_ROOT . DS . $plugin . DS . 'Send.php');
            return send_sms($mobile,$content,$templateId);
        }
    }
}
