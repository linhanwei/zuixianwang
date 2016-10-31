<?php
/**
 * PC端
 */
defined('InSystem') or exit('Access Invalid!');

class ClientControl{
    public $member_info = array();
    private $_appsecret = '84joenk2ovnds8r32ndkajeuqh943kdsk';

    /**
     * 构造函数
     * @param bool $need_login
     */
	public function __construct($need_login = true){
        if($need_login) $this->check_login();

	}

    protected function check_login() {
        if(!$_SESSION['member_id']) {
            $ref_url = BASE_SITE_URL.request_uri();
            header('location: '.CLIENT_SITE_URL.'/index.php?act=login&ref_url='.$ref_url);die;
        }

        $this->member_info = Model('member')->getMemberInfoByID($_SESSION['member_id']);
        Tpl::output('member_info',$this->member_info);
    }



    protected function get_member_avatar($member_id) {
        if(!isset($_SESSION['member_avatar'])) {
            $model_member = Model('member');
            $member_info = $model_member->infoMember(array('member_id'=>$member_id));
            $_SESSION['member_avatar'] = $member_info['member_avatar'];
        }
    }


    /**
     * 获得验证数据.
     */
    public function get_token(){
        $time = TIMESTAMP;
        $token = md5(md5($time.$this->_appsecret));

        return array($time,$token);
    }
}

