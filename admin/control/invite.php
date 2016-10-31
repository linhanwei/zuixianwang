<?php
/**
 * 会员推荐分佣统计
 *
 *
 *
 ***/

defined('InSystem') or exit('Access Invalid!');

class inviteControl extends SystemControl{
	const EXPORT_SIZE = 1000;
	public function __construct(){
		parent::__construct();
		Language::read('member');
	}

	/**
	 * 会员管理
	 */
	public function indexOp(){
		$model_member = Model('member');

        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['stime']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['etime']);
        $start_unixtime = $if_start_date ? strtotime($_GET['stime']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['etime']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['lg_add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }

		//会员级别
		$member_grade = unserialize(C('member_grade'));

        $member_name = $_GET['member_name'];

		if ($member_name != '') {
            $member_info = $model_member->getMemberInfo(array('member_name'=>$member_name));
            $member_info['invite_count'] = $model_member->getMemberCount(array('inviter_id'=>$member_info['member_id']));
            $member_info['store_count'] = $model_member->getMemberCount(array('inviter_id'=>$member_info['member_id'],'is_store'=>1));
            $member_info['vip_count'] = $model_member->getMemberCount(array('inviter_id'=>$member_info['member_id'],'grade_id'=>1));
            $member_info['invite_amount'] = Model('predeposit')->getUpgradeAmount($member_info['member_name'],'invite_upgrade');

            $condition['inviter_id'] = $member_info['member_id'];
		}
		switch ($_GET['search_state']){
			case 'no_informallow':
				$condition['inform_allow'] = '2';
				break;
			case 'no_isbuy':
				$condition['is_buy'] = '0';
				break;
			case 'no_isallowtalk':
				$condition['is_allowtalk'] = '0';
				break;
			case 'no_memberstate':
				$condition['member_state'] = '0';
				break;
		}
		//会员等级
		$search_grade = $_GET['search_grade'] ? intval($_GET['search_grade']) : -1  ;
		if ($search_grade >= 0 && $member_grade){
		    //$condition['member_exppoints'] = array(array('egt',$member_grade[$search_grade]['exppoints']),array('lt',$member_grade[$search_grade+1]['exppoints']),'and');
            $condition['grade_id'] = $search_grade;
        }

		//排序
		$order = trim($_GET['search_sort']);
		if (empty($order)) {
		    $order = 'grade_id desc';
		}

        //商户
        $search_store = trim($_GET['search_store']);
        if ($search_store) {
            $condition['is_store'] = '1';
        }
        //合伙人
        $search_agent = trim($_GET['search_agent']);
        if ($search_agent) {
            $condition['is_agent'] = '1';
        }

        $member_list = array();
        if($member_name){
            $member_list = $model_member->getMemberList($condition, '*', 10, $order);
            Tpl::output('page',$model_member->showpage());
        }

		//整理会员信息
		if (is_array($member_list)){
            $model_upgrade = Model('upgrade');
			foreach ($member_list as $k=> $v){
				$member_list[$k]['member_time'] = $v['member_time']?date('Y-m-d H:i:s',$v['member_time']):'';
				$member_list[$k]['member_login_time'] = $v['member_login_time']?date('Y-m-d H:i:s',$v['member_login_time']):'';
				$member_list[$k]['member_grade'] = ($t = $model_member->getOneMemberGrade($v['member_exppoints'], false, $member_grade))?$t['level_name']:'';

                if($v['grade_id'] == 1){
                    $upgrade = $model_upgrade->getLastUpgrade($v['member_id']);
                    $member_list[$k]['upgrade_date'] = date('Y-m-d H:i:s',$upgrade['pu_payment_time']);
                }

                $member_list[$k]['invite_count'] = $model_member->getMemberCount(array('inviter_id'=>$v['member_id']));
                $member_list[$k]['invite_amount'] = Model('predeposit')->getUpgradeAmount($v['member_name'],'invite_upgrade');
            }
		}
		Tpl::output('member_grade',$member_grade);
		Tpl::output('search_sort',trim($_GET['search_sort']));
		Tpl::output('search_field_name',trim($_GET['search_field_name']));
		Tpl::output('search_field_value',trim($_GET['search_field_value']));
        Tpl::output('search_store',$search_store);
        Tpl::output('search_agent',$search_agent);
		Tpl::output('member_list',$member_list);
        Tpl::output('member_info',$member_info);
		Tpl::showpage('invite.index');
	}
}
