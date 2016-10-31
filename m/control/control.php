<?php
/**
 * mobile父类
 *
 *
 *
 */


defined('InSystem') or exit('Access Invalid!');

/********************************** 前台control父类 **********************************************/

class mobileControl{

    //客户端类型
    protected $client_type_array = array('android', 'ios', 'pc');
    //列表默认分页数
    protected $page = 15;
    protected $rsa_data = array();
    private $_appsecret = '84joenk2ovnds8r32ndkajeuqh943kdsk';
    protected $need_login = true;

	public function __construct() {
        Language::read('mobile');

        //分页数处理
        $page = intval($_GET['pagesize']);
        if($page > 0) {
            $this->page = $page;
        }
        if($_POST['curpage']){
            $_GET['curpage'] = $_POST['curpage'];
        }

        if(!in_array($_GET['act'],array('member_payment','payment','message','logout'))){
            $client = $_REQUEST['client'];
            if(in_array($client,$this->client_type_array)){
                $token = $_REQUEST['token'];
                $t = $_REQUEST['t'];
                if($_REQUEST['client'] == 'pc'){
                    if(abs($t - TIMESTAMP) > 3600){
                        output_error('请刷登录页面重试');
                    }
                }
                if(md5(md5($t.$this->_appsecret)) != $token){
                    output_error('认证失败');
                }
            }else{
                //output_error('非法客户端');
            }
        }

        $this->rsaPOST();
    }

    /**
     * 私密解
     */
    private function rsaPOST(){
        $encrypted = $_POST['data'];
        if($encrypted){
            $private_key=file_get_contents("rsa/rsa_private_key.pem","r");
            $pi_key =  openssl_pkey_get_private($private_key);
            openssl_private_decrypt(base64_decode($encrypted),$decrypted,$pi_key);//私钥解密
            $post = json_decode($decrypted,true);

            if(is_array($post) && $post){
                foreach($post as $key=>$val){
                    $this->rsa_data[$key] = $val;
                }
            }
        }
    }

    public function createAnonymous($key = ''){
        sleep(1);
        return md5($key . microtime() . mt_rand(0,10000));
    }

    /**
     *
     * 请求次数限制
     *
     * @param $key
     * @param int $allow_times
     * @param int $expire
     * @return bool
     * @throws Exception
     */
    public function checkTimes($key,$allow_times = 5,$expire = 60){
        return true;
        $key = md5($key);
        $times = intval(rkcache($key));

        if($times >= $allow_times){
            output_error('请求超过'.$allow_times.'次，帐号被锁定');
        }else{
            wkcache($key,strval($times + 1),$expire);
        }
    }

    /**
     *
     * 取消次数限制
     *
     * @param $key
     */
    public function outCheckTimes($key){
        dkcache(md5($key));
    }

}

class mobileHomeControl extends mobileControl{
	public function __construct() {
        parent::__construct();
    }
}

class mobileMemberControl extends mobileControl{

    protected $member_info = array();

	public function __construct() {
        parent::__construct();
        if($_GET['act'] == 'fund' && $_GET['op'] == 'index') return;

        $model_mb_user_token = Model('mb_user_token');
        $key = $_POST['key'];
        if(empty($key)) {
            $key = $_GET['key'];
        }
        $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);
        if($this->need_login && empty($mb_user_token_info)) {
            output_error('请登录', array('login' => '0'));
        }
        if($mb_user_token_info) {
            $model_member = Model('member');
            $this->member_info = $model_member->getMemberInfoByID($mb_user_token_info['member_id']);
            $this->member_info['client_type'] = strtolower($mb_user_token_info['client_type']);

            if (empty($this->member_info)) {
                output_error('请登录', array('login' => '0'));
            } else {
                //超过一天没登录，重新登录
                if (abs(time() - $mb_user_token_info['login_time']) > 86400) {
                    $condition = array();
                    $condition['token_id'] = $mb_user_token_info['token_id'];
                    $model_mb_user_token->delMbUserToken($condition);
                    output_error('登录超时，请重新登录', array('login' => '0'));
                }

                unset($this->member_info['member_passwd']);
                unset($this->member_info['member_paypwd']);
                //更新登录时间
                $condition = array();
                $condition['token_id'] = $mb_user_token_info['token_id'];
                $model_mb_user_token->editMbUserToken(array('login_time' => time()), $condition);
            }
        }
    }
}
