<?php
/**
 * 卖家预约管理
 *
 *
 *
*/


defined('InSystem') or exit('Access Invalid!');
class store_appointment_goodsControl extends BaseSellerControl {
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
	 * 绑定商品URL
	 *
	 */
	public function indexOp() {
        
		self::profile_menu('goods','goods');
		Tpl::output('store_appointment_url', $this->store_info['store_appointment_url']);
		Tpl::showpage('store_appointment.goods');
	}

	/**
	 * 
	 * 设置URL
	 */
	public function urlOp(){
		$update['store_appointment_url'] = $_POST['store_appointment_url'];
		Model("store")->editStore($update,array('store_id'=>$_SESSION['store_id']));
		$this->recordSellerLog('修改预约关联商品');
		
		showDialog('操作成功','reload','js');
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
        	case 'goods':
        		$menu_array = array(
	            array('menu_key'=>'goods','menu_name'=>'关联商品设置',	'menu_url'=>'index.php?act=store_appointment_goods')
	            );
            break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
