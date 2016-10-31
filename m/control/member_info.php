<?php
/**
 * 会员信息管理
 ***/


defined('InSystem') or exit('Access Invalid!');
class member_infoControl extends mobileMemberControl {

    public function __construct() {
        parent::__construct();

    }

    /**
     * 新的用户，未设置支付密码就是新用户
     */
    public function isNewMemberOp(){
        $member_info = Model('member')->getMemberInfoByID($this->member_info['member_id']);

        if($member_info['member_paypwd'] == ''){
            output_data(array('is_new'=>'1'));
        }
        output_data(array('is_new'=>'0'));
    }


    /**
     * 设置支付密码
     */
    public function setPayPWDOp(){
        $model_member	= Model('member');

        $member_info = $model_member->getMemberInfoByID($this->member_info['member_id']);
        if($member_info['member_paypwd'] != ''){
            output_error('支付密码已设置，如需修改请至个人中心');
        }

        $cache_key = $_POST['cache_key'];
        $identifying_code = $_POST['identifying_code'];
        $password_times_key = 'setpasswd'.$this->member_info['member_name'];
        $pay_password = $_POST['pay_password'];

        if(rkcache($cache_key) == '' || $identifying_code != rkcache($cache_key)){
            $this->checkTimes($password_times_key);
            output_error('验证码不正确');
        }

        if(strlen($pay_password) < 6){
            output_error('请输入至少6位数支付密码');
        }

        $member_array['member_paypwd'] = md5($pay_password);

        $update = $model_member->editMember(array('member_id'=>$this->member_info['member_id']),$member_array);

        $msg = $update ? '设置成功!' : '设置失败';
        $status = $update ? 1 : 0;
        output_data(array('msg'=>$msg,'status'=>$status));
    }

    /**
     *
     * 返回用户信息
     * @return array
     */
    public function getMemberInfoOp(){
        $member_info = $this->member_info;
        unset($member_info['member_passwd']);
        unset($member_info['member_paypwd']);
        output_data(array('member_info'=>$member_info));
    }


    /**
     * 推荐会员的会员积分总表
     */
    public function modifyOp(){
        $model_member = Model('member');
        $member_id = $_POST['member_id'] ? $_POST['member_id'] : $this->member_info['member_id'];

        $member = $model_member->getMemberInfoByID($member_id);

        if(!$member){
            output_error('会员不存在');
        }

        $member_info = array();


        if($_POST['name']){
            $member_info['member_truename'] = $_POST['name'];
        }else{
            output_error('请输入姓名');
        }



        if($_POST['nikename']){
            $member_info['member_nickname'] = $_POST['nikename'];
        }
        if($_POST['sex']){
            $member_info['member_sex'] = $_POST['sex'];
        }

        if($_POST['idcard']){
            if(checkIdCard($_POST['idcard'])){
                $member_info['member_idcard'] = $_POST['idcard'];
            }else{
                output_error('请输入正确的身份证号码');
            }

            $member_count = $model_member->getMemberCount(" member_id <> " . $this->member_info['member_id'] ." AND member_idcard = '{$member_info['member_idcard']}'");

            if($member_count>0){
                output_error('一个身份证只能绑定一个号码');
            }
        }else{
            output_error('请输入身份证号码');
        }

        $model_member->editMember(array('member_id'=>$member_id),$member_info);

        output_data(array('code'=>'success','msg'=>'修改成功'));
    }

    /**
     * 上传头像
     */
    public function upload_avatarOp(){
        $member_id = $_POST['member_id'] ? $_POST['member_id'] : $this->member_info['member_id'];
        $thumb_width = $_POST['width'] ? $_POST['width'] : 500;
        $thumb_height = $_POST['height'] ? $_POST['height'] : 500;

        //上传图片
        $upload = new UploadFile();
        $upload->set('thumb_width',	$thumb_width);
        $upload->set('thumb_height',$thumb_height);
        $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
        $upload->set('file_name',"avatar_$member_id.$ext");
        $upload->set('thumb_ext','_new');
        $upload->set('ifremove',true);
        $upload->set('default_dir',ATTACH_AVATAR);
        if (!empty($_FILES['avatar']['tmp_name'])){
            $result = $upload->upfile('avatar');
            if (!$result){
                output_error($upload->error);
            }
        }else{
            output_error('上传失败，请尝试更换图片格式或小图片');
        }

        $avatar_url = UPLOAD_SITE_URL . DS . ATTACH_AVATAR . DS . $upload->thumb_image;

        $result = array('avatar'=>$avatar_url);

        $member_array['member_avatar'] = $avatar_url;

        Model('member')->editMember(array('member_id'=>$this->member_info['member_id']),$member_array);
        output_data($result);
    }

    //修改密码 与 支付密码
    public function edit_passwordOp() {
        $old_password = $_REQUEST['old_password'];
        $new_password = trim($_REQUEST['new_password']);
        $confirm_password = trim($_REQUEST['confirm_password']);
        $password_type = $_REQUEST['password_type']; //mp:会员密码;pp:支付密码
        $return = array('msg'=>'','status'=>0);
        if(empty($old_password)){
            $msg = '旧密码不能为空!';
            $return['msg'] = $msg;
            output_data($return);
        }
        if(empty($new_password)){
            $msg = '新密码不能为空!';
            $return['msg'] = $msg;
            output_data($return);
        }
        if(empty($confirm_password)){
            $msg = '确认密码不能为空!';
            $return['msg'] = $msg;
            output_data($return);
        }
        if(strlen($new_password) < 6){
            $msg = '新密码长度不能少于6位数!';
            $return['msg'] = $msg;
            output_data($return);
        }
        if($new_password != $confirm_password){
            $msg = '新密码与确认密码不一致!';
            $return['msg'] = $msg;
            output_data($return);
        }

        $model_member = Model('member');
        $member_info = $model_member->getMemberInfoByID($this->member_info['member_id']);

        if($password_type == 'mp'){
            if($member_info['member_passwd'] != md5($old_password)){
                $msg = '旧密码不正确!';
                $return['msg'] = $msg;
                output_data($return);
            }

            $member_array['member_passwd'] = md5($new_password);

        }else{
            if($member_info['member_paypwd'] != md5($old_password)){
                $msg = '旧密码不正确!';
                $return['msg'] = $msg;
                output_data($return);
            }

            $member_array['member_paypwd'] = md5($new_password);

        }

        $update = $model_member->editMember(array('member_id'=>$this->member_info['member_id']),$member_array);

        $msg = $update ? '修改密码成功!' : '修改密码失败';
        $status = $update ? 1 : 0;
        output_data(array('msg'=>$msg,'status'=>$status));
    }

    /**
     * 获得对应的QRCODE
     */
    public function getQrcodeOp(){
        $qrcode = UPLOAD_SITE_URL . DS . MyQRcode::buildMember($this->member_info['member_name']);


        $result = array('invite'=>$qrcode,'store'=>$qrcode);
        output_data($result);
    }

    /**
     * 获得对应的QRCODE
     */
    public function getInviteUrlOp(){
        $url = BASE_SITE_URL . DS . 'invite/?i='.$this->member_info['member_id'];
        $qrcode = UPLOAD_SITE_URL . DS . MyQRcode::buildMember($url);


        $result = array('invite'=>$qrcode,'url'=>$url);
        output_data($result);
    }

    /**
     * 是否开启手势密码
     */
    public function changeSignOp(){
        $member_id = $this->member_info['member_id'];
        $model_member = Model('member');
        $value = $_REQUEST['value'];

        $member_info = array();
        $member_info['is_sign'] = $value;
        $model_member->editMember(array('member_id'=>$member_id),$member_info);
        output_data(array('code'=>'success'));
    }

    /**
     * 修改手势密码
     */
    public function modifySignOp(){
        $password = $_REQUEST['password'];
        $new_sign = $_REQUEST['new_sign'];

        if(empty($password)){
            $msg = '密码不能为空!';
            $return['msg'] = $msg;
            output_data($return);
        }

        if(empty($new_sign)){
            $msg = '手势密码不能为空!';
            $return['msg'] = $msg;
            output_data($return);
        }

        $return = array('msg'=>'','status'=>0);
        $model_member = Model('member');
        $condition = array();
        $condition['member_id'] = $this->member_info['member_id'];
        $member_info = $model_member->getMemberInfoByID($this->member_info['member_id']);

        if($member_info['member_passwd'] != md5($password)){
            $msg = '密码不正确!';
            $return['msg'] = $msg;
            output_data($return);
        }

        $member_array = array();
        $member_array['member_sign'] = md5($new_sign);
        $update = $model_member->editMember(array('member_id'=>$this->member_info['member_id']),$member_array);
        $msg = $update ? '手势密码修改成功!' : '手势密码修改失败';
        $status = $update ? 1 : 0;
        output_data(array('msg'=>$msg,'status'=>$status));
    }

    /**
     * 验证手势密码
     */
    public function checkSignOp(){
        $sign = $_REQUEST['sign'];

        if(empty($sign)){
            $msg = '手势密码不能为空!';
            $return['msg'] = $msg;
            output_data($return);
        }

        $model_member = Model('member');
        $condition = array();
        $condition['member_id'] = $this->member_info['member_id'];
        $member_info = $model_member->getMemberInfoByID($this->member_info['member_id']);


        if($member_info['member_sign'] != md5($sign)){
            output_data(array('msg'=>'错误','status'=>0));
        }

        output_data(array('msg'=>'正确','status'=>1));
    }
}
