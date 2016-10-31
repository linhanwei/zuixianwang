<?php
/**
 * 任务计划 - 按天执行的任务
 *
 * 
 *
 */
defined('InSystem') or exit('Access Invalid!');

class dateControl extends BaseCronControl {

    /**
     * 该文件中所有任务执行频率，默认1天，单位：秒
     * @var int
     */
    const EXE_TIMES = 86400;


    /**
     * 默认方法
     */
    public function indexOp() {
        //自动计算每天的奖励
        $this->_auto_inviter_points();
        //会员相关数据统计
        $this->_points_remission();
    }

    /**
     * 20年等额返还
     */
    public function _points_remission(){
        //获得有会员积分的会员
        //查询会员信息
        $redeemable_date = strtotime(date('Y-m-d 00:00:00'));       //执行日期
        $yestoday_date =  strtotime(date("Y-m-d 00:00:00",strtotime("-1 day")));                        //计算日期

        //首次返还
        $i = 0;
        while($i++<1000){
            $points_list = Model('points_sum')->getSumList('pd_pay=0 AND pd_price = 0 AND pd_addtime = ' . $yestoday_date, 500);

            if(empty($points_list)){
                break;
            }

            $this->_couputer($points_list,$redeemable_date);
        }

        //返还昨天
        $i = 0;
        while($i++<1000){
            $points_list = Model('points_sum')->getSumList('pd_last_day = ' . $yestoday_date, 500);
            if(empty($points_list)){
                break;
            }
            $this->_couputer($points_list,$redeemable_date);
        }

        //
    }

    /**
     *
     * 计算返还
     * @param $points_list
     * @param $redeemable_date
     */
    private function _couputer($points_list,$redeemable_date){

        $model_points = Model('points');
        $model_pd = Model('predeposit');

        foreach($points_list as $points){
            $model_pd->beginTransaction();
            //首次计算
            if($points['pd_price'] == 0){
                $points['pd_price'] = round($points['pd_points'] / $points['pd_days'],8);
                if($points['pd_price']==0){
                    $points['pd_price'] = 0.00000001;
                }
            }

            $data = array();
            $data['pdr_sn'] = $pdr_sn = $model_pd->makeSn();
            $data['pdr_member_id'] = $points['pd_memberid'];
            $data['pdr_member_name'] = $points['pd_membername'];
            $data['pdr_points'] = $points['pd_price'];
            $data['pdr_amount'] = $points['pd_price'];
            $data['pdr_add_time'] = TIMESTAMP;

            $insert = $model_pd->addRedeemablesn($data);

            if($insert){
                //扣除积分
                $insert_arr = array();
                $insert_arr['pl_memberid'] = $points['pd_memberid'];
                $insert_arr['pl_membername'] = $points['pd_membername'];
                $insert_arr['redeemablesn'] = $data['pdr_sn'];
                $insert_arr['pl_points'] = $points['pd_price'];

                $result = $model_points->savePointsLog('redeemable',$insert_arr,true);

                if($result){
                    $data = array();
                    $data['member_id'] = $points['pd_memberid'];
                    $data['member_name'] = $points['pd_membername'];
                    $data['amount'] = $points['pd_price'];
                    $data['pdr_sn'] = $pdr_sn;
                    $data['admin_name'] = 'crontab';
                    $data['last_redeemable'] = $redeemable_date;
                    $model_pd->changePd('redeemable',$data);

                    //返还记录
                    $points['pd_points'] -= $points['pd_price'];
                    $points['pd_pay']++;
                    $points['pd_less'] = $points['pd_days'] - $points['pd_pay'];
                    $points['pd_last_day'] =  $redeemable_date;
                    if(Model('points_sum')->editSum(array('id'=>$points['id']),$points)){
                        $model_pd->commit();
                    }
                }
            }

            $model_pd->rollback();
            sleep(2);
        }
    }

    /**
     * 以100为整数提取奖励积分
     */
    private function _auto_inviter_points(){
        $model_member = Model('member');



        $i = 0;
        while($i++<1000){
            $member_list = $model_member->getMemberList('member_points_inviter >= 100');

            if(empty($member_list)) {
                break;
            }
            foreach($member_list as $member_info){
                $this->_auto_inviter_points_computer($member_info);
            }
        }
    }

    /**
     *
     * 计算每个会员返还
     * @param $member_info
     */
    private function _auto_inviter_points_computer($member_info){
        if($member_info['member_points_inviter'] < 100){
            return;
        }

        $distill_amount = floor($member_info['member_points_inviter']);
        $distill_amount = $distill_amount - $distill_amount % 100;

        $model_points = Model('points');
        $model_points_inviter = Model('points_inviter');

        Model()->beginTransaction();

        //转至会员积分
        $pointsArray = array(
            'pl_desc'=>'奖励提取至会员积分',
            'pl_memberid'=>$member_info['member_id'],
            'pl_membername'=>$member_info['member_name'],
            'distill_amount'=>$distill_amount
        );
        $model_points->savePointsLog('distill',$pointsArray,true);
        //扣除奖励
        $pointsArray = array(
            'pl_desc'=>'提取至会员积分',
            'pl_memberid'=>$member_info['member_id'],
            'pl_membername'=>$member_info['member_name'],
            'distill_amount'=>$distill_amount
        );
        $model_points_inviter->savePointsLog('distill',$pointsArray,true);

        Model()->commit();
        sleep(1);
    }
}