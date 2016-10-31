<?php
/**
 * 会员积分管理
 ***/


defined('InSystem') or exit('Access Invalid!');
class member_pointsControl extends mobileMemberControl {
    public function __construct() {
        parent::__construct();
        /**
         * 读取语言包
         */
        Language::read('member_member_points,member_pointorder');
    }

    public function indexOp(){
        $this->points_logOp();
        exit;
    }

    /**
     * 会员积分日志列表
     */
    public function points_logOp(){
        $condition_arr = array();
        $condition_arr['pl_memberid'] = $this->member_info['member_id'];
        if ($_GET['stage']){
            $condition_arr['pl_stage'] = $_GET['stage'];
        }
        if($_GET['member_id'] > 0){
            $condition_arr['pl_memberid'] = $_GET['member_id'];
        }
        $condition_arr['saddtime'] = strtotime($_GET['stime']);
        $condition_arr['eaddtime'] = strtotime($_GET['etime']);
        if($condition_arr['eaddtime'] > 0) {
            $condition_arr['eaddtime'] += 86400;
        }
        $condition_arr['pl_desc_like'] = $_GET['description'];
        //分页
        $page	= new Page();
        $page->setEachNum($this->page);
        //查询会员积分日志列表
        $points_model = Model('points');
        $points_list = $points_model->getPointsLogList($condition_arr,$page,'*','');


        $points_list = $points_list ? $points_list : array();

        if($points_list){
            $model_member = Model('member');
            foreach($points_list as $key=>$val){
                $points_list[$key]['pl_addtime'] = date('Y-m-d H:i:s',$val['pl_addtime']);
                $member = $model_member->getMemberInfo(array('member_id'=>$val['pl_memberid']));

                if($member['member_truename']){
                    $points_list[$key]['pl_membername'] = $member['member_truename'];
                }

                $member = $model_member->getMemberInfo(array('member_id'=>$val['pl_from_member_id']));

                if($member['member_truename']){
                    $points_list[$key]['pl_from_membername'] = $member['member_truename'];
                }

                unset($points_list[$key]['pl_adminid']);
                unset($points_list[$key]['pl_adminname']);
            }
        }
        $page_count = $page->getTotalPage();
        output_data(array('member_points_list' => $points_list), mobile_page($page_count));
    }

    /**
     * 推荐会员列表
     */
    public function recommend_listOp(){
        $member_id = $_POST['member_id'] > 0 ? $_POST['member_id'] : $this->member_info['member_id'];
        $is_store = $_POST['is_store'] ? $_POST['is_store'] : 0;
        //$level = $_POST['level'] > 0 ? $_POST['level'] : 1;
        $condition = array();
        $condition['inviter_id'] = $member_id;
        $condition['is_store'] = $is_store;

        $model_member = Model('member');
        $member_list = $model_member->getMemberList($condition);
        $page_count = $model_member->gettotalpage();

        if($member_list){
            //$points_model = Model('points');
            foreach($member_list as $key=>$val){
                $condition_arr = '';
                $condition_arr['pl_memberid'] = $member_id;
                $condition_arr['pl_from_member_id'] = $val['member_id'];

                $condition_arr['pl_stage_in'] = "'inviter','rebate'";
                $condition_arr['group'] = "pl_from_member_id";

                //$fields = 'sum(pl_points) as pl_points,pl_memberid,pl_from_member_id,pl_from_membername,pl_desc,pl_addtime,pl_stage';
                //$member_points = $points_model->getPointsInfo($condition_arr,'*');

                //$recommend_list[$key]['pl_desc'] = $member_points['pl_desc'];
                $recommend_list[$key]['member_id'] = strval($val['member_id'] + 600000);
                $recommend_list[$key]['mobile_phone'] =  $val['member_name'];
                $recommend_list[$key]['member_truename'] = $val['member_truename'];
                $recommend_list[$key]['invite_time'] = date('Y-m-d H:i:s',$val['member_time']);
                //$recommend_list[$key]['pl_memberid'] =  $member_points['pl_memberid'];
                //$recommend_list[$key]['pl_membername'] =  $member_points['pl_membername'];
                //$recommend_list[$key]['pl_stage'] =  $member_points['pl_stage'];
                //$recommend_list[$key]['pl_points'] = $member_points['pl_points'] ? $member_points['pl_points'] : 0;
            }
        }

        output_data(array('recommend_list' => $recommend_list), mobile_page($page_count));
    }

    /**
     * 奖励日志列表
     */
    public function points_inviter_logOp(){
        $condition_arr = array();
        $condition_arr['pl_memberid'] = $this->member_info['member_id'];
        if ($_GET['stage']){
            $condition_arr['pl_stage'] = $_GET['stage'];
        }
        if($_GET['member_id'] > 0){
            $condition_arr['pl_memberid'] = $_GET['member_id'];
        }
        $condition_arr['saddtime'] = strtotime($_GET['stime']);
        $condition_arr['eaddtime'] = strtotime($_GET['etime']);
        if($condition_arr['eaddtime'] > 0) {
            $condition_arr['eaddtime'] += 86400;
        }
        $condition_arr['pl_desc_like'] = $_GET['description'];
        //分页
        $page	= new Page();
        $page->setEachNum($this->page);
        //查询会员积分日志列表
        $points_model = Model('points_inviter');
        $points_list = $points_model->getPointsLogList($condition_arr,$page,'*','');


        $points_list = $points_list ? $points_list : array();

        if($points_list){
            $model_member = Model('member');
            foreach($points_list as $key=>$val){
                $points_list[$key]['pl_addtime'] = date('Y-m-d H:i:s',$val['pl_addtime']);
                $member = $model_member->getMemberInfo(array('member_id'=>$val['pl_memberid']));

                if($member['member_truename']){
                    $points_list[$key]['pl_membername'] = $member['member_truename'];
                }

                $member = $model_member->getMemberInfo(array('member_id'=>$val['pl_from_member_id']));

                if($member['member_truename']){
                    $points_list[$key]['pl_from_membername'] = $member['member_truename'];
                }

                unset($points_list[$key]['pl_adminid']);
                unset($points_list[$key]['pl_adminname']);
            }
        }
        $page_count = $page->getTotalPage();
        output_data(array('member_points_inviter_list' => $points_list), mobile_page($page_count));
    }

    /**
     * 佣金积提取至会员积分
     */
    public function inviter2pointsOp(){
        $obj_validate = new Validate();
        $distill_amount = round(floatval($_POST['distill_amount']),2);
        $validate_arr[] = array("input"=>$distill_amount, "require"=>"true",'validator'=>'Compare','operator'=>'>=',"to"=>'100',"message"=>'最少提取100奖励');
        $validate_arr[] = array("input"=>$_POST["password"], "require"=>"true","message"=>'请输入支付密码');
        $obj_validate -> validateparam = $validate_arr;
        $error = $obj_validate->validate();

        if ($error != ''){
            output_error($error);
        }

        $model_points = Model('points');
        $model_points_inviter = Model('points_inviter');
        $model_member = Model('member');
        $member_info = $model_member->getMemberInfoByID($this->member_info['member_id']);

        //验证支付密码
        if (md5($_POST['password']) != $member_info['member_paypwd']) {
            output_error('支付密码错误');
        }
        if($this->member_info['member_points_inviter'] < 100){
            output_error('奖励不足100元，不能提取！');
        }
        if($this->member_info['member_points_inviter'] < $distill_amount){
            output_error('奖励不足');
        }

        try {
            Model()->beginTransaction();

            //转至会员积分
            $pointsArray = array(
                'pl_desc'=>'奖励提取至会员积分',
                'pl_memberid'=>$this->member_info['member_id'],
                'pl_membername'=>$this->member_info['member_name'],
                'distill_amount'=>$distill_amount
            );
            $model_points->savePointsLog('distill',$pointsArray,true);
            //扣除奖励
            $pointsArray = array(
                'pl_desc'=>'提取至会员积分',
                'pl_memberid'=>$this->member_info['member_id'],
                'pl_membername'=>$this->member_info['member_name'],
                'distill_amount'=>$distill_amount
            );
            $model_points_inviter->savePointsLog('distill',$pointsArray,true);

            Model()->commit();
            output_data(array('distill_amount'=>$distill_amount));
        } catch (Exception $e) {
            Model()->rollback();
            output_error($e->getMessage());
        }
    }
}
