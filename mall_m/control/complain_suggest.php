<?php
/**
 * 投诉建议
 *
 */
defined('InSystem') or exit('Access Invalid!');

class complain_suggestControl extends mobileMemberControl {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 保存数据
     */
    public function cs_postOp(){

        $member_id = $this->member_info['member_id'];
        $member_name = $this->member_info['member_name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $message = $_POST['message'];
        $pic_list = $_POST['complain_pic'];
        $pic0 = $pic_list[0];
        $pic1 = $pic_list[1];
        $pic2 = $pic_list[2];

        if(empty($phone)){
            output_error('联系电话不能为空!');
        }

        if(empty($message)){
            output_error('建议内容不能为空!');
        }

        $addData['cs_member_id'] = $member_id;
        $addData['cs_member_name'] = $member_name;
        $addData['cs_mobile'] = $phone;
        $addData['cs_content'] = $message;
        $addData['cs_state'] = 1;
        if($email) $addData['cs_email'] = $email;
        if($pic0) $addData['cs_pic1'] = $pic0;
        if($pic1) $addData['cs_pic2'] = $pic1;
        if($pic2) $addData['cs_pic3'] = $pic2;

        $model_complain_suggest = Model('complain_suggest');

        $result = $model_complain_suggest->addData($addData);

        if($result){
            output_data(1);
        }else{
            output_error('提交失败');
        }
    }

    /**
     * 上传图片
     */
    public function upload_picOp() {
        $upload = new UploadFile();
        $dir = ATTACH_PATH.DS.'complain_suggest'.DS;
        $upload->set('default_dir',$dir);
        $upload->set('allow_type',array('jpg','jpeg','gif','png'));
        $result = 0;
        if (!empty($_FILES['complain_pic']['name'])){
            $result = $upload->upfile('complain_pic');
        }
        if ($result){
            $file_name = $upload->file_name;
            $pic = UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/complain_suggest/'.$file_name;
            output_data(array('file_name' => $file_name,'pic' => $pic));
        } else {
            output_error('图片上传失败');
        }
    }

}
