<?php
/**
 * 加盟商管理
 *
 *
 *
 *
 */

defined('InSystem') or exit('Access Invalid!');

class agentControl extends mobileMemberControl{

    public function __construct() {
        parent::__construct();
    }

    public function feedbackOp(){
        $model_feedback = Model('feedback');

        $feedback_info = $model_feedback->getFeedbackInfo(array('fb_type'=>1,'fb_member_id' => $this->member_info['member_id']));

        if($feedback_info){
            output_error('您已经申请，客服会尽快联系您');
        }

        $param = array();
        $param['fb_member_id'] = $this->member_info['member_id'];
        $param['fb_member_name'] = $this->member_info['member_name'];
        $param['fb_type'] = $_POST['type'] ? $_POST['type'] : 1;
        $param['fb_content'] = $_POST['content'];

        $param['fb_image'] = $this->upload_image('image');
        $param['fb_addtime'] = TIMESTAMP;
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
           array("input"=>$param['fb_content'], "require"=>"true","message"=>"请输入留言内容")
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            output_error($error);
        }

        $model_feedback->addFeedback($param);
        output_data('资料提交成功');
    }

    private function upload_image($file) {
        $pic_name = '';
        $upload = new UploadFile();
        $uploaddir = ATTACH_PATH.DS.'agent'.DS;
        $upload->set('default_dir',$uploaddir);
        $upload->set('allow_type',array('jpg','jpeg','gif','png'));
        if (!empty($_FILES[$file]['name'])){
            $result = $upload->upfile($file);
            if ($result){
                $pic_name = $upload->file_name;
                $upload->file_name = '';
            }
        }
        return $pic_name;
    }
}
