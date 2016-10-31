<?php
/**
 * 转帐,交易管理
 * User: benluo
 * Date: 16/3/4
 * Time: 下午10:01
 */

defined('InSystem') or exit('Access Invalid!');

class predepositControl extends mobileMemberControl {
    public function __construct(){
        parent::__construct();
    }

    /**
     * 自动升级
     */
    public function auto_upgradeOp(){
        return ;
        if(Model('predeposit')->getPdTransferMemberAmount($this->member_info['member_id']) >= 1000){
            Model('member')->editMember(array('member_id'=>$this->member_info['member_id']),array('grade_id'=>1));
            output_data(array('msg'=>'会员升级成功'));
        }
        output_error('升级失败');
    }

    /*
     * 会员升级
     */
    public function upgradeOp() {
        $grade_id = $_POST['grade_id'];
        $payment_code = $_POST['payment_code'];
        $password = $_POST['password'];

        /*
        if(empty($password)){
            output_error('请输入支付密码');
        }*/
        if($grade_id && $payment_code){
            $grade_info = unserialize(C('member_grade'));

            if(!$grade_info[$grade_id]){
                output_error('请选择正确的等级');
            }

            $grade_info = $grade_info[$grade_id];

            $return = array('status'=>0,'msg'=>'');
            if($this->member_info['grade_id']  > 0 && $grade_id >= $this->member_info['grade_id']){
                output_error('当前会员级别为' . $grade_info['grade_name'] . '无需升级');
            }
            if(empty($this->member_info['member_idcard'])){
                $return['msg'] = '请在个人资料绑定身份证号';
                output_data($return);
            }

            /*
            $member_pass_info = Model('member')->getMemberInfoByID($this->member_info['member_id']);
            if($member_pass_info['member_paypwd'] != md5($password)){
                output_error('支付密码不正确');
            }*/

            if($grade_info['price']!=1000){
                output_error('非法操作');
            }

            if(!in_array($payment_code,array('wxpay','alipay','predeposit'))){
                output_error('请输入正确的支付方式');
            }

            if($grade_info['price'] > $this->member_info['available_predeposit']){
                $return['msg'] = '您的账户余额不够,请先充值!';
                output_data($return);
            }

            $model_pu = Model('predeposit');
            $condition = array();
            $condition['pu_member_id'] = $this->member_info['member_id'];
            $condition['pu_grade_id'] = $grade_id;
            $upgrade_info = $model_pu->getPdUpgradeInfo($condition);

            $data = array();
            $pay_sn = '';
            if($upgrade_info){
                if($upgrade_info['pu_payment_state'] == 1){
                    output_error('已支付成功，如等级没有变化请联系客服');
                }
                $pay_sn = $upgrade_info['pu_sn'];
                $model_pu->editUpgrade(array('pu_amount'=>$grade_info['price']),$condition);
            }else{
                $data['pu_sn'] = $model_pu->makeSn($this->member_info['member_id']);
                $data['pu_member_id'] = $this->member_info['member_id'];
                $data['pu_member_name'] = $this->member_info['member_name'];
                $data['pu_grade_id'] = $grade_id;
                $data['pu_amount'] = $grade_info['price'];
                $data['pu_add_time'] = TIMESTAMP;
                $insert = $model_pu->addPdUpgrade($data);
                if($insert){
                    $pay_sn = $data['pu_sn'];
                }
            }

            if ($pay_sn) {
                //余额支付
                if($payment_code == 'predeposit'){

                    $model_pd = Model('predeposit');
                    $model_pd->beginTransaction();
                    $pu_sn = $model_pd->makeSn($this->member_info['member_id']);
                    //减余额
                    $data = array();
                    $data['member_id'] = $this->member_info['member_id'];
                    $data['member_name'] = $this->member_info['member_name'];
                    $data['amount'] = $grade_info['price'];
                    $data['pu_sn'] = $pu_sn;

                    try{
                        if($model_pd->changePd('upgrade',$data)){
                            $update = array();
                            $update['pu_payment_state'] = 1;
                            $update['pu_payment_time'] = TIMESTAMP;
                            $update['pu_payment_code'] = 'predeposit';
                            $update['pu_payment_name'] = '余额支付';
                            $update['pu_trade_sn'] = $pu_sn;
                            $update = $model_pd->editUpgrade($update,array('pu_sn'=>$pay_sn));

                            if($update){
                                Logic('upgrade')->upgrade($this->member_info,$grade_info,$pay_sn);
                                $model_pd->commit();
                                output_data(array('pu_sn'=>$pu_sn));
                            }else{
                                $model_pd->rollback();
                                output_error('升级失败-001');
                            }
                        }
                    }catch (Exception $ex){
                        $model_pd->rollback();
                        output_error('升级失败-002');
                    }

                }
                $pay_url = 'index.php?act=member_payment&op=pd_order';
                //app操作
                if($this->member_info['client_type'] == 'android' || $this->member_info['client_type'] == 'ios'){
                    $model_payment = Model('payment');

                    //读取接口配置信息
                    $condition = array();
                    $condition['payment_code'] = $payment_code;
                    $payment_info = $model_payment->getPaymentOpenInfo($condition);
                    switch($payment_code){
                        case 'wxpay':
                            $params = "&pdr_sn={$pay_sn}&key={$_REQUEST['key']}&payment_code={$payment_code}&client=app";

                            output_data(array('prepay_url'=>MOBILE_SITE_URL . '/' . $pay_url . $params));
                            break;
                        case 'alipay':
                            output_data(array('pdr_sn'=>'u' . $pay_sn,'payment_info'=>$payment_info['payment_config'],'notify_url'=>MOBILE_SITE_URL . '/api/payment/alipay/notify_url.php'));
                            break;
                    }

                }
            }


        }else{
            output_error('请选择会员等级和支付方式!');
        }
    }

    /**
     * 充值添加
     */
    public function recharge_addOp(){
        $pdr_amount = ceil($_POST['pdr_amount']);

        if ($pdr_amount < 1) {
            output_error('输入正确的充值金额');
        }
        $payment_code = $_POST['payment_code'];
        if(!in_array($payment_code,array('wxpay','alipay'))){
            output_error('请输入正确的支付方式');
        }

        $model_pdr = Model('predeposit');
        $data = array();
        $data['pdr_sn'] = $pay_sn = $model_pdr->makeSn();
        $data['pdr_member_id'] = $this->member_info['member_id'];
        $data['pdr_member_name'] = $this->member_info['member_name'];
        $data['pdr_amount'] = $pdr_amount;
        $data['pdr_add_time'] = TIMESTAMP;
        $insert = $model_pdr->addPdRecharge($data);
        if ($insert) {
            $pay_url = 'index.php?act=member_payment&op=pd_order';
            //app操作
            if($this->member_info['client_type'] == 'android' || $this->member_info['client_type'] == 'ios'){
                $model_payment = Model('payment');

                //读取接口配置信息
                $condition = array();
                $condition['payment_code'] = $payment_code;
                $payment_info = $model_payment->getPaymentOpenInfo($condition);
                switch($payment_code){
                    case 'wxpay':
                        $params = "&pdr_sn={$pay_sn}&key={$_REQUEST['key']}&payment_code={$payment_code}&client=app";

                        output_data(array('prepay_url'=>MOBILE_SITE_URL . '/' . $pay_url . $params));
                        break;
                    case 'alipay':
                        output_data(array('pdr_sn'=>'p' . $data['pdr_sn'],'payment_info'=>$payment_info['payment_config'],'notify_url'=>MOBILE_SITE_URL . '/api/payment/alipay/notify_url.php'));
                        break;
                }

            }
            if($payment_code == 'alipay' || $payment_code == 'wxpay'){
                $str_html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
					<html>
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
						<title></title>
					</head>';
                $str_html .= '<form id="recharge" name="recharge" class="am-form transfer-form" method="post" action="' . $pay_url . '">';
                $str_html .= '<input type="hidden" name="pdr_sn" value="'.$pay_sn.'">
				<input type="hidden" name="key" id="key" value="' . $_REQUEST['key'] . '">
								<input type="hidden" id="payment_code" name="payment_code" value="' . $payment_code . '">
								';
                $str_html .= '</form>
							<script>
	function getcookie(name){
		var strcookie=document.cookie;
		var arrcookie=strcookie.split("; ");
		for(var i=0;i<arrcookie.length;i++){
		var arr=arrcookie[i].split("=");
		if(arr[0]==name)return arr[1];
		}
		return "";
	}document.getElementById("recharge").submit();</script></body>
					</html>';
                echo $str_html;
                exit();
            }

        }
    }


    /**
     * 转帐添加
     *
     * 商家==消费返还，推荐提成
     */
    public function transfer_addOp(){
        //$this->checkTimes('transfer'.$this->member_info['member_name'] . $_POST['pdt_amount']);
        $cache_key = $_POST['cache_key'];
        $identifying_code = $_POST['identifying_code'];
        $gaojihuo = $_POST['gaojihuo'] ? $_POST['gaojihuo'] : 0;


        if($gaojihuo==0){
            if(rkcache($cache_key) == '' || $identifying_code != rkcache($cache_key)){
                output_error('验证码不正确');
            }
        }

        $pdt_amount = round(abs(floatval($_POST['pdt_amount'])),2);
        $password = trim($_POST['password']);
        $pdt_remark = $_POST['pdt_remark'] ? $_POST['pdt_remark'] : '';

        if ($pdt_amount < 0.01) {
            output_error('输入正确的转出金额');
        }


        if($pdt_amount > 100000){
            output_error('单次最多转出100000元');
        }

        if(empty($password)){
            output_error('请输入支付密码');
        }

        $pdt_to_member_name = $_POST['pdt_to_member_name'];

        if($this->member_info['member_name'] == $pdt_to_member_name){
            output_error('不能转帐给自己');
        }


        $model_member = Model('member');

        $member = $model_member->getMemberInfo(array('member_name'=>$pdt_to_member_name));
        if(empty($member)){
            output_error('转出帐号不存在');
        }

        if($member['is_store'] == 0){
            output_error('只能向商户转帐');
        }

        if($this->member_info['available_predeposit'] < $pdt_amount){
            output_error('余额不足，请先充值');
        }

        $member_pass_info = $model_member->getMemberInfoByID($this->member_info['member_id']);
        if($gaojihuo==0) {
            if ($member_pass_info['member_paypwd'] != md5($password)) {
                output_error('支付密码不正确');
            }
        }

        $model_pd = Model('predeposit');
        $transfer_info = array();
        $transfer_info['pdt_sn'] = $pay_sn = $model_pd->makeSn();
        $transfer_info['pdt_from_member_id'] = $this->member_info['member_id'];
        $transfer_info['pdt_from_member_name'] = $this->member_info['member_name'];
        $transfer_info['pdt_to_member_id'] = $member['member_id'];
        $transfer_info['pdt_to_member_name'] = $member['member_name'];

        //消费计算手续费
        //15％手续费
        if($member['is_store'] == 1){
            $transfer_info['pdt_amount_rate'] = 0.15;
            $transfer_info['pdt_amount_out'] = $pdt_amount * $transfer_info['pdt_amount_rate'];
            $transfer_info['pdt_amount_get'] = round($pdt_amount - $transfer_info['pdt_amount_out'],2);
        }

        $transfer_info['pdt_amount'] = $pdt_amount;
        $transfer_info['pdt_add_time'] = TIMESTAMP;
        $transfer_info['pdt_remark'] = $pdt_remark;

        $model_pd->beginTransaction();
        try {
            $insert = $model_pd->addPdTransfer($transfer_info);
            if($insert){
                //转出变更
                $data = array();
                $data['member_id'] = $transfer_info['pdt_from_member_id'];
                $data['member_name'] = $transfer_info['pdt_from_member_name'];

                $data['amount'] = -1 * $transfer_info['pdt_amount'];

                $data['pdt_sn'] = $transfer_info['pdt_sn'];
                $data['lg_desc'] = '向[' . $transfer_info['pdt_to_member_name'] . '] 转帐,单号:'.$data['pdt_sn'];
                if($member['is_store'] == '1'){
                    $data['lg_desc'] = '向[' . $transfer_info['pdt_to_member_name'] . ']消费,单号:'.$data['pdt_sn'];
                }

                if(abs($data['amount'])>0){
                    $model_pd->changePd('transfer',$data);
                }else{
                    $model_pd->rollback();
                    throw new Exception('转帐出错');
                }

                //商户返会员积分，推荐提成
                //收入变更
                if($member['is_store'] == '1'){
                    //消费者上级佣金
                    Logic('inviter')->buyerCommis($transfer_info['pdt_from_member_id'],$transfer_info['pdt_from_member_name'],$pdt_amount,$transfer_info['pdt_sn']);

                    //商户收入代理佣金
                    Logic('inviter')->sellerCommis($transfer_info['pdt_to_member_id'],$transfer_info['pdt_to_member_name'],$pdt_amount,$transfer_info['pdt_sn']);

                    //变更家商帐户余额
                    //收款85%
                    $data = array();
                    $data['member_id'] = $transfer_info['pdt_to_member_id'];
                    $data['member_name'] = $transfer_info['pdt_to_member_name'];
                    $data['amount'] = $transfer_info['pdt_amount_get'];                 //实际获得85%
                    $data['pdt_sn'] = $transfer_info['pdt_sn'];
                    $data['lg_desc'] = '收到[' . $transfer_info['pdt_from_member_name'] . ' 付款,单号:'.$data['pdt_sn'];
                    $data['lg_desc'] .= ',金额:' . $pdt_amount . ',手续费:' . $transfer_info['pdt_amount_rate'] * 100 . '%';
                    $model_pd->changePd('transfer',$data);

                    //会员获得消费会员积分
                    $pointsArray = array(
                        'pl_desc'=>'消费获得会员积分,单号:'.$data['pdt_sn'],
                        'pl_memberid'=>$this->member_info['member_id'],
                        'pl_membername'=>$this->member_info['member_name'],
                        'pl_sn'=>$data['pdt_sn'],
                        'transfer_amount'=>$pdt_amount
                    );
                    Model('points')->savePointsLog('transfer',$pointsArray,true);

                    //商家上级佣金
                    //Logic('inviter')->sellerCommis($transfer_info['pdt_to_member_id'],$transfer_info['pdt_to_member_name'],$transfer_info['pdt_amount'],'');
                }else{  //一般会员更变
                    $data = array();
                    $data['member_id'] = $transfer_info['pdt_to_member_id'];
                    $data['member_name'] = $transfer_info['pdt_to_member_name'];
                    $data['amount'] = floatval($transfer_info['pdt_amount']);
                    $data['pdt_sn'] = $transfer_info['pdt_sn'];
                    $data['lg_desc'] = '收到[' . $transfer_info['pdt_from_member_name'] . ' 转帐,单号:'.$data['pdt_sn'];
                    $model_pd->changePd('transfer',$data);
                }

                $model_pd->commit();

                output_data(array('pdt_amount'=>$data['amount']));
            }
        } catch (Exception $e) {
            $model_pd->rollback();
            output_error($e->getMessage() ? $e->getMessage() : '网络出错');
        }
    }




    /**
     * 申请提现
     */
    public function pd_cash_addOp(){
        if($this->member_info['grade_id'] != 1){
            output_error('非VIP会员不能提现');
        }
        if(empty($this->member_info['member_idcard']) || empty($this->member_info['member_truename'])){
            output_error('必须填写身份证号和姓名');
        }
        $card_id = $_POST['card_id'];
        if(empty($card_id)){
            output_error('请选择提现的银行卡');
        }

        $member_card = Model('member_bank')->getBankInfo(array('id'=>$card_id,'member_id'=>$this->member_info['member_id']));
        if(empty($member_card)){
            output_error('请先绑定银行卡');
        }


        $obj_validate = new Validate();
        $pdc_amount = round(floatval($_POST['pdc_amount']),2);
        $validate_arr[] = array("input"=>$_POST["password"], "require"=>"true","message"=>'请输入支付密码');
        $obj_validate -> validateparam = $validate_arr;
        $error = $obj_validate->validate();
        if ($error != ''){
            output_error($error);
        }

        if($pdc_amount < 100){
            output_error('最少提现100元');
        }

        if($member_card['account_name'] != $this->member_info['member_truename']){
            output_error('仅能提现至本人的银行卡');
        }

        $model_pd = Model('predeposit');
        $model_member = Model('member');
        $member_info = $model_member->getMemberInfoByID($this->member_info['member_id']);

        //验证支付密码
        if (md5($_POST['password']) != $member_info['member_paypwd']) {
            output_error('支付密码错误');
        }
        if($this->member_info['available_predeposit'] < 100){
            output_error('余额不足100元，不能申请提现！');
        }
        if($this->member_info['available_predeposit'] < $pdc_amount){
            output_error('余额不足，请先充值');
        }

        try {
            $pdc_amount_rate = 0.03;
            if($this->member_info['is_store']){
                $pdc_amount_rate = 0;
            }
            $model_pd->beginTransaction();
            $pdc_sn = $model_pd->makeSn();
            $data = array();
            $data['pdc_sn'] = $pdc_sn;
            $data['pdc_member_id'] = $this->member_info['member_id'];
            $data['pdc_member_name'] = $this->member_info['member_name'];
            $data['pdc_bank_name'] = $member_card['bank_name'] . '<br/>支行:'.$member_card['province_name'] . '-' . $member_card['city_name'] . '-' . $member_card['branch_name'];
            $data['pdc_bank_no'] = $member_card['account_no'];
            $data['pdc_bank_user'] = $member_card['account_name'];
            $data['pdc_add_time'] = TIMESTAMP;
            $data['pdc_payment_state'] = 0;
            $data['pdc_amount'] = $pdc_amount;
            $data['pdc_amount_rate'] = $pdc_amount_rate;
            $data['pdc_amount_out'] = $pdc_amount * $data['pdc_amount_rate'];
            $data['pdc_amount_get'] = round($pdc_amount - $data['pdc_amount_out'],2);
            $insert = $model_pd->addPdCash($data);

            if (!$insert) {
                output_error('提现申请失败');
            }
            //冻结可用帐户余额
            $data = array();
            $data['member_id'] = $member_info['member_id'];
            $data['member_name'] = $member_info['member_name'];
            $data['amount'] = $pdc_amount;
            $data['order_sn'] = $pdc_sn;
            $model_pd->changePd('cash_apply',$data);

            $model_pd->commit();
            output_data(array('pdc_amount'=>$pdc_amount));
        } catch (Exception $e) {
            $model_pd->rollback();
            output_error($e->getMessage());
        }
    }

    /**
     * 帐户余额变更日志
     */
    public function pd_log_listOp(){
        $lg_id = intval($_REQUEST ['lg_id']);
        $model_pd = Model('predeposit');
        $condition = array();
        $condition['lg_member_id'] = $this->member_info['member_id'];
        if($lg_id>0){
            $condition['lg_id'] = $lg_id;
        }

        $list = $model_pd->getPdLogList($condition,$this->page,'*','lg_id desc');
        if($list){
            foreach ($list as $key=>$val){
                $lg_type_name = '';
                $list[$key]['color'] = 'red';
                $list[$key]['title'] = '入帐金额';
                switch ($val['lg_type']){
                    case 'oil':
                        $lg_type_name = '油卡充值';
                        $list[$key]['color'] = 'green';
                        $list[$key]['title'] = '出帐金额';
                        break;
                    case 'oil_card':
                        $lg_type_name = '购买油卡';
                        $list[$key]['color'] = 'green';
                        $list[$key]['title'] = '出帐金额';
                        break;
                    case 'upgrade':
                        $lg_type_name = '会员升级';
                        $list[$key]['color'] = 'green';
                        $list[$key]['title'] = '出帐金额';
                        break;
                    case 'recharge':
                        $lg_type_name = '充值';
                        break;
                    case 'transfer':
                        $lg_type_name = '转帐';
                        if($val['lg_av_amount'] < 0){
                            $list[$key]['color'] = 'green';
                            $list[$key]['title'] = '出帐金额';
                        }
                        break;
                    case 'cash_apply':
                        $lg_type_name = '申请提现冻结帐户余额';
                        $list[$key]['color'] = 'blue';
                        $list[$key]['title'] = '出帐金额';
                        break;
                    case 'cash_pay':
                        $lg_type_name = '提现成功';
                        $list[$key]['color'] = 'green';
                        $list[$key]['title'] = '出帐金额';
                        break;
                    case 'cash_del':
                        $lg_type_name = '取消提现申请,解冻帐户余额';
                        break;
                    case 'redeemable':
                        $lg_type_name = '现金返还';
                        break;
                }
                $list[$key]['lg_type_name'] = $lg_type_name;
                $list[$key]['add_time'] = date('Y-m-d H:i:s',$val['lg_add_time']);

                unset($list[$key]['lg_add_time']);
                unset($list[$key]['lg_type']);
                unset($list[$key]['lg_freeze_amount']);
                unset($list[$key]['lg_admin_name']);
                unset($list[$key]['lg_member_id']);
                unset($list[$key]['lg_member_name']);

                if($val['lg_av_amount'] > 0){
                    $list[$key]['lg_av_amount'] = '+' . $val['lg_av_amount'];
                }
            }
        }

        $page_count = $model_pd->gettotalpage();
        output_data(array('pd_log_list' => $list), mobile_page($page_count));
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
