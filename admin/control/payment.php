<?php
/**
 * 支付方式
 *
 *
 *
 ***/

defined('InSystem') or exit('Access Invalid!');
class paymentControl extends SystemControl{
    public function __construct(){
        parent::__construct();
    }

    public function indexOp() {
        $this->payment_listOp();
    }

    public function payment_listOp() {
        $model_payment = Model('payment');
        $payment_list = $model_payment->getPaymentList();
        Tpl::output('payment_list', $payment_list);
        Tpl::showpage('payment.list');
    }

    /**
     * 编辑
     */
    public function payment_editOp() {
        $payment_id = intval($_GET["payment_id"]);

        $model_payment = Model('payment');

        $payment_info = $model_payment->getPaymentInfo(array('payment_id' => $payment_id));
        Tpl::output('payment', $payment_info);
        Tpl::showpage('payment.edit');
    }

    /**
     * 编辑保存
     */
    public function payment_saveOp() {
        $payment_id = intval($_POST["payment_id"]);

        $data = array();
        $data['payment_state'] = intval($_POST["payment_state"]);

        switch ($_POST['payment_code']) {
            case 'alipay':
                $payment_config = array(
                    'alipay_account' => $_POST['alipay_account'],
                    'alipay_key' => $_POST['alipay_key'],
                    'alipay_partner' => $_POST['alipay_partner'],
                );
                break;
            case 'wxpay':
                $payment_config = array(
                    'wxpay_appid' => $_POST['wxpay_appid'],
                    'wxpay_mch_id' => $_POST['wxpay_mch_id'],
                    'wxpay_appsecret' => $_POST['wxpay_appsecret'],
                    'wxpay_key'=>$_POST['wxpay_key']
                );
                break;
            default:
                showMessage(L('param_error'), '');
        }
        $data['payment_config'] = $payment_config;

        $model_payment = Model('payment');

        $result = $model_payment->editPayment($data, array('payment_id' => $payment_id));
        if($result) {
            showMessage(Language::get('nc_common_save_succ'), urlAdmin('payment', 'payment_list'));
        } else {
            showMessage(Language::get('nc_common_save_fail'), urlAdmin('payment', 'payment_list'));
        }
    }
}
