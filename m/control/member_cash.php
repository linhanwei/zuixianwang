<?php
/**
 * 现金管理
 ***/


defined('InSystem') or exit('Access Invalid!');
class member_cashControl extends mobileMemberControl {
    public function indexOp(){
        $this->cash_listOp();
        exit;
    }
    public function __construct() {
        parent::__construct();

    }
    /**
     * 提现列表
     */
    public function cash_listOp(){
        $condition_arr = array();
        $condition_arr['pdc_member_id'] = $this->member_info['member_id'];
        if ($_GET['state']){
            $condition_arr['pdc_payment_state'] = $_GET['state'];
        }
        if($_GET['pdc_id']){
            $condition_arr['pdc_id'] = $_GET['pdc_id'];
        }

        //分页
        $page	= new Page();
        $page->setEachNum(10);
        $page->setStyle('admin');
        //查询积分日志列表
        $cash_model = Model('predeposit');
        $cash_list = $cash_model->getPdCashList($condition_arr,$page,'*','pdc_id desc');

        //$page_count = $cash_model->gettotalpage();
        $cash_list = $cash_list ? $cash_list : array();

        if($cash_list){
            foreach($cash_list as $key=>$val){
                $cash_list[$key]['pdc_add_time'] = date('Y-m-d H:i:s',$val['pdc_add_time']);
                if($val['pdc_payment_time'] > 0){
                    $cash_list[$key]['pdc_payment_time'] = date('Y-m-d H:i:s',$val['pdc_payment_time']);

                }

                if($val['pdc_payment_state'] == 0){
                    $cash_list[$key]['pdc_payment_state'] = '未审核';
                }elseif($val['pdc_payment_state'] == 1){
                    $cash_list[$key]['pdc_payment_state'] = '提现申请通过';
                }else{
                    $cash_list[$key]['pdc_payment_state'] = '提现申请驳回';
                }
                $pdc_amount = abs($val['pdc_amount']);
                $cash_list[$key]['pdc_amount_'] = round($pdc_amount * 0.97,2);
                $cash_list[$key]['pdc_amount'] = $pdc_amount;
            }
        }
        $page_count = $cash_model->gettotalpage();
        output_data(array('cash_list' => $cash_list), mobile_page($page_count));
    }

    /**
     * 最近一次提现信息列表
     */
    public function last_cashOp(){
        $condition_arr = array();
        $condition_arr['pdc_member_id'] = $this->member_info['member_id'];

        //查询积分日志列表
        $cash_model = Model('predeposit');
        list($last_cash) = $cash_model->getPdCashList($condition_arr,1,'*','pdc_id desc');
        output_data(array('last_cash'=>$last_cash));
    }
}
