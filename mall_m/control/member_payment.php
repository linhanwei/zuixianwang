<?php
/**
 * 支付
 *
 *
 *
 *
 */


defined('InSystem') or exit('Access Invalid!');

class member_paymentControl extends mobileMemberControl {

    private $payment_code;
    private $payment_config;

    public function __construct() {
        parent::__construct();

        if($_GET['op'] != 'payment_list' && $_POST['payment_code']) {
            $payment_code = $_POST['payment_code'];

            $model_mb_payment = Model('mb_payment');
            $condition = array();
            $condition['payment_code'] = $payment_code;
            $mb_payment_info = $model_mb_payment->getMbPaymentOpenInfo($condition);
            if(!$mb_payment_info) {
                output_error('支付方式未开启');
            }

            $this->payment_code = $payment_code;
            $this->payment_config = $mb_payment_info['payment_config'];

        }
    }

    /**
     * 实物订单支付 新方法
     */
    public function pay_newOp() {
        @header("Content-type: text/html; charset=".CHARSET);
        $pay_sn = $_GET['pay_sn'];
        if (!preg_match('/^\d{18}$/',$pay_sn)){
            exit('支付单号错误');
        }
        if (in_array($_GET['payment_code'],array('alipay','wxpay_jsapi'))) {
            $model_mb_payment = Model('mb_payment');
            $condition = array();
            $condition['payment_code'] = $_GET['payment_code'];
            $mb_payment_info = $model_mb_payment->getMbPaymentOpenInfo($condition);
            if(!$mb_payment_info) {
                exit('支付方式未开启');
            }

            $this->payment_code = $_GET['payment_code'];
            $this->payment_config = $mb_payment_info['payment_config'];
        } else {
            exit('支付方式提交错误');
        }

        $pay_info = $this->_get_real_order_info($pay_sn,$_GET);
        if(isset($pay_info['error'])) {
            exit($pay_info['error']);
        }

        //第三方API支付
        $this->_api_pay($pay_info['data']);

    }

    /**
     * 虚拟订单支付 新方法
     */
    public function vr_pay_newOp() {
        @header("Content-type: text/html; charset=".CHARSET);
        $order_sn = $_GET['pay_sn'];
        if(!preg_match('/^\d{18}$/',$order_sn)){
            exit('订单号错误');
        }
        if (in_array($_GET['payment_code'],array('alipay','wxpay_jsapi'))) {
            $model_mb_payment = Model('mb_payment');
            $condition = array();
            $condition['payment_code'] = $_GET['payment_code'];
            $mb_payment_info = $model_mb_payment->getMbPaymentOpenInfo($condition);
            if(!$mb_payment_info) {
                exit('支付方式未开启');
            }

            $this->payment_code = $_GET['payment_code'];
            $this->payment_config = $mb_payment_info['payment_config'];
        } else {
            exit('支付方式提交错误');
        }

        $pay_info = $this->_get_vr_order_info($order_sn,$_GET);
        if(isset($pay_info['error'])) {
            exit($pay_info['error']);
        }

        //第三方API支付
        $this->_api_pay($pay_info['data']);

    }

    /**
     * 站内余额支付(充值卡、预存款支付) 实物订单
     *
     */
    private function _pd_pay($order_list, $post) {
        if (empty($post['password'])) {
            return $order_list;
        }
        $model_member = Model('member');
        $buyer_info = $model_member->getMemberInfoByID($this->member_info['member_id']);
        if ($buyer_info['member_paypwd'] == '' || $buyer_info['member_paypwd'] != md5($post['password'])) {
            return $order_list;
        }

        if ($buyer_info['available_rc_balance'] == 0) {
            $post['rcb_pay'] = null;
        }
        if ($buyer_info['available_predeposit'] == 0) {
            $post['pd_pay'] = null;
        }
        if (floatval($order_list[0]['rcb_amount']) > 0 || floatval($order_list[0]['pd_amount']) > 0) {
            return $order_list;
        }

        try {
            $model_member->beginTransaction();
            $logic_buy_1 = Logic('buy_1');
            //使用充值卡支付
            if (!empty($post['rcb_pay'])) {
                $order_list = $logic_buy_1->rcbPay($order_list, $post, $buyer_info);
            }

            //使用预存款支付
            if (!empty($post['pd_pay'])) {
                $order_list = $logic_buy_1->pdPay($order_list, $post, $buyer_info);
            }

            //特殊订单站内支付处理
            $logic_buy_1->extendInPay($order_list);

            $model_member->commit();
        } catch (Exception $e) {
            $model_member->rollback();
            exit($e->getMessage());
        }

        return $order_list;
    }

    /**
     * 站内余额支付(充值卡、预存款支付) 虚拟订单
     *
     */
    private function _pd_vr_pay($order_info, $post) {
        if (empty($post['password'])) {
            return $order_info;
        }
        $model_member = Model('member');
        $buyer_info = $model_member->getMemberInfoByID($this->member_info['member_id']);
        if ($buyer_info['member_paypwd'] == '' || $buyer_info['member_paypwd'] != md5($post['password'])) {
            return $order_info;
        }
        if ($buyer_info['available_rc_balance'] == 0) {
            $post['rcb_pay'] = null;
        }
        if ($buyer_info['available_predeposit'] == 0) {
            $post['pd_pay'] = null;
        }
        if (floatval($order_info['rcb_amount']) > 0 || floatval($order_info['pd_amount']) > 0) {
            return $order_info;
        }

        try {
            $model_member->beginTransaction();
            $logic_buy = Logic('buy_virtual');
            //使用充值卡支付
            if (!empty($post['rcb_pay'])) {
                $order_info = $logic_buy->rcbPay($order_info, $post, $buyer_info);
            }

            //使用预存款支付
            if (!empty($post['pd_pay'])) {
                $order_info = $logic_buy->_pdPay($order_info, $post, $buyer_info);
            }

            $model_member->commit();
        } catch (Exception $e) {
            $model_member->rollback();
            exit($e->getMessage());
        }

        return $order_info;
    }

    /**
     * 实物订单支付
     */
    public function payOp() {
        $pay_sn = $_GET['pay_sn'];

        $pay_info = $this->_get_real_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }

        //第三方API支付
        $this->_api_pay($pay_info['data']);
    }

    /**
     * 虚拟订单支付
     */
    public function vr_payOp() {
        $pay_sn = $_GET['pay_sn'];

        $pay_info = $this->_get_vr_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }

        //第三方API支付
        $this->_api_pay($pay_info['data']);
    }

    /**
     * 第三方在线支付接口
     *
     */
    private function _api_pay($order_pay_info) {
        $inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$this->payment_code.DS.$this->payment_code.'.php';
        if(!is_file($inc_file)){
            exit('支付接口不存在');
        }
        require($inc_file);
        $param = $this->payment_config;

        // wxpay_jsapi
        if ($this->payment_code == 'wxpay_jsapi') {
            $param['orderSn'] = $order_pay_info['pay_sn'];
            $param['orderFee'] = (int) (100 * $order_pay_info['api_pay_amount']);
            $param['orderInfo'] = C('site_name') . '商品订单' . $order_pay_info['pay_sn'];
            $param['orderAttach'] = ($order_pay_info['order_type'] == 'real_order' ? 'r' : 'v');
            $api = new wxpay_jsapi();
            $api->setConfigs($param);
            try {
                echo $api->paymentHtml($this);
            } catch (Exception $ex) {
                if (C('debug')) {
                    header('Content-type: text/plain; charset=utf-8');
                    echo $ex, PHP_EOL;
                } else {
                    Tpl::output('msg', $ex->getMessage());
                    Tpl::showpage('payment_result');
                }
            }
            exit;
        }

        $param['order_sn'] = $order_pay_info['pay_sn'];
        $param['order_amount'] = $order_pay_info['api_pay_amount'];
        $param['order_type'] = ($order_pay_info['order_type'] == 'real_order' ? 'r' : 'v');
        $payment_api = new $this->payment_code();
        $return = $payment_api->submit($param);
        echo $return;
        exit;
    }

    /**
     * 获取订单支付信息
     */
    private function _get_real_order_info($pay_sn,$rcb_pd_pay = array()) {
        $logic_payment = Logic('payment');

        //取订单信息
        $result = $logic_payment->getRealOrderInfo($pay_sn, $this->member_info['member_id']);
        if(!$result['state']) {
            return array('error' => $result['msg']);
        }

        //站内余额支付
        if ($rcb_pd_pay) {
            $result['data']['order_list'] = $this->_pd_pay($result['data']['order_list'],$rcb_pd_pay);
        }

        //计算本次需要在线支付的订单总金额
        $pay_amount = 0;
        $pay_order_id_list = array();
        if (!empty($result['data']['order_list'])) {
            foreach ($result['data']['order_list'] as $order_info) {
                if ($order_info['order_state'] == ORDER_STATE_NEW) {
                    $pay_amount += $order_info['order_amount'] - $order_info['pd_amount'] - $order_info['rcb_amount'];
                    $pay_order_id_list[] = $order_info['order_id'];
                }
            }
        }

        if ($pay_amount == 0) {
            redirect(WAP_SITE_URL.'/tmpl/member/order_list.html');
        }

        $result['data']['api_pay_amount'] = ncPriceFormat($pay_amount);
        //临时注释
        //$update = Model('order')->editOrder(array('api_pay_time'=>TIMESTAMP),array('order_id'=>array('in',$pay_order_id_list)));
        //if(!$update) {
        //       return array('error' => '更新订单信息发生错误，请重新支付');
        //    }

        //如果是开始支付尾款，则把支付单表重置了未支付状态，因为支付接口通知时需要判断这个状态
        if ($result['data']['if_buyer_repay']) {
            $update = Model('order')->editOrderPay(array('api_pay_state'=>0),array('pay_id'=>$result['data']['pay_id']));
            if (!$update) {
                return array('error' => '订单支付失败');
            }
            $result['data']['api_pay_state'] = 0;
        }

        return $result;
    }

    /**
     * 获取虚拟订单支付信息
     */
    private function _get_vr_order_info($pay_sn,$rcb_pd_pay = array()) {
        $logic_payment = Logic('payment');

        //取得订单信息
        $result = $logic_payment->getVrOrderInfo($pay_sn, $this->member_info['member_id']);
        if(!$result['state']) {
            output_error($result['msg']);
        }

        //站内余额支付
        if ($rcb_pd_pay) {
            $result['data'] = $this->_pd_vr_pay($result['data'],$rcb_pd_pay);
        }
        //计算本次需要在线支付的订单总金额
        $pay_amount = 0;
        if ($result['data']['order_state'] == ORDER_STATE_NEW) {
            $pay_amount += $result['data']['order_amount'] - $result['data']['pd_amount'] - $result['data']['rcb_amount'];
        }

        if ($pay_amount == 0) {
            redirect(WAP_SITE_URL.'/tmpl/member/vr_order_list.html');
        }

        $result['data']['api_pay_amount'] = ncPriceFormat($pay_amount);
        //临时注释
        //$update = Model('order')->editOrder(array('api_pay_time'=>TIMESTAMP),array('order_id'=>$result['data']['order_id']));
        //if(!$update) {
        //    return array('error' => '更新订单信息发生错误，请重新支付');
        //}

        //计算本次需要在线支付的订单总金额
        $pay_amount = $result['data']['order_amount'] - $result['data']['pd_amount'] - $result['data']['rcb_amount'];
        $result['data']['api_pay_amount'] = ncPriceFormat($pay_amount);

        return $result;
    }

    /**
     * 可用支付参数列表
     */
    public function payment_listOp() {
        $model_mb_payment = Model('mb_payment');

        $payment_list = $model_mb_payment->getMbPaymentOpenList();

        $payment_array = array();
        if(!empty($payment_list)) {
            foreach ($payment_list as $value) {
                $payment_array[] = $value['payment_code'];
            }
        }

        output_data(array('payment_list' => $payment_array));
    }


    /**
     * 取得支付宝移动支付 订单信息 实物订单
     */
    public function app_order_payOp() {
        $pay_sn = $_POST['pay_sn'];
        if (!preg_match('/^\d+$/',$pay_sn)){
            output_error('支付单号错误');
        }
        $pay_info = $this->_get_real_order_info($pay_sn);

        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }
        $model_payment = Model('payment');

        //读取接口配置信息
        $condition = array();
        $condition['payment_code'] = $this->payment_code;
        $payment_info = $model_payment->getPaymentOpenInfo($condition);

        $order_type = 'r';
        switch($pay_info['data']['order_type']){
            default :
                $order_type = 'r';
                break;
        }
        output_data(array('title'=>'订单:' . $pay_info['data']['pay_sn'],'total'=>$pay_info['data']['api_pay_amount'],'sn'=>$order_type . $pay_info['data']['pay_sn'],'payment_info'=>$payment_info['payment_config'],'notify_url'=>MOBILE_SITE_URL . '/api/payment/' . $this->payment_code . '/notify_url.php'));
    }


}
