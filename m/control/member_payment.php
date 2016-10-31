<?php
/**
 * 支付
 *
 *
 *
 */

defined('InSystem') or exit('Access Invalid!');

class member_paymentControl extends mobileMemberControl {

    private $payment_code = 'alipay';

    public function __construct() {
        parent::__construct();
        $this->payment_code = isset($_GET['payment_code']) && trim($_GET['payment_code']) != '' ? trim($_GET['payment_code']) :'alipay';
    }

    /**
     * 帐户余额充值
     */
    public function pd_orderOp(){
        $pdr_sn = $_GET['pdr_sn'] ? $_GET['pdr_sn'] : $_POST['pdr_sn'];

        if(!preg_match('/^\d{18}$/',$pdr_sn)){
            output_error('参数错误');
        }
        $model_payment = Model('payment');
        $logic_payment = Logic('payment');

        $condition = array();
        $condition['payment_code'] = $this->payment_code;
        $payment_info = $model_payment->getPaymentOpenInfo($condition);
        if(!$payment_info) {
            output_error('系统不支持选定的支付方式');
        }
        $result = $logic_payment->getPdOrderInfo($pdr_sn,$this->member_info['member_id']);
        if(!$result['state']) {
            output_error($result['msg']);
        }
        if ($result['data']['pdr_payment_state'] || empty($result['data']['api_pay_amount'])) {
            output_error('该充值单不需要支付');
        }
        $result['data']['order_type'] = 'p';
        //转到第三方API支付
        $this->_api_pay($result['data'], $payment_info);
    }

    /**
     * 第三方在线支付接口
     *
     */
    private function _api_pay($order_pay_info, $payment_info) {
        $inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$this->payment_code.DS.$this->payment_code.'.php';
        if(!is_file($inc_file)){
            output_error('支付接口不存在');
        }
        require_once($inc_file);
        $param = $payment_info['payment_config'];
        $param['order_sn'] = $order_pay_info['pay_sn'];
        $param['order_amount'] = $order_pay_info['api_pay_amount'];
        $param['order_type'] = $order_pay_info['order_type'];

        $payment_api = new $this->payment_code();
        if($_GET['client'] == 'app'){
            $payment_api->prePay($param);
            exit;
        }else{
            $return = $payment_api->submit($param);
            echo $return;
            exit;
        }
    }

    /**
     * 可用支付参数列表
     */
    public function payment_listOp() {
        $model_payment = Model('payment');

        $payment_list = $model_payment->getPaymentOpenList();

        $payment_array = array();
        if(!empty($payment_list)) {
            foreach ($payment_list as $value) {
                $payment_array[] = array('payment_code' => $value['payment_code'],'payment_name' =>$value['payment_name']);
            }
        }

        output_data(array('payment_list' => $payment_array));
    }

}
