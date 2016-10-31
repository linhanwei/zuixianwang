<?php

class sendMemberMsg {
    private $code = '';
    private $member_id = 0;
    private $member_info = array();
    private $mobile = '';
    private $email = '';

    /**
     * 设置
     *
     * @param mixed $key
     * @param mixed $value
     */
    public function set($key,$value){
        $this->$key = $value;
    }

    public function send($param = array()) {
        $msg_tpl = rkcache('member_msg_tpl', true);
        if (!isset($msg_tpl[$this->code]) || $this->member_id <= 0) {
            return false;
        }

        $tpl_info = $msg_tpl[$this->code];

        $setting_info = Model('member_msg_setting')->getMemberMsgSettingInfo(array('mmt_code' => $this->code, 'member_id' => $this->member_id), 'is_receive');
        if (empty($setting_info) || $setting_info['is_receive']) {
            // 发送站内信
            //if ($tpl_info['mmt_message_switch']) {
                $message = ncReplaceText($tpl_info['mmt_message_content'],$param);
                $this->sendMessage($message);
            //}
            // 发送短消息
            //if ($tpl_info['mmt_short_switch']) {
                $this->getMemberInfo();
                if (empty($this->mobile)) $this->mobile = $this->member_info['member_name'];

                $param['site_name'] = C('site_name');
                //$message = ncReplaceText($tpl_info['mmt_short_content'],$param);
                $message = $tpl_info['mmt_short_content'];
                $this->sendShort($this->mobile, $message,$param);

           // }
            // 发送邮件
            /*if ($tpl_info['mmt_mail_switch']) {
                $this->getMemberInfo();
                if (!empty($this->email)) $this->member_info['member_email'] = $this->email;
                if ($this->member_info['member_email_bind'] && !empty($this->member_info['member_email'])) {
                    $param['site_name'] = C('site_name');
                    $param['mail_send_time'] = date('Y-m-d H:i:s');
                    $subject = ncReplaceText($tpl_info['mmt_mail_subject'],$param);
                    $message = ncReplaceText($tpl_info['mmt_mail_content'],$param);
                    $this->sendMail($this->member_info['member_email'], $subject, $message);
                }
            }*/
        }
    }

    /**
     * 会员详细信息
     */
    private function getMemberInfo() {
        if (empty($this->member_info)) {
            $this->member_info = Model('member')->getMemberInfoByID($this->member_id);
        }
    }

    /**
     * 发送站内信
     * @param unknown $message
     */
    private function sendMessage($message) {
        //添加短消息
        $model_message = Model('message');
        $insert_arr = array();
        $insert_arr['from_member_id'] = 0;
        $insert_arr['to_member_id'] = $this->member_id;
        $insert_arr['message_body'] = $message;
        $insert_arr['message_type'] = 1;
        $insert_arr['message_time'] = TIMESTAMP;
        $model_message->addMessage($insert_arr);
    }

    /**
     * 发送短消息
     * @param unknown $number
     * @param unknown $message
     * @param array $param
     */
    private function sendShort($number, $message,$param) {
        //容联云
        switch($message){
            case 101767:
                $data = array($param['time'],$param['desc'],$param['av_amount'],$param['freeze_amount'],$param['available_predeposit']);
                break;

        }
        if($data){
            $sms = new Sms();
            $sms->send($number,$data, $message);
        }
    }

    /**
     * 发送邮件
     * @param unknown $number
     * @param unknown $subject
     * @param unknown $message
     */
    private function sendMail($number, $subject, $message) {
        // 计划任务代码
        $insert = array();
        $insert['mail'] = $number;
        $insert['subject'] = $subject;
        $insert['contnet'] = $message;
        Model('mail_cron')->addMailCron($insert);
    }
}
