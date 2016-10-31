<?php
/**
 * 预约统计概述
*/


defined('InSystem') or exit('Access Invalid!');

class store_appointment_reportControl extends BaseSellerControl {
    public function __construct(){
        parent::__construct();
        Language::read('member_store_statistics');
        import('function.statistics');
    		switch($this->store_info['store_appointment']){
    		case 1:
        		showMessage('申请审核中', urlShop('store_appointment_audit', 'index'), '', 'error');
        		break;
        	case 0:
        	case 2:
	            showMessage('请先申请预约功能', urlShop('store_appointment_audit', 'index'), '', 'error');
        		break;
        
        }
    }
	/**
	 * 预约分析
	 */
	public function indexOp(){
	    $model_order = Model('appointment_order');
		$condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
	 	if ($_GET['member_name'] != '') {
            $condition['member_name'] = $_GET['member_name'];
        }
	    
	 	$if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
        $start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }

		$order_list = $model_order->getOrderList($condition, 20, '*', 'order_id desc','');

		$state_arr = array();
		//统计数据
		$state_arr['all'] = $model_order->getOrderCount('state <> 5');
		$state_arr['2'] = $model_order->getOrderCount('state = 2 AND state <> 5');
		$state_arr['3'] = $model_order->getOrderCount('state = 3 AND state <> 5');
		$state_arr['4'] = $model_order->getOrderCount('state = 4 AND state <> 5');
		$state_arr['sn'] = $model_order->getOrderCount("state = 4 AND goods_order_sn <> '' AND state <> 5");
        
		Tpl::output('state_arr',$state_arr);
        Tpl::output('order_list',$order_list);
        Tpl::output('show_page',$model_order->showpage());
    	Tpl::showpage('store_appointment.report');
	}


}
