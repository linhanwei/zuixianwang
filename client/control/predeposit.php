<?php

defined('InSystem') or exit('Access Invalid!');
class predepositControl extends ClientControl{

	public function __construct() {
		parent::__construct();
    }

	public function rechargeOp(){
		Tpl::showpage('recharge');
	}

    public function cashOp(){
        //判断是否已绑定银行卡
        $model_member_bank = Model('member_bank');
        $condition = array();
        $condition['member_id'] = $this->member_info['member_id'];
        $condition['card_type'] = '2';
        $my_bank_list = $model_member_bank->getBankList($condition);

        Tpl::output('my_bank_list',$my_bank_list);
        Tpl::showpage('cash');
    }

    public function transferOp(){
        Tpl::showpage('transfer');
    }


    public function my_bank_listOp(){
        $model_member_bank = Model('member_bank');
        $condition = array();
        $condition['member_id'] = $this->member_info['member_id'];
        $my_bank_list = $model_member_bank->getBankList($condition);

        Tpl::output('my_bank_list',$my_bank_list);
        Tpl::showpage('my_bank_list');
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
            $pay_url = 'index.php?act=payment&op=pd_order';

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

}
