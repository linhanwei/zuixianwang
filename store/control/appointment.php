<?php
/**
 * 会员中心——我是卖家
 *
 *
*/


defined('InSystem') or exit('Access Invalid!');

class appointmentControl extends BaseHomeControl{
	/**
	 * 预约分类
	 *
	 * @param
	 * @return
	 */
	public function ajaxClassOp() {
		$store_id = intval($_GET['store_id']);
		if(!is_numeric($store_id)){
			die();
		}
		
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
		
		$model_class	= Model('appointment_class');

		$condition = array('store_id'=>$store_id);
		
		//cate
		if($id > 0){
			$condition['sac_id'] = $id;
		}
		$condition['sac_parent_id'] = 0;
		$appointment_class	= $model_class->getAppointmentClassList($condition);
		
		$return_class = array();
		if($appointment_class){
			unset($condition['sac_id']);
			$tmp = array();
			foreach ($appointment_class as $class){
				$tmp['id'] = $class['sac_id'];
				$tmp['name'] = $class['sac_name'];
				$return_class['cate'][] = $tmp;
			}
			
			if($id==0){
				$parent_id = $return_class['cate'][0]['id'];
			}else{
				$parent_id = $id;
			}
			//question
			//name(不可选择)
			$tmp = array();
			$condition = array('sac_parent_id'=>$parent_id,'sac_state'=>0);
			$appointment_class_name	= $model_class->getAppointmentClassList($condition);
			if($appointment_class_name){
				foreach($appointment_class_name as $name){
					if($name['sac_is_budget'] == 0){
						$tmp['one'] = $name['sac_name'];
					}else{
						$tmp['two'] = $name['sac_name'];
					}
					$return_class['question']['name'] = $tmp;
				}
			}
			
			//name(可选择)
			$tmp_one = $tmp_two = array();
			$condition = array('sac_parent_id'=>$parent_id,'sac_state'=>1);
			$appointment_class_name	= $model_class->getAppointmentClassList($condition);
			if($appointment_class_name){
				foreach($appointment_class_name as $name){
					if($name['sac_is_budget'] == 0){
						$return_class['question']['one'][] = $name['sac_name'];
					}else{
						$return_class['question']['two'][] = $name['sac_name'];
					}
				}
			}
		}
		
		echo json_encode($return_class);
	}
	
	
	/**
	 * 
	 * 提交预约
	 */
	public function applyOp(){
		$store_id = intval($_GET['store_id']);

		if (!chksubmit()) {
            Tpl::showpage('appointment_apply','null_layout');
            exit();
        } else {
		    if ($store_id <= 0) {
		        showDialog(L('wrong_argument'));
		    }
			$name = $_POST['name'];
			$time = $_POST['time'];
			$phone = $_POST['mobile'];
			
			if(empty($name)){
				showDialog('姓名不能为空');
			}
			
        	if(empty($time)){
				showDialog('日期不能为空');
			}
			
        	if(empty($phone)){
				showDialog('手机不能为空');
			}
			
			$time = strtotime($time);
			
			$type = $_POST['type'];
			$type_ = $_POST['type_'];
			$budget = $_POST['budget'];
			
			$order_array['name'] = $name;
			$order_array['time'] = $time;
			$order_array['phone'] = $phone;
			$order_array['type'] = $type;
			$order_array['type_'] = $type_;
			$order_array['budget'] = $budget;
			$order_array['store_id'] = $store_id;
			
			
			$model_order	= Model('appointment_order');
			$state = $model_order->addOrder($order_array);
			
			
			$store = Model('store')->getStoreInfo(array('store_id'=>$store_id));

			showDialog('预约成功',$store['store_appointment_url']);
	
        }
	}
}
