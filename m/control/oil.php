<?php
/**
 * 油卡相关
 * User: benluo
 * Date: 16/3/4
 * Time: 下午10:01
 */

defined('InSystem') or exit('Access Invalid!');

class oilControl extends mobileMemberControl {
    public function __construct(){
        //output_error('系统维护，我们会尽快处理');
        parent::__construct();
    }



    /*
     * 油卡流程
     */
    public function checkOp($return = false){
        if($this->member_info['grade_id'] != 1){
            output_data(array('step'=>1,'msg'=>'VIP专属，请升级'));
        }

        $model_pd = Model('predeposit');

        if($_POST["mobile"]){
            $card_info = $model_pd->getOilCardInfo(array('oc_mobile'=>$_POST["mobile"]));
            if($card_info){
                showMessage('一个手机号码只能绑定一张油卡');
            }
        }

        $oil_card = $model_pd->getOilCardInfo(array('oc_member_id'=>$this->member_info['member_id']));
        if(empty($oil_card)){
            output_data(array('step'=>2,'msg'=>'请先购买油卡'));
        }

        if($oil_card['oc_state'] == 1){
            output_data(array('step'=>3,'msg'=>'资料审核中','card'=>$oil_card));
        }
        if($oil_card['oc_state'] == 3){
            output_data(array('step'=>4,'msg'=>'资料有误，请修改','card'=>$oil_card));
        }



        if($return){
            if($oil_card['oc_type'] == '2'){
                output_data(array('step'=>3,'msg'=>'ＢＰ油卡不能充值','card'=>$oil_card));
            }
            return;
        }
        output_data(array('step'=>5,'msg'=>'油卡可充值','card'=>$oil_card));
    }
    /*
     * 充油卡
     */
    public function rechargeOp() {
        $ol_amount = $_POST['amount'];
        $payment_code = $_POST['payment_code'];

        $this->checkOp(true);
        if($ol_amount<OIL_PRICE){
            output_data('最少充' . OIL_PRICE . '元');
        }

        if(!in_array($payment_code,array('predeposit'))){
            output_error('请输入正确的支付方式');
        }

        if($ol_amount % OIL_PRICE !== 0){
            output_error('请输入' . OIL_PRICE . '的倍数');
        }

        if($ol_amount > $this->member_info['available_predeposit']){
            output_error('您的账户余额不够,请先充值!');
        }

        $model_ol = Model('oil_log');
        $model_pd = Model('predeposit');

        $data = array();
        $pay_sn = '';

        $ol_amount *= OIL_RATE;

        $data['ol_sn'] = $model_pd->makeSn($this->member_info['member_id']);
        $data['ol_member_id'] = $this->member_info['member_id'];
        $data['ol_member_name'] = $this->member_info['member_name'];
        $data['ol_amount'] = $ol_amount;
        $data['ol_add_time'] = TIMESTAMP;
        $insert = $model_ol->addOilLog($data);
        if($insert){
            $pay_sn = $data['ol_sn'];
        }

        if ($pay_sn) {
            //余额支付
            if($payment_code == 'predeposit'){

                $model_pd->beginTransaction();
                $ol_sn = $model_pd->makeSn($this->member_info['member_id']);
                //减余额
                $data = array();
                $data['member_id'] = $this->member_info['member_id'];
                $data['member_name'] = $this->member_info['member_name'];
                $data['amount'] = $ol_amount;
                $data['ol_sn'] = $ol_sn;

                try{
                    if($model_pd->changePd('oil',$data)){
                        $update = array();
                        $update['ol_payment_state'] = 1;
                        $update['ol_payment_time'] = TIMESTAMP;
                        $update['ol_payment_code'] = 'predeposit';
                        $update['ol_payment_name'] = '余额支付';
                        $update['ol_trade_sn'] = $ol_sn;
                        $update['ol_state'] = 1;
                        $update = $model_ol->editOilLog($update,array('ol_sn'=>$pay_sn));

                        if($update){
                            $model_pd->commit();
                            output_data(array('ol_sn'=>$ol_sn));
                        }else{
                            $model_pd->rollback();
                            output_error('充值失败');
                        }
                    }
                }catch (Exception $ex){
                    $model_pd->rollback();
                    output_error('充值失败:'.$ex->getMessage());
                }

            }
        }
    }

    /*
     * 购买油卡
     */
    public function buyOp() {
        $payment_code = $_POST['payment_code'] ? $_POST['payment_code'] : 'predeposit';
        $mobile = $_POST['mobile'];
        $idcard_number = $_POST['idcard_number'];
        $idcard_name    =$_POST['idcard_name'];
        $address = $_POST['address'];
        $oc_type = $_POST['oc_type'] ? $_POST['oc_type'] : 1;
        $oil_price = OIL_PRICE;
        if($oc_type == 2){
            $oil_price = OIL_BP_PRICE;
        }

        if(!in_array($payment_code,array('wxpay','alipay','predeposit'))){
            output_error('请输入正确的支付方式');
        }

        if(empty($mobile)){
            output_error('请填写手机号');
        }

        if(checkIdCard($idcard_number) === false){
            output_error('请输入正确的身份证号码');
        }

        if(empty($idcard_name)){
            output_error('请输入身份证姓名');
        }

        if(empty($address)){
            output_error('请输入收货地址');
        }

        if($oil_price > $this->member_info['available_predeposit']){
            output_error('您的账户余额不够,请先充值!');
        }

        $model_oc = Model('predeposit');
        $condition = array();
        $condition['oc_member_id'] = $this->member_info['member_id'];
        $oil_card_info = $model_oc->getOilCardInfo($condition);

        $data = array();
        $pay_sn = '';
        if($oil_card_info){
            if($oil_card_info['oc_payment_state'] == 1){
                output_error('已支付成功，如有问题请联系客服');
            }
            $pay_sn = $oil_card_info['oc_sn'];
            $new_data = array();
            $new_data['oc_type'] = $oc_type;
            $new_data['oc_amount'] = $oil_price;
            $new_data['oc_mobile'] = $mobile;
            $new_data['oc_idcard_front'] = $this->upload_image('idcard_front');
            if(empty($new_data['oc_idcard_front'])){
                output_error('请上传身份证正面');
            }
            $new_data['oc_idcard_back'] = $this->upload_image('idcard_back');
            if(empty($new_data['oc_idcard_back'])){
                output_error('请上传身份证反面');
            }
            $new_data['oc_address'] = $address;
            $new_data['oc_state'] = 1;
            $new_data['oc_idcard_number'] = $idcard_number;
            $new_data['oc_idcard_name'] = $idcard_name;
            $model_oc->editOilCard($new_data,$condition);
        }else{
            $data['oc_sn'] = $model_oc->makeSn($this->member_info['member_id']);
            $data['oc_type'] = $oc_type;
            $data['oc_member_id'] = $this->member_info['member_id'];
            $data['oc_member_name'] = $this->member_info['member_name'];
            $data['oc_amount'] = $oil_price;
            $data['oc_add_time'] = TIMESTAMP;

            $data['oc_mobile'] = $mobile;
            $data['oc_idcard_front'] = $this->upload_image('idcard_front');
            if(empty($data['oc_idcard_front'])){
                    output_error('请上传身份证正面');
            }
            $data['oc_idcard_back'] = $this->upload_image('idcard_back');
            if(empty($data['oc_idcard_back'])){
                output_error('请上传身份证反面');
            }
            $data['oc_address'] = $address;
            $data['oc_idcard_number'] = $idcard_number;
            $data['oc_idcard_name'] = $idcard_name;
            $insert = $model_oc->addOilCard($data);
            if($insert){
                $pay_sn = $data['oc_sn'];
            }
        }

        if ($pay_sn) {
            //余额支付
            if($payment_code == 'predeposit'){

                $model_pd = Model('predeposit');
                $model_pd->beginTransaction();
                $oc_sn = $model_pd->makeSn($this->member_info['member_id']);
                //减余额
                $data = array();
                $data['member_id'] = $this->member_info['member_id'];
                $data['member_name'] = $this->member_info['member_name'];
                $data['amount'] = $oil_price;
                $data['oc_sn'] = $oc_sn;

                try{
                    if($model_pd->changePd('oil_card',$data)){
                        $update = array();
                        $update['oc_payment_state'] = 1;
                        $update['oc_payment_time'] = TIMESTAMP;
                        $update['oc_payment_code'] = 'predeposit';
                        $update['oc_payment_name'] = '余额支付';
                        $update['oc_trade_sn'] = $oc_sn;
                        $update = $model_pd->editOilCard($update,array('oc_sn'=>$pay_sn));

                        if($update){
                            $model_pd->commit();
                            output_data(array('oc_sn'=>$oc_sn));
                        }else{
                            $model_pd->rollback();
                            output_error('购买油卡失败');
                        }
                    }
                }catch (Exception $ex){
                    $model_pd->rollback();
                    output_error('购买油卡失败:'.$ex->getMessage());
                }

            }
        }

    }

    /**
     * 修改油卡信息
     */
    public function modifyOp(){
        $mobile = $_POST['mobile'];
        $idcard_front = $_POST['idcard_front'];
        $idcard_back = $_POST['idcard_back'];
        $address = $_POST['address'];

        if(empty($mobile)){
            output_error('请填写手机号');
        }
        if(empty($idcard_front)){
            output_error('请上传身份证正面');
        }

        if(empty($idcard_back)){
            output_error('请上传身份证反面');
        }

        if(empty($address)){
            output_error('请输入收货地址');
        }
        $model_oc = Model('predeposit');
        $condition = array();
        $condition['oc_member_id'] = $this->member_info['member_id'];
        $oil_card_info = $model_oc->getOilCardLogInfo($condition);

        if($oil_card_info['oc_state'] == 3 && $oil_card_info['oc_payment_state'] == 1){
            $new_data = array();
            $new_data['oc_mobile'] = $mobile;
            $new_data['oc_idcard_front'] = $this->upload_image('idcard_front');
            $new_data['oc_idcard_back'] = $this->upload_image('idcard_back');
            $new_data['oc_address'] = $address;
            $new_data['oc_state'] = 1;
            $model_oc->editOilCardLog($new_data,$condition);
            output_data(array('oc_sn'=>$oil_card_info['oc_sn']));
        }else{
            output_error('请先购买油卡');
        }

    }

    /**
     *
     * 上传文件
     * @param $file
     * @return string
     */
    private function upload_image($file) {
        $pic_name = '';
        $upload = new UploadFile();
        $uploaddir = ATTACH_PATH.DS.'idcard'.DS;
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
