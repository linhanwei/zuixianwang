<?php
/**
 * 会员中心——我是卖家
 *
 *
*/


defined('InSystem') or exit('Access Invalid!');

class store_appointment_classControl extends BaseSellerControl {
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
	 * 预约分类
	 *
	 * @param
	 * @return
	 */
	public function indexOp() {
		$model_class	= Model('appointment_class');

		if($_GET['type'] == 'ok') {
			if(intval($_GET['class_id']) != 0) {
				$class_info	= $model_class->getAppointmentClassInfo(array('sac_id'=>intval($_GET['class_id'])));
				Tpl::output('class_info',$class_info);
			}
			if(intval($_GET['top_class_id']) != 0) {
				Tpl::output('class_info',array('sac_parent_id'=>intval($_GET['top_class_id'])));
			}
			$appointment_class		= $model_class->getAppointmentClassList(array('store_id'=>$_SESSION['store_id'],'sac_parent_id'=>0));
			Tpl::output('appointment_class',$appointment_class);
			Tpl::showpage('store_appointment_class.add','null_layout');
		} else {
			$appointment_class		= $model_class->getTreeClassList(array('store_id'=>$_SESSION['store_id']),2);
			$str	= '';
			if(is_array($appointment_class) and count($appointment_class)>0) {
				foreach ($appointment_class as $key => $val) {
					$row[$val['sac_id']]	= $key + 1;
					$str .= intval($row[$val['sac_parent_id']]).",";
				}
				$str = substr($str,0,-1);
			} else {
				$str = '0';
			}
			Tpl::output('map',$str);
			Tpl::output('class_num',count($appointment_class)-1);
			Tpl::output('appointment_class',$appointment_class);

			self::profile_menu('appointment_class','appointment_class');
			Tpl::showpage('store_appointment_class.list');
		}
	}
	/**
	 * 卖家商品分类保存
	 *
	 * @param
	 * @return
	 */
	public function appointment_saveOp() {
		$model_class	= Model('appointment_class');
		if(isset($_POST['sac_id'])) {
		    $sac_id = intval($_POST['sac_id']);
		    if ($sac_id <= 0) {
		        showDialog(L('wrong_argument'));
		    }
			$class_array	= array();
			if($_POST['sac_name'] != ''){
			    $class_array['sac_name']     = $_POST['sac_name'];
			}
			if($_POST['sac_parent_id'] != ''){
			    $class_array['sac_parent_id']= $_POST['sac_parent_id'];
			}
			if($_POST['sac_state'] != ''){
			    $class_array['sac_state']    = $_POST['sac_state'];
			}
			if($_POST['sac_sort'] != ''){
			    $class_array['sac_sort']     = $_POST['sac_sort'];
			}
			if($_POST['sac_is_budget'] != ''){
			    $class_array['sac_is_budget']     = $_POST['sac_is_budget'];
			    $class_array['sac_sort']  = 999;
			}
			
			$where = array();
			$where['store_id'] = $_SESSION['store_id'];
			$where['sac_id'] = intval($_POST['sac_id']);
			$state = $model_class->editAppointmentClass($class_array, $where);
			if($state) {
				showDialog(Language::get('nc_common_save_succ'),'index.php?act=store_appointment_class&op=index','succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog(Language::get('nc_common_save_fail'));
			}
		} else {
		    $class_array		= array();
		    $class_array['sac_name']      = $_POST['sac_name'];
		    $class_array['sac_parent_id'] = $_POST['sac_parent_id'];
		    $class_array['sac_state']     = $_POST['sac_state'];
		    $class_array['store_id']      = $_SESSION['store_id'];
		    $class_array['sac_sort']      = $_POST['sac_sort'];
			$state = $model_class->addAppointmentClass($class_array); 
			if($state) {
				showDialog(Language::get('nc_common_save_succ'),'index.php?act=store_appointment_class&op=index','succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog(Language::get('nc_common_save_fail'));
			}
		}
	}
	/**
	 * 卖家商品分类删除
	 *
	 * @param
	 * @return
	 */
	public function drop_appointment_classOp() {
		$model_class	= Model('appointment_class');
		$sacid_array = explode(',', $_GET['class_id']);
		foreach ($sacid_array as $key => $val) {
		    if (!is_numeric($val)) unset($sacid_array[$key]);
		}
		$where = array();
		$where['sac_id'] = array('in', $sacid_array);
		$where['store_id'] = $_SESSION['store_id'];
		$drop_state	= $model_class->delAppointmentClass($where);
		if ($drop_state){
			showDialog(Language::get('nc_common_del_succ'),'index.php?act=store_appointment_class&op=appointment_class','succ');
		}else{
			showDialog(Language::get('nc_common_del_fail'));
		}
	}

	/**
	 * 用户中心右边，小导航
	 *
	 * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @return
	 */
	private function profile_menu($menu_type,$menu_key='') {
		Language::read('member_layout');
		$menu_array		= array();
		switch ($menu_type) {
			case 'appointment_class':
				$menu_array = array(
				1=>array('menu_key'=>'appointment_class','menu_name'=>'预约分类',	'menu_url'=>'index.php?act=store_appointment_class&op=appointment_class'));
				break;
		}
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
	}
}
