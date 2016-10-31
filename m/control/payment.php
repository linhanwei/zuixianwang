<?php
/**
 * 支付回调
 *
 *
 *
 *
 */

defined('InSystem') or exit('Access Invalid!');

class paymentControl extends mobileHomeControl{
    private $payment_code;

    public function __construct() {
        parent::__construct();

        $this->payment_code = $_GET['payment_code'];
    }

    public function returnopenidOp(){
        $payment_api = $this->_get_payment_api();
        if($this->payment_code != 'wxpay'){
            output_error('支付参数异常');
            die;
        }

        $payment_api->getopenid();

    }

    /**
     * 支付回调
     */
    public function returnOp() {
        unset($_GET['act']);
        unset($_GET['op']);
        unset($_GET['payment_code']);

        $payment_api = $this->_get_payment_api();

        $payment_config = $this->_get_payment_config();

        $callback_info = $payment_api->getReturnInfo($payment_config);

        if($callback_info) {
            //验证成功
            $result = $this->_update_order($callback_info['out_trade_no'],$callback_info['order_type'], $callback_info['trade_no'], $callback_info['total_fee']);
            if($result['state']) {
                Tpl::output('result', 'success');
                Tpl::output('message', '支付成功');
            } else {
                Tpl::output('result', 'fail');
                Tpl::output('message', '支付失败');
            }
        } else {
            //验证失败
            Tpl::output('result', 'fail');
            Tpl::output('message', '支付失败');
        }
        Tpl::output('order_type', $callback_info['order_type']);
        Tpl::showpage('payment_message');
    }

    /**
     * 支付提醒
     */
    public function notifyOp() {
        // 恢复框架编码的post值
        if(isset($_POST['notify_data'])){
            $_POST['notify_data'] = html_entity_decode($_POST['notify_data']);
        }

        $payment_api = $this->_get_payment_api();

        $payment_config = $this->_get_payment_config();

        $callback_info = $payment_api->getNotifyInfo($payment_config);

        /*$callback_info['out_trade_no'] = $_POST['out_trade_no'];
        $callback_info['order_type'] = 'p';
        $callback_info['total_fee'] = $_POST['total_fee'];
        $callback_info['trade_no'] = '329832983283028320';*/

        //log::record(json_encode($callback_info));
        if($callback_info['out_trade_no'] && $callback_info['total_fee'] >= 0.01) {
            //验证成功
            $result = $this->_update_order($callback_info['out_trade_no'],$callback_info['order_type'], $callback_info['trade_no'],$callback_info['total_fee']);
            if($result['state']) {
                if($this->payment_code == 'wxpay'){
                    echo $callback_info['returnXml'];
                    die;
                }else{
                    echo 'success';die;
                }

            }
        }

        //验证失败

        if($this->payment_code == 'wxpay'){
            echo '<xml><return_code><!--[CDATA[FAIL]]--></return_code></xml>';
            die;
        }else{
            echo "fail";die;
        }
    }

    /**
     * 获取支付接口实例
     */
    private function _get_payment_api() {
        $inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$this->payment_code.DS.$this->payment_code.'.php';

        if(is_file($inc_file)) {
            require($inc_file);
        }

        $payment_api = new $this->payment_code();

        return $payment_api;
    }

    /**
     * 获取支付接口信息
     */
    private function _get_payment_config() {
        $model_payment = Model('payment');

        //读取接口配置信息
        $condition = array();
        $condition['payment_code'] = $this->payment_code;
        $payment_info = $model_payment->getPaymentOpenInfo($condition);

        return $payment_info['payment_config'];
    }

    /**
     * @param $out_trade_no
     * @param $order_type
     * @param $trade_no
     * @param $total_fee
     * @return array
     */
    private function _update_order($out_trade_no,$order_type, $trade_no,$total_fee) {
        $logic_payment = Logic('payment');

        if($order_type == 'p'){
            $result = $logic_payment->getPdOrderInfo($out_trade_no);
        }elseif($order_type == 'u'){
            $result = $logic_payment->getUpgradeInfo($out_trade_no);
        }


        if ($result['data']['pdr_payment_state'] == 1) {
            return array('state'=>true);
        }

        if($result['data']['pdr_amount'] != $total_fee){
            return array('state'=>false);
        }

        //取得支付方式
        $payment_info = array();

        $payment_result = $logic_payment->getPaymentInfo($this->payment_code);
        $payment_info['payment_code'] = $payment_result['data']['payment_code'];
        $payment_info['payment_name'] = $payment_result['data']['payment_name'];

        $order_pay_info = $result['data'];

        switch($order_type){
            case 'p':
                $result = $logic_payment->updatePdOrder($out_trade_no, $trade_no, $payment_info, $order_pay_info);
                $result['state'] = true;
                break;
            case 'u':
                $result = $logic_payment->updateUpgrade($out_trade_no, $trade_no, $payment_info, $order_pay_info);
                $result['state'] = true;
                break;
        }


        return $result;
    }


}
