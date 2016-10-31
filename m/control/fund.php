<?php
defined('InSystem') or exit('Access Invalid!');
class fundControl extends mobileMemberControl{

	public function __construct() {
        parent::__construct();
    }


    public function indexOp($fund_id = 1){
        $model_fund = Model('fund');

        $condition = array();
        $condition['fund_id'] = $fund_id;
        $fund = $model_fund->getOneById($fund_id);

        Tpl::output('html_title',$fund['fund_name']);
        Tpl::output('fund',$fund);
        Tpl::showpage('fund');
    }

    /*
     * 捐赠
     */
    public function donateOp() {
        $fl_amount = $_POST['amount'];
        $payment_code = $_POST['payment_code'];
        $password = trim($_POST['password']);

        if($fl_amount<0 || empty($fl_amount)){
            output_data('请输入正确的金额');
        }

        if(empty($password)){
            output_error('请输入支付密码');
        }

        if(!in_array($payment_code,array('predeposit'))){
            output_error('请输入正确的支付方式');
        }
        if($fl_amount > $this->member_info['available_predeposit']){
            output_error('您的账户余额不够,请先充值!');
        }

        $model_member = Model('member');
        $member_pass_info = $model_member->getMemberInfoByID($this->member_info['member_id']);
        if($member_pass_info['member_paypwd'] != md5($password)){
            output_error('支付密码不正确');
        }

        $model_fl = Model('fund_log');
        $model_pd = Model('predeposit');

        $data = array();
        $pay_sn = '';

        $data['fl_sn'] = $model_pd->makeSn($this->member_info['member_id']);
        $data['fl_member_id'] = $this->member_info['member_id'];
        $data['fund_id'] = $fund_id = $_POST['fund_id'] ? $_POST['fund_id'] : 1;
        $data['fl_member_name'] = $this->member_info['member_name'];
        $data['fl_amount'] = $fl_amount;
        $data['fl_add_time'] = TIMESTAMP;
        $insert = $model_fl->addFundLog($data);
        if($insert){
            $pay_sn = $data['fl_sn'];
        }

        if ($pay_sn) {
            //余额支付
            if($payment_code == 'predeposit'){

                $model_pd->beginTransaction();
                $fl_sn = $model_pd->makeSn($this->member_info['member_id']);
                //减余额
                $data = array();
                $data['member_id'] = $this->member_info['member_id'];
                $data['member_name'] = $this->member_info['member_name'];
                $data['amount'] = $fl_amount;
                $data['fl_sn'] = $fl_sn;

                try{
                    if($model_pd->changePd('fund',$data)){
                        $update = array();
                        $update['fl_payment_state'] = 1;
                        $update['fl_payment_time'] = TIMESTAMP;
                        $update['fl_payment_code'] = 'predeposit';
                        $update['fl_payment_name'] = '余额支付';
                        $update['fl_trade_sn'] = $fl_sn;
                        $update['fl_state'] = 1;
                        $update = $model_fl->editFundLog($update,array('fl_sn'=>$pay_sn));

                        if($update){
                            $data_fund = array();
                            $model_fund = Model('fund');
                            $fund	= $model_fund->getOneById(intval($fund_id));
                            $data_fund['fund_id'] = $fund['fund_id'];
                            $data_fund['fund_raise'] = $fund['fund_raise'] + $fl_amount;
                            $data_fund['fund_love'] = $fund['fund_love'] + 1;
                            $model_fund->update($data_fund);
                            $model_pd->commit();
                            output_data(array('fl_sn'=>$fl_sn));
                        }else{
                            $model_pd->rollback();
                            output_error('捐赠失败');
                        }
                    }
                }catch (Exception $ex){
                    $model_pd->rollback();
                    output_error('捐赠失败:'.$ex->getMessage());
                }

            }
        }
    }

}
