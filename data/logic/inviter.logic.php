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
        }
    }

    /**
     * 商户上级提成 分佣金
     * @param int $seller_id 商户id
     * @param string $seller_name 商户名称
     * @param int $amount 总额
     * @param string $sn 说明
     * @param int $level 级别
     * @return boolen
     */
    public function sellerCommis($seller_id,$seller_name,$amount,$sn = '',$level=3) {
        try {
            //所在省
            $store_info = Model('store')->getStoreInfo(array('member_id'=>$seller_id));

            if($store_info['province_id'] > 0 && INVITE_AGENT_PROVINCE > 0){
            }

            $member_info = Model('member')->getMemberInfoByID($seller_id);
            $model_agent_commis = Model('agent_commis');
            $have_region = false;
            //所在县区
            if($store_info['region_id'] > 0 && INVITE_AGENT_AREA > 0){
                $model_agent = Model('agent');
                $agent = $model_agent->getAgentInfo(array('city_id'=>$store_info['city_id'],'area_id'=>$store_info['region_id']));
                if($agent){
                    $data = array();
                    $data['member_id'] = $member_info['member_id'];
                    $data['member_name'] = $member_info['member_name'];
                    $data['agent_id'] = $agent['agent_id'];
                    $data['commis'] = round($amount * INVITE_AGENT_AREA,2);
                    $data['commis_time'] = TIMESTAMP;

                    $data['sn'] = $sn;

                    $model_agent_commis->saveCommisLog('sale',$data);
                    $have_region = true;
                }
            }

            //所在市
            if($store_info['city_id'] > 0 && INVITE_AGENT_CITY > 0){
                $model_agent = Model('agent');
                $agent = $model_agent->getAgentInfo(array('city_id'=>$store_info['city_id'],'area_id'=>0));
                if($agent){
                    $data = array();
                    $data['member_id'] = $member_info['member_id'];
                    $data['member_name'] = $member_info['member_name'];
                    $data['agent_id'] = $agent['agent_id'];
                    $data['commis_time'] = TIMESTAMP;

                    $commis_rate = INVITE_AGENT_CITY;
                    if($have_region == false){
                        $commis_rate += INVITE_AGENT_AREA;
                    }

                    $data['commis'] = round($amount * $commis_rate,2);
                    $data['sn'] = $sn;

                    $model_agent_commis->saveCommisLog('sale',$data);
                }

            }

            return true;
            //获得
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param $member_id
     * @param int $level
     * @return array
     */
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
}