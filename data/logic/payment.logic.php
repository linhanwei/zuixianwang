<?php
/**
 * 支付行为
 *
 *
 */
defined('InSystem') or exit('Access Invalid!');
class paymentLogic {
    /**
     * 取得升级单所需支付金额等信息
     * @param int $pdr_sn
     * @param int $member_id
     * @return array
     */
    public function getUpgradeInfo($pu_sn, $member_id = null) {

        $model_pd = Model('predeposit');
        $condition = array();
        $condition['pu_sn'] = $pu_sn;
        if (!empty($member_id)) {
            $condition['pu_member_id'] = $member_id;
        }

        $upgrade_info = $model_pd->getPdRechargeInfo($condition);
        if(empty($upgrade_info)){
            return callback(false,'该订单不存在');
        }

        $upgrade_info['subject'] = '升级_'.$upgrade_info['pu_sn'];
        $upgrade_info['order_type'] = 'upgrade_order';
        $upgrade_info['pay_sn'] = $upgrade_info['pu_sn'];
        $upgrade_info['api_pay_amount'] = $upgrade_info['pu_amount'];
        return callback(true,'',$upgrade_info);
    }
    /**
     * 取得充值单所需支付金额等信息
     * @param int $pdr_sn
     * @param int $member_id
     * @return array
     */
    public function getPdOrderInfo($pdr_sn, $member_id = null) {

        $model_pd = Model('predeposit');
        $condition = array();
        $condition['pdr_sn'] = $pdr_sn;
        if (!empty($member_id)) {
            $condition['pdr_member_id'] = $member_id;
        }

        $order_info = $model_pd->getPdRechargeInfo($condition);
        if(empty($order_info)){
            return callback(false,'该订单不存在');
        }

        $order_info['subject'] = '充值_'.$order_info['pdr_sn'];
        $order_info['order_type'] = 'pd_order';
        $order_info['pay_sn'] = $order_info['pdr_sn'];
        $order_info['api_pay_amount'] = $order_info['pdr_amount'];
        return callback(true,'',$order_info);
    }
    /**
     * 支付成功后修改充值订单状态
     * @param unknown $out_trade_no
     * @param unknown $trade_no
     * @param unknown $payment_info
     * @param array $recharge_info
     * @throws Exception
     * @return multitype:unknown
     */
    public function updatePdOrder($out_trade_no,$trade_no,$payment_info,$recharge_info) {

        $condition = array();
        $condition['pdr_sn'] = $out_trade_no;
        $condition['pdr_payment_state'] = 0;
        $update = array();
        $update['pdr_payment_state'] = 1;
        $update['pdr_payment_time'] = TIMESTAMP;
        $update['pdr_payment_code'] = $payment_info['payment_code'];
        $update['pdr_payment_name'] = $payment_info['payment_name'];
        $update['pdr_trade_sn'] = $trade_no;

        $model_pd = Model('predeposit');
        try {
            $model_pd->beginTransaction();
            $pdnum=$model_pd->getPdRechargeCount(array('pdr_sn'=>$out_trade_no,'pdr_payment_state'=>1));
            if (intval($pdnum)>0) {
                throw new Exception('订单已经处理');
            }
            //更改充值状态
            $state = $model_pd->editPdRecharge($update,$condition);
            if (!$state) {
                throw new Exception('更新充值状态失败');
            }
            //变更会员帐户余额
            $data = array();
            $data['member_id'] = $recharge_info['pdr_member_id'];
            $data['member_name'] = $recharge_info['pdr_member_name'];
            $data['amount'] = $recharge_info['pdr_amount'];
            $data['pdr_sn'] = $recharge_info['pdr_sn'];
            $model_pd->changePd('recharge',$data);
            $model_pd->commit();
            return callback(true);

        } catch (Exception $e) {
            $model_pd->rollback();
            return callback(false,$e->getMessage());
        }
    }

    /**
     * 支付成功后修改充值订单状态
     * @param unknown $out_trade_no
     * @param unknown $trade_no
     * @param unknown $payment_info
     * @param array $recharge_info
     * @throws Exception
     * @return multitype:unknown
     */
    public function updateUpgrade($out_trade_no,$trade_no,$payment_info,$recharge_info){
        $condition = array();
        $condition['pu_sn'] = $out_trade_no;
        $condition['pu_payment_state'] = 0;
        $update = array();
        $update['pu_payment_state'] = 1;
        $update['pu_payment_time'] = TIMESTAMP;
        $update['pu_payment_code'] = $payment_info['payment_code'];
        $update['pu_payment_name'] = $payment_info['payment_name'];
        $update['pu_trade_sn'] = $trade_no;

        $model_pd = Model('predeposit');
        try {
            $model_pd->beginTransaction();
            $pdnum=$model_pd->getUpgradeCount(array('pu_sn'=>$out_trade_no,'pu_payment_state'=>1));
            if (intval($pdnum)>0) {
                throw new Exception('已经处理');
            }
            //更改充值状态
            $state = $model_pd->editUpgrade($update,$condition);
            if (!$state) {
                throw new Exception('更新升级状态失败');
            }
            //变更会员帐户余额
            $data = array();
            $data['member_id'] = $recharge_info['pu_member_id'];
            $data['member_name'] = $recharge_info['pu_member_name'];
            $data['amount'] = $recharge_info['pu_amount'];
            $data['pu_sn'] = $recharge_info['pu_sn'];
            $model_pd->changePd('upgrade',$data);

            $grade_info = array();
            $grade_info['grade_id'] = 1;
            $grade_info['grade_name'] = 'VIP用户';
            $grade_info['price'] = $recharge_info['pu_amount'];
            Logic('upgrade')->upgrade($this->member_info,$grade_info,$recharge_info['pu_sn']);

            $model_pd->commit();
            return callback(true);

        } catch (Exception $e) {
            $model_pd->rollback();
            return callback(false,$e->getMessage());
        }
    }

    /**
     * 取得所使用支付方式信息
     * @param unknown $payment_code
     */
    public function getPaymentInfo($payment_code) {
        if (!in_array($payment_code,array('alipay','wxpay')) || empty($payment_code)) {
            return callback(false,'系统不支持选定的支付方式');
        }
        $model_payment = Model('payment');
        $condition = array();
        $condition['payment_code'] = $payment_code;
        $payment_info = $model_payment->getPaymentOpenInfo($condition);
        if(empty($payment_info)) {
            return callback(false,'系统不支持选定的支付方式');
        }

        $inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$payment_info['payment_code'].DS.$payment_info['payment_code'].'.php';


        if(!file_exists($inc_file)){
            return callback(false,'系统不支持选定的支付方式');
        }
        include_once($inc_file);
        return callback(true,'',$payment_info);
    }
}