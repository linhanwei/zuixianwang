<?php
/**
 * 卖家预约管理
 *
 *
 *
*/


defined('InSystem') or exit('Access Invalid!');
class store_appointmentControl extends BaseSellerControl {
    public function __construct() {
        parent::__construct();
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
	 * 预约单列表
	 *
	 */
	public function indexOp() {
        $model_order = Model('appointment_order');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        if ($_GET['order_id'] != '') {
            $condition['order_id'] = $_GET['order_id'];
        }
        if ($_GET['name'] != '') {
            $condition['name'] = $_GET['name'];
        }
        $allow_state_array = array('1','2','3','4');
        if (in_array($_GET['state_type'],$allow_state_array)) {
            $condition['state'] = $_GET['state_type'];
        }
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
        $start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }

        $order_list = $model_order->getOrderList($condition, 20, '*', 'order_id desc','');

        
        Tpl::output('order_list',$order_list);
        Tpl::output('show_page',$model_order->showpage());
        self::profile_menu('list',$_GET['state_type']);

        Tpl::showpage('store_appointment_order.index');
	}
	
	/**
	 * 
	 * 马上洽谈
	 */
	public function change_stateOp(){
		$state = $_GET['state'];
		
		if(chksubmit()){
			switch ($state){      		
	        	case 3:
	        		$state_desc = '未达成预约';
	        		break;
	        	case 4:
	        		$state_desc = '已完成预约';
	        		break;
	        	case 'sn':
	        		$state_desc = '设置订单号为 : ' . $_POST['goods_order_sn'];
	        		break;
	        	default:
	        		$state_desc = '洽谈中';
	        		$update['member_id'] = $_SESSION['member_id'];
	        		$update['member_name'] = $_SESSION['member_name'];
	        		break;
	        }
			$member_id = $_SESSION['member_id'];
			$member_name = $_SESSION['member_name'];
			
			if($state == 'sn'){
				$update['goods_order_sn'] = $_POST['goods_order_sn'];
			}else{
	        	$update['state'] = $state;
			}
	        
	        $remark = !empty($_POST['remark']) ? $_POST['remark'] : '无备注';
	        $update['remark_' . $state] = $state_desc . ' : ' . $remark;

	        Model("appointment_order")->editOrder($update,array('order_id'=>$_GET['order_id']));

	        $this->recordSellerLog($state_desc);
	        
	        showDialog('操作成功','reload','js');
		}
		Tpl::output('state',$state);
		Tpl::output('order_id',$_GET['order_id']);
		Tpl::showpage('store_appointment_order.state','null_layout');
	}

	/**
	 * 用户中心右边，小导航
	 *
	 * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @return
     */
    private function profile_menu($menu_type='',$menu_key='') {
        Language::read('member_layout');
        switch ($menu_type) {
        	case 'list':
	            $menu_array = array(
	            array('menu_key'=>'','menu_name'=>Language::get('nc_member_path_all_order'),	'menu_url'=>'index.php?act=store_appointment&op=index'),
	            array('menu_key'=>'1','menu_name'=>'未处理',	'menu_url'=>'index.php?act=store_appointment&op=index&state_type=1'),
	            array('menu_key'=>'2','menu_name'=>'洽谈中',	'menu_url'=>'index.php?act=store_appointment&op=index&state_type=2'),
	            array('menu_key'=>'3','menu_name'=>'未达成预约',	'menu_url'=>'index.php?act=store_appointment&op=index&state_type=3'),
	            array('menu_key'=>'4','menu_name'=>'已完成预约',	'menu_url'=>'index.php?act=store_appointment&op=index&state_type=4')
	            );
	            break;
            break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
