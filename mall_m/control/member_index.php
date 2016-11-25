<?php
/**
 * 我的商城
 *
 *
 *
 *
 */


defined('InSystem') or exit('Access Invalid!');

class member_indexControl extends mobileMemberControl {

	public function __construct(){
		parent::__construct();
	}

    /**
     * 会员新首页
     */
    public function new_indexOp(){
        $member_info = array();
        $member_info['user_name'] = $this->member_info['member_name'];
        $member_info['avator'] = $this->member_info['member_avatar'];
        $member_info['point'] = number_format($this->member_info['member_points'],2);
        $member_info['predepoit'] = $this->member_info['available_predeposit'];
        $member_info['available_rc_balance'] = $this->member_info['available_rc_balance'];
//        dump($member_info);
        Tpl::output('member_info',$member_info);
        Tpl::showpage('member.index');
    }

    /**
     * 会员中心首页
     */
	public function indexOp() {
        $member_info = array();
        $member_info['user_name'] = $this->member_info['member_name'];
        $member_info['avator'] = $this->member_info['member_avatar'];
        $member_info['point'] = number_format($this->member_info['member_points'],2);
        $member_info['predepoit'] = $this->member_info['available_predeposit'];
        $member_info['available_rc_balance'] = $this->member_info['available_rc_balance'];
        $member_info['birthday'] = $this->member_info['member_birthday'] ? $this->member_info['member_birthday'] : date('Y-m-d');
        $member_info['nickname'] = $this->member_info['member_nickname'];
        $member_info['sex'] = intval($this->member_info['member_sex']);

        //网站信息
        $model_setting = Model('setting');
        $list_setting = $model_setting->getListSetting();
//        dump($member_info);
//        Tpl::output('member_info',$member_info);
//        Tpl::showpage('member.index');

        output_data(array('member_info' => $member_info,'web_config'=>$list_setting));
	}

    //修改会员资料
    public function edit_member_infoOp(){
        $return = array('status'=>0,'msg'=>'');
        $nickname = $_POST['nickname'];
        $sex = $_POST['sex'];
        $birthday = $_POST['birthday'];
        $avatar_img = $_POST['avatar_img'];

        $data = array();
        if($nickname) $data['member_nickname'] = $nickname;
        if($sex) $data['member_sex'] = $sex;
        if($birthday) $data['member_birthday'] = $birthday;
        if($avatar_img) $data['member_avatar'] = $avatar_img;

        if(!empty($data)){
            $model_member = Model('member');
            $condition['member_id'] = $this->member_info['member_id'];
            $result = $model_member->editMember($condition, $data);

            if($result){
                $return['status'] = 1;
                $return['msg'] = '修改成功';
            }else{
                $return['msg'] = '修改失败';
            }
        }else{
            $return['msg'] = '没有修改的资料';
        }

        output_data($return);

    }

    //上传头像
    public function upload_avatarOp(){
        header('Content-type:text/html;charset=utf-8');
        $base64_image_content = $_POST['imgBase64'];
        $return = array('status'=>'0','msg'=>'','data'=>'');
        //匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
            $type = $result[2];
            $upload_dir = DIR_UPLOAD.DS.ATTACH_AVATAR.DS;
            $new_file = BASE_ROOT_PATH.DS.$upload_dir;

            if(!file_exists($new_file)){
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0700);
            }

            $avatar_name = 'avatar_'.$this->member_info['member_id'].'_new.'.$type;
            $upload_file = $new_file.$avatar_name;

            if (file_put_contents($upload_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){

                $return['status'] = 1;
                $return['msg'] = '头像上传成功';
                $return['data'] = array('img_url'=>BASE_SITE_URL.DS.$upload_dir.$avatar_name,'img_name'=>$avatar_name);
            }else{
                $return['msg'] = '头像上传失败';
            }

            output_data($return);

        }
    }

}
