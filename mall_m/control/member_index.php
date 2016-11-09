<?php
/**
 * 我的商城
 *
 *
 *
 *
 */


defined('InSystem') or exit('Access Invalid!');

class member_indexControl extends mobileMemberControl {

	public function __construct(){
		parent::__construct();
	}

    /**
     * 会员新首页
     */
    public function new_indexOp(){
        $member_info = array();
        $member_info['user_name'] = $this->member_info['member_name'];
        $member_info['avator'] = $this->member_info['member_avatar'];
        $member_info['point'] = number_format($this->member_info['member_points'],2);
        $member_info['predepoit'] = $this->member_info['available_predeposit'];
        $member_info['available_rc_balance'] = $this->member_info['available_rc_balance'];
//        dump($member_info);
        Tpl::output('member_info',$member_info);
        Tpl::showpage('member.index');
    }

    /**
     * 会员中心首页
     */
	public function indexOp() {
        $member_info = array();
        $member_info['user_name'] = $this->member_info['member_name'];
        $member_info['avator'] = $this->member_info['member_avatar'];
        $member_info['point'] = number_format($this->member_info['member_points'],2);
        $member_info['predepoit'] = $this->member_info['available_predeposit'];
        $member_info['available_rc_balance'] = $this->member_info['available_rc_balance'];

        //网站信息
        $model_setting = Model('setting');
        $list_setting = $model_setting->getListSetting();
//        dump($member_info);
//        Tpl::output('member_info',$member_info);
//        Tpl::showpage('member.index');

        output_data(array('member_info' => $member_info,'web_config'=>$list_setting));
	}

}
