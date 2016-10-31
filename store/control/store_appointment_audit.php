<?php
/**
 * 卖家预约管理
 *
 *
*/


defined('InSystem') or exit('Access Invalid!');
class store_appointment_auditControl extends BaseSellerControl {
    public function __construct() {
        parent::__construct();
    }

	/**
	 * 申请预约
	 *
	 */
	public function indexOp() {
        if($_GET['state'] == '1'){
			$update = array('store_appointment'=>1);
			Model("store")->editStore($update,array('store_id'=>$this->store_info['store_id']));
			
			$this->recordSellerLog('申请预约功能');
			showMessage('已提交申请，请耐心等候');
		}
		self::profile_menu('audit','audit');
		Tpl::output('store_appointment', $this->store_info['store_appointment']);
		Tpl::showpage('store_appointment.audit');
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
        	case 'audit':
        		$menu_array = array(
	            array('menu_key'=>'audit','menu_name'=>'申请预约',	'menu_url'=>'index.php?act=store_appointment&op=audit')
	            );
            break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
