<?php
/**
 * 多级消费流量行为
 *
 *
 */
defined('InSystem') or exit('Access Invalid!');
class inviterLogic {
    private $grade_id = 0;
    private $member_name = '';

    /**
     *
     * 消费者上级提成 分佣金
     * @param $buyer_id
     * @param $buyer_name
     * @param $amount
     * @param $sn
     * @param int $level
     */
    public function buyerCommis($buyer_id,$buyer_name,$amount,$sn,$level=3) {
        //消费流量积分
        if($buyer_id>0){
            $buyerInviterList = $this->_getInviter($buyer_id,$level);
            if($buyerInviterList){
                $commis_rate = json_decode(INVITE_RATE,true);
                for($i=0;$i<count($buyerInviterList);$i++){
                    //
                    $sn_desc = !empty($sn) ? ',交易单号：' . $sn : '';
                    $pl_desc = '获得【' . $buyer_name . '】佣金'.$sn_desc;
                    $saveArray = array(
                        'pl_desc'=>$pl_desc,
                        'pl_memberid'=>$buyerInviterList[$i]['member_id'],
                        'pl_membername'=>$buyerInviterList[$i]['member_name'],
                        'rebate_amount'=>round($amount*$commis_rate[$i],2),
                        'pl_from_member_id'=>$buyer_id,
                        'pl_from_membername'=>$buyer_name,
                        'pl_sn'=>$sn
                    );
                    Model('points_inviter')->savePointsLog('rebate',$saveArray,true);
                }
            }

            //俱乐部的流量积分
            //所在省
            $member_info = Model('member')->getMemberInfoByID($buyer_id);
            if($member_info['member_provinceid'] > 0 && INVITE_AGENT_PROVINCE > 0){
                $province_condition['is_agent'] = 1;
                $province_condition['agent_provinceid'] = $member_info['member_provinceid'];
                $province_condition['agent_cityid'] = 0;
                $province_condition['agent_areaid'] = 0;
                $agent_list = Model('member')->getMemberList($province_condition);
                $this->_setAgentList($agent_list,$sn,$amount,$buyer_id,$buyer_name,INVITE_AGENT_PROVINCE);
            }

            //所在市
            if($member_info['member_cityid'] > 0 && INVITE_AGENT_CITY > 0){
                $city_condition['is_agent'] = 1;
                $city_condition['agent_cityid'] = $member_info['member_cityid'];
                $city_condition['agent_areaid'] = 0;
                $agent_list = Model('member')->getMemberList($city_condition);
                $this->_setAgentList($agent_list,$sn,$amount,$buyer_id,$buyer_name,INVITE_AGENT_CITY);
            }

            //所在县区
            if($member_info['member_areaid'] > 0 && INVITE_AGENT_AREA > 0){
                $area_condition['is_agent'] = 1;
                $area_condition['agent_areaid'] = $member_info['member_areaid'];
                $agent_list = Model('member')->getMemberList($area_condition);
                $this->_setAgentList($agent_list,$sn,$amount,$buyer_id,$buyer_name,INVITE_AGENT_AREA);
            }
        }
    }

    private function _getInviter($member_id,$level = 3){
        $inviterList = array();
        $model_member = Model('member');
        $memberInfo= $model_member->getMemberInfoByID($member_id);
        $inviter_id = $memberInfo['inviter_id'];
        $this->grade_id = $memberInfo['grade_id'];
        $this->member_name = $memberInfo['member_name'];
        for($i=0;$i<$level;$i++){
            if($inviter_id==0) break;
            $inviterMember = $model_member->getMemberInfoByID($inviter_id);
            if($inviterMember){
                $inviter_id = $inviterMember['inviter_id'];
                $inviterList[] = $inviterMember;
            }else{
                break;
            }
        }

        return $inviterList;
    }

    /*
    * 获得N上级会员
    * @param $member_id 当前会员id
    * @return array
    */

    /**
     *
     * 保存返还积分
     *
     * @param $agent_list
     * @param $sn
     * @param $amount
     * @param $buyer_id
     * @param $buyer_name
     */
    private function _setAgentList($agent_list,$sn,$amount,$buyer_id,$buyer_name,$rate){
        if($agent_list){
            foreach($agent_list as $agent){
                $sn_desc = !empty($sn) ? ',交易单号：' . $sn : '';
                $pl_desc = '获得【' . $buyer_name . '】消费流量积分'.$sn_desc;
                $saveArray = array(
                    'pl_desc'=>$pl_desc,
                    'pl_memberid'=>$agent['member_id'],
                    'pl_membername'=>$agent['member_name'],
                    'rebate_amount'=>round($amount * $rate,2),
                    'pl_from_member_id'=>$buyer_id,
                    'pl_from_membername'=>$buyer_name,
                    'pl_sn'=>$sn
                );
                Model('points_inviter')->savePointsLog('rebate',$saveArray,true);
            }
        }

    }

    /**
     * 商户收款流量积分
     *
     * @param $seller_id
     * @param $seller_name
     * @param $amount
     * @param string $sn
     * @param int $level
     */
    public function sellerCommis($seller_id,$seller_name,$amount,$sn = '',$level=3) {
        //商家流量积分
        /*if($seller_id > 0){
            $sellerInviterList = $this->_getInviter($seller_id,$level);
            if($sellerInviterList){
                $commis_rate = array(0.02,0.02,0.02);

                for($i=0;$i<count($sellerInviterList);$i++){
                    $sn_desc = !empty($sn) ? ',交易单号：' . $sn : '';
                    $pl_desc = '获得【' . $seller_name . '】佣金'.$sn_desc;

                    $saveArray = array(
                        'pl_desc'=>$pl_desc,
                        'pl_memberid'=>$sellerInviterList[$i]['member_id'],
                        'pl_membername'=>$sellerInviterList[$i]['member_name'],
                        'rebate_amount'=>round($amount*$commis_rate[$i],2),
                        'pl_from_member_id'=>$seller_id,
                        'pl_from_membername'=>$seller_name,
                        'pl_sn'=>$sn
                    );
                    Model('points_inviter')->savePointsLog('rebate',$saveArray,true);
                }
            }
        }*/
    }
}