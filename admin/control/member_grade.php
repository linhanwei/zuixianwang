<?php
/**
 * 会员管理
 *
 *
 *
 ***/

defined('InSystem') or exit('Access Invalid!');

class member_gradeControl extends SystemControl{
	public function __construct(){
		parent::__construct();
		Language::read('member');
	}
	/**
	 * 会员管理
	 */
	public function indexOp(){
	    $model_setting = Model('setting');
	    $list_setting = $model_setting->getListSetting();
	    $list_setting['member_grade'] = $list_setting['member_grade']?unserialize($list_setting['member_grade']):array();
	    if (chksubmit()){
    	    $update_arr = array();
    	    if($_POST['mg']){
    	        $mg_arr = array();
    	        $i = 0;
    	        foreach($_POST['mg'] as $k=>$v){
    	            $mg_arr[$i]['grade_id'] = $i;
                    if($i==0){
                        $mg_arr[$i]['grade_name'] = '普通用户';
                    }elseif($i==1){
                        $mg_arr[$i]['grade_name'] = 'VIP用户';
                    }
        			//所需经验值
        			$mg_arr[$i]['price'] = floatval($v['price']);
        			$i++;
    	        }
    	        $update_arr['member_grade'] = serialize($mg_arr);
    	    } else {
    	        $update_arr['member_grade'] = '';
    	    }
    	    $result = true;
    	    if ($update_arr){
    	        $result = $model_setting->updateSetting($update_arr);
    	    }
    	    if ($result){
    	        $this->log(L('nc_edit,nc_member_grade'),1);
				showDialog(L('nc_common_save_succ'),'reload','succ');
    	    } else {
    	        $this->log(L('nc_edit,nc_member_grade'),0);
				showDialog(L('nc_common_save_fail'));
    	    }
	    } else {
	        Tpl::output('list_setting',$list_setting);
		    Tpl::showpage('member.grade');
	    }
	}
}