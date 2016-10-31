<?php
/**
 * 支付入口
 *
 *
 **by 好商城V3 www.33hao.com 运营版*/


defined('InSystem') or exit('Access Invalid!');

class paymentControl extends ClientControl{

    public function __construct() {
        if($_GET['op'] == 'notify'){
            parent::__construct(false);
        }else{
            parent::__construct();
        }
    }



	/**
	 * 预存款充值
	 */
	public function pd_orderOp(){
	    $pdr_sn = $_POST['pdr_sn'];
	    $payment_code = $_POST['payment_code'];
	    $url = 'index.php?act=predeposit';

	    if(!preg_match('/^\d{18}$/',$pdr_sn)){
	        showMessage('参数错误',$url,'html','error');
	    }

	    $logic_payment = Logic('payment');
	    $result = $logic_payment->getPaymentInfo($payment_code);
	    if(!$result['state']) {
	        showMessage($result['msg'], $url, 'html', 'error');
	    }
	    $payment_info = $result['data'];

        $result = $logic_payment->getPdOrderInfo($pdr_sn,$_SESSION['member_id']);
        if(!$result['state']) {
            showMessage($result['msg'], $url, 'html', 'error');
        }
        if ($result['data']['pdr_payment_state'] || empty($result['data']['api_pay_amount'])) {
            showMessage('该充值单不需要支付', $url, 'html', 'error');
        }

	    //转到第三方API支付
	    $this->_api_pay($result['data'], $payment_info);
	}

	/**
	 * 第三方在线支付接口
	 *
	 */
	private function _api_pay($order_info, $payment_info) {
    	$payment_api = new $payment_info['payment_code']($payment_info,$order_info);
    	if($payment_info['payment_code'] == 'chinabank') {
    		$payment_api->submit();
    	} else {
    		@header("Location: ".$payment_api->get_payurl());
    	}
    	exit();
	}

	/**
	 * 通知处理(支付宝异步通知和网银在线自动对账)
	 *
	 */
	public function notifyOp(){
        switch ($_GET['payment_code']) {
            case 'alipay':
                $success = 'success'; $fail = 'fail'; break;
            case 'chinabank':
                $success = 'ok'; $fail = 'error'; break;
            default: 
                exit();
        }

        $order_type = $_POST['extra_common_param'];
        $out_trade_no = $_POST['out_trade_no'];
        $trade_no = $_POST['trade_no'];


        $log_text = json_encode($_POST);
        $fp = fopen("log.txt","a");
        flock($fp, LOCK_EX) ;
        fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\n".$log_text."\n");
        flock($fp, LOCK_UN);
        fclose($fp);
		//参数判断
		if(!preg_match('/^\d{18}$/',$out_trade_no)) exit($fail);

		$logic_payment = Logic('payment');

		if ($order_type == 'pd_order') {
		    $result = $logic_payment->getPdOrderInfo($out_trade_no);
		    if ($result['data']['pdr_payment_state'] == 1) {
		        exit($success);
		    }

            if($result['data']['api_pay_amount'] != $_POST['total_fee']){
                exit($fail);
            }

		} else {
		    exit();
		}
		$order_pay_info = $result['data'];

		//取得支付方式
		$result = $logic_payment->getPaymentInfo($_GET['payment_code']);
		if (!$result['state']) {
		    exit($fail);
		}
		$payment_info = $result['data'];

		//创建支付接口对象
		$payment_api	= new $payment_info['payment_code']($payment_info,$order_pay_info);

		//对进入的参数进行远程数据判断
		$verify = $payment_api->notify_verify();
		if (!$verify) {
		    exit($fail);
		}

        //购买商品
		if ($order_type == 'pd_order') {
		    $result = $logic_payment->updatePdOrder($out_trade_no,$trade_no,$payment_info,$order_pay_info);
		}

		exit($result['state'] ? $success : $fail);
	}

	/**
	 * 支付接口返回
	 *
	 */
	public function returnOp(){

	    $order_type = $_GET['extra_common_param'];
		if($order_type != 'pd_order') {
		    exit();
		}
		$out_trade_no = $_GET['out_trade_no'];
		$trade_no = $_GET['trade_no'];
		$url = CLIENT_SITE_URL.'/index.php';
        $url_error = CLIENT_SITE_URL . '/index.php?act=predeposit&op=recharge';

		//对外部交易编号进行非空判断
		if(!preg_match('/^\d{18}$/',$out_trade_no)) {
		    showMessage('参数错误',$url,'','html','error');
		}

		$logic_payment = Logic('payment');

	    $result = $logic_payment->getPdOrderInfo($out_trade_no);

        if(!$result['state']) {
            showMessage($result['msg'], $url, 'javascript', 'error');
        }
        if ($result['data']['pdr_payment_state'] == 1) {
            $payment_state = 'success';
        }

		$order_pay_info = $result['data'];
		$api_pay_amount = $result['data']['api_pay_amount'];

		if ($payment_state != 'success' || $api_pay_amount != $_GET['total_fee']) {
		    //取得支付方式
		    $result = $logic_payment->getPaymentInfo($_GET['payment_code']);
		    if (!$result['state']) {
		        showMessage($result['msg'],$url,'javascript','error');
		    }
		    $payment_info = $result['data'];

		    //创建支付接口对象
		    $payment_api	= new $payment_info['payment_code']($payment_info,$order_pay_info);

		    //返回参数判断
		    $verify = $payment_api->return_verify();
		    if(!$verify) {
		        showMessage('支付数据验证失败',$url_error,'javascript','error');
		    }

		    //取得支付结果
		    $pay_result	= $payment_api->getPayResult($_GET);
		    if (!$pay_result) {
		        showMessage('非常抱歉，您的订单支付没有成功，请您后尝试',$url_error,'close','error');
		    }

            //更改订单支付状态
		    if ($order_type == 'pd_order') {
		        $result = $logic_payment->updatePdOrder($out_trade_no, $trade_no, $payment_info, $order_pay_info);
		    }
		    if (!$result['state']) {
		        showMessage('支付状态更新失败',$url_error,'javascript','error');
		    }
		}

        showMessage('支付成功',$url,'javascript','error');

	}
}