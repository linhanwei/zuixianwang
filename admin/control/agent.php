<?php
/**
 * 代理商管理界面
 *
 ***/

defined('InSystem') or exit('Access Invalid!');

class agentControl extends SystemControl{
	public function __construct(){
		parent::__construct();
	}

    public function indexOp(){
        $this->agentOp();
    }

    /**
     * 代理商管理
     */
    public function agentOp(){
        $model_agent = Model('agent');

        if($_GET['agent_name']){
            $condition['agent_name'] = array('like', '%' . trim($_GET['agent_name']) . '%');
        }
        if($_GET['area_name']){
            $condition['area_info'] = array('like','%'. trim($_GET['area_name']) . '%');
        }
        $condition['is_deleted'] = 0;

        //列表
        $agent_list = $model_agent->getAgentList($condition,10,'*','agent_id desc');

        Tpl::output('agent_list',$agent_list);
        Tpl::output('page',$model_agent->showpage());
        Tpl::showpage('agent.index');
    }

    /**
     * 添加代理商
     */
    public function agent_addOp(){
        $model_agent = Model('agent');

        if (chksubmit()){
            if(Model('agent')->getAgentCount(array('agent_name'=>$_POST['agent_name'])) > 0){
                showMessage('用户已为代理商');
            }


            $param['agent_name'] = $_POST['agent_name'];
            $param['company_name'] = $_POST['company_name'];
            $param['password'] = md5($_POST['password']);

            $model_area = Model('area');
            //代理地区
            $agent_area = $model_area->getAreaByID($_POST['area_id']);
            if(empty($agent_area['city_id'])){
                showMessage('至少为市代');
            }
            if(!$this->checkAgent($agent_area)){
                showMessage('请检查代理的数量，市代码限1人，区代理限1人');
            }
            $param['area_id']		= $agent_area['area_id'];
            $param['city_id']		= $agent_area['city_id'];
            $param['province_id']	= $agent_area['province_id'];
            $param['area_info']	    = $_POST['area_info'];
            $param['join_at']       = TIMESTAMP;
            $param['year_limit']    = $_POST['year_limit'];
            $param['upgrade_commis']= 0;
            $param['sale_commis']   = 0;
            $param['is_deleted']    = 0;



            $rs = $model_agent->addAgent($param);
            if ($rs){
                $this->log('添加代理商'.'['.$_POST['agent_name'].']',1);
                showMessage(L('nc_common_save_succ'),'index.php?act=agent&op=agent');
            }else {
                showMessage(L('nc_common_save_fail'));
            }
        }

        Tpl::showpage('agent.add');
    }
    /**
     * 编辑代理商
     */
    public function agent_editOp(){
        $model_agent = Model('agent');
        $agent_id = $_GET['agent_id']>0?$_GET['agent_id']:$_POST['agent_id'];
        $agent = $model_agent->getAgentInfo(array('agent_id'=>$agent_id));
        if (chksubmit()){
            if(empty($agent)){
                showMessage('代理不存在');
            }
            $update_param['company_name'] = $_POST['company_name'];

            $model_area = Model('area');

            //代理地区
            if($agent['province_id'] != $_POST['province_id'] || $agent['city_id'] != $_POST['city_id'] || $agent['area_id'] != $_POST['area_id']){
                $agent_area = $model_area->getAreaByID($_POST['area_id']);
                if(empty($agent_area['city_id'])){
                    showMessage('至少为市代');
                }
                if(!$this->checkAgent($agent_area)){
                    showMessage('请检查代理的数量，市代码限1人，区代理限1人');
                }
                $update_param['area_id']		= $agent_area['area_id'];
                $update_param['city_id']		= $agent_area['city_id'];
                $update_param['province_id']	= $agent_area['province_id'];
                $update_param['area_info']	    = $_POST['area_info'];
            }

            $update_param['year_limit']    = $_POST['year_limit'];
            if($_POST['password']){
                $update_param['password'] = md5($_POST['password']);
            }

            $rs = $model_agent->editAgent($update_param,array('agent_id'=>$agent_id));
            if ($rs){
                $this->log('编辑代理商'.'['.$agent['agent_name'].']',1);
                showMessage(L('nc_common_save_succ'),'index.php?act=agent&op=agent');
            }else {
                showMessage(L('nc_common_save_fail'));
            }
        }


        Tpl::output('agent',$agent);
        Tpl::showpage('agent.add');
    }
    /**
     * 关系会员
     */
    public function agent_memberOp(){
        $model_agent = Model('agent');
        $model_agent_member = Model('agent_member');
        $agent_id = $_GET['agent_id']>0?$_GET['agent_id']:$_POST['agent_id'];
        $condition = array('agent_id'=>$agent_id);
        $agent = $model_agent->getAgentInfo($condition);
        if (chksubmit()){
            if(empty($agent)){
                showMessage('代理不存在');
            }

            $agent_member_count = $model_agent_member->getAgentMemberCount(array('agent_id'=>$agent_id));

            if($agent_member_count>=5){
                showMessage('每个区域紧限关联5个帐号');
            }

            $member_name = $_POST['member_name'];
            if(empty($member_name)){
                showMessage('请输入需要关联的帐号');
            }
            $member_info = Model('member')->getMemberInfo(array('member_name'=>$member_name));

            if(empty($member_info)){
                showMessage('用户不存在，请先注册');
            }

            $agent_member_count = $model_agent_member->getAgentMemberCount(array('member_id'=>$member_info['member_id']));

            if($agent_member_count>0){
                showMessage('一个帐号只能关联一个区域代理');
            }


            $param['agent_id']    = $_POST['agent_id'];
            $param['member_id']   = $member_info['member_id'];
            $param['member_name'] = $member_info['member_name'];

            $rs = $model_agent_member->addAgentMember($param);
            if ($rs){
                $this->log('编辑关系'.'['.$agent['agent_name'].']',1);
                showMessage(L('nc_common_save_succ'),'index.php?act=agent&op=agent_member&agent_id='.$agent_id);
            }else {
                showMessage(L('nc_common_save_fail'));
            }
        }

        $agent_member_list = $model_agent_member->getAgentMemberList($condition);
        Tpl::output('agent_member_list',$agent_member_list);
        Tpl::output('agent',$agent);
        Tpl::showpage('agent_member.edit');
    }
    /**
     * 删除代理商
     */
    public function agent_delOp(){
        if (!empty($_GET['agent_id'])){
            $condition = array('agent_id'=>$_GET['agent_id']);
            $agent = Model('agent')->getAgentInfo($condition);
            $this->log('删除代理商资料[Agent:'.$agent['member_name'].']',1);
            Model('agent')->editAgent(array('is_deleted'=>1),$condition);
            showMessage(L('nc_common_del_succ'));
        }else {
            showMessage(L('nc_common_del_fail'));
        }
    }

    /**
     * 删除关联帐号
     */
    public function agent_member_delOp(){
        if (!empty($_GET['id'])){
            Model('agent_member')->delAgentMember($_GET['id']);
            showMessage(L('nc_common_del_succ'));
        }else {
            showMessage(L('nc_common_del_fail'));
        }
    }

    /**
     * 留言列表
     */
	public function feedbackOp(){
        $model_feedback = Model('feedback');

        if(trim($_GET['member_name']) != ''){
            $condition['fb_member_name']	= array('like', '%'.$_GET['member_name'].'%');
            Tpl::output('member_name',$_GET['member_name']);
        }

        if($_GET['fb_state'] == '1' || $_GET['fb_state'] == '2'){
            $condition['fb_state'] = $_GET['fb_state'];
        }

        $condition['fb_type'] = 1;

        //列表
        $feedback_list = $model_feedback->getFeedbackList($condition, 10,'*','fb_id desc');

        Tpl::output('feedback_list',$feedback_list);
        Tpl::output('page',$model_feedback->showpage('20'));
        Tpl::showpage('agent.feedback');
    }


    /**
     * 留言管理
     */
    public function feedback_detailOp(){
        $model_feedback = Model('feedback');
        $fb_id = $_GET['fb_id'] ? $_GET['fb_id'] : $_POST['fb_id'];
        if(empty($fb_id)){
            exit();
        }
        $condition = array('fb_id'=>$fb_id);
//提交
        if (chksubmit()) {
            $update_arr['fb_remark'] = trim($_POST['fb_remark']);
            $update_arr['fb_state'] = 2;
            $model_feedback->editFeedback($update_arr,$condition);
            //跳转
            $this->log('处理区域处理意见', 1);
            showMessage('成功处理', 'index.php?act=agent&op=feedback');
        }

        $feedback = $model_feedback->getFeedbackInfo($condition);

        Tpl::output('feedback',$feedback);
        Tpl::showpage('agent.feedback_detail');
    }


    /**
     * ajax操作
     */
    public function ajaxOp(){
        switch ($_GET['branch']){
            case 'check_aa_name':
                $aa_id = $_GET['aa_id'];
                $condition = array();
                $condition['aa_name'] = $_GET['aa_name'];
                if($aa_id>0){
                    $condition['aa_id'] = array('neq',$aa_id);
                }
                $info = Model('agent_area')->getAgentAreaInfo($condition);
                if (!empty($info)){
                    exit('false');
                }else {
                    exit('true');
                }
            case 'check_agent_name':
                $condition = array();
                $condition['agent_name'] = $_GET['agent_name'];

                if (Model('agent')->getAgentCount($condition) > 0){
                    exit('false');
                }else {
                    exit('true');
                }

        }
    }

    /**
     * 判断代理是否满员
     */
    private function checkAgent($area_info){

        if($area_info){
            if($area_info['province_id'] > 0 && $area_info['city_id'] == 0 && $area_info['area_id']==0){
                $where = "province_id = {$area_info['province_id']} AND city_id = 0 AND area_id = 0";

                $count = Model('agent')->getAgentCount($where);

                if($count >= 1){
                    return false;
                }
            }elseif($area_info['city_id'] > 0 && $area_info['area_id'] == 0){
                $where = "province_id = {$area_info['province_id']} AND city_id = {$area_info['city_id']} AND area_id = 0";
                $count = Model('agent')->getAgentCount($where);

                if($count >= 1){
                    return false;
                }
            }elseif($area_info['area_id'] > 0){
                $where = "province_id = {$area_info['province_id']} AND city_id = {$area_info['city_id']} AND area_id = {$area_info['area_id']}";
                $count = Model('agent')->getAgentCount($where);

                if($count >= 1){
                    return false;
                }
            }
        }

        return true;
    }
}
