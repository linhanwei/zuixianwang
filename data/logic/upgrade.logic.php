<?php
/**
 * 升级处理
 *
 *
 */
defined('InSystem') or exit('Access Invalid!');
class upgradeLogic {
    /**
     *
     * 升级
     * @param $member_info
     * @param $grade_info
     * @param string $pay_sn
     */
    public function upgrade($member_info,$grade_info,$pay_sn = ''){
        $model_member = Model('member');
        $edit_data['grade_id'] = $grade_info['grade_id'];
        $edit_data['grade_name'] = $grade_info['grade_name'];
        $edit_data['upgrade_date'] = time();
        $edit_data['upgrade_times'] = array('exp','upgrade_times+1');

        $model_member->editMember(array('member_id'=>$member_info['member_id']), $edit_data);

        //增加会员积分
        /*$point_data['pl_memberid'] = $member_info['member_id'];
        $point_data['pl_membername'] = $member_info['member_name'];
        $point_data['pl_points'] = $grade_info['price'];*/
        //升级不返
        //Model('points')->savePointsLog('upgrade',$point_data,true);
        $model_pd = Model('predeposit');
        $head_price = $grade_info['price'];     //剩余作为总部金额


        //上级分成

        $inviter_list = $this->_getInviter($member_info['inviter_id'],3);

        if($inviter_list){
            $i = 0;
            $level_commis = json_decode(UPGRADE_COMMIS,true);
            foreach($inviter_list as $inviter_info){
                $data = array();
                $data['member_id'] = $inviter_info['member_id'];
                $data['member_name'] = $inviter_info['member_name'];

                $data['amount'] = $level_commis[$i++];
                $head_price -= $data['amount'];

                $data['pu_sn'] = $pay_sn;
                $data['lg_desc'] = '被邀请人[' . $member_info['member_name'] . '] 升级帐号,单号:'.$data['pu_sn'];

                $model_pd->changePd('invite_upgrade',$data);
            }

        }


        $have_region = false;
        $model_agent_commis = Model('agent_commis');
        //区域代理返现
        //省
        if($member_info['member_provinceid'] > 0){


        }

        //区县
        if($member_info['member_areaid'] > 0){
            $agent = $this->_getAgentByArea($member_info,$member_info['member_cityid']);

            if($agent){
                $data = array();
                $data['member_id'] = $member_info['member_id'];
                $data['member_name'] = $member_info['member_name'];
                $data['agent_id'] = $agent['agent_id'];
                $data['commis'] = UPGRADE_AGENT_AREA;
                $data['commis_time'] = TIMESTAMP;

                $head_price -= $data['commis'];

                $data['sn'] = $pay_sn;
                $model_agent_commis->saveCommisLog('upgrade',$data);
                $have_region = true;
            }

        }

        //市
        if($member_info['member_cityid'] > 0){
            $model_agent = Model('agent');
            $agent = $model_agent->getAgentInfo(array('city_id'=>$member_info['member_cityid'],'area_id'=>0));
            if($agent){
                $data = array();
                $data['member_id'] = $member_info['member_id'];
                $data['member_name'] = $member_info['member_name'];
                $data['agent_id'] = $agent['agent_id'];
                $data['commis'] = UPGRADE_AGENT_CITY;
                $data['commis_time'] = TIMESTAMP;

                if($have_region == false){
                    $data['commis'] += UPGRADE_AGENT_AREA;
                }

                $data['sn'] = $pay_sn;

                $model_agent_commis->saveCommisLog('upgrade',$data);
            }

        }


        //总部
        if($head_price>0){
            $agent = Model('member')->getMemberInfoByID(2);

            $data = array();
            $data['member_id'] = $agent['member_id'];
            $data['member_name'] = $agent['member_name'];

            $data['amount'] = $head_price;

            $data['pu_sn'] = $pay_sn;
            $data['lg_desc'] = '[' . $member_info['member_name'] . '] 升级帐号,单号:'.$data['pu_sn'];

            $model_pd->changePd('club_upgrade',$data);
        }

        //发放电子券
        Logic('coupons')->upgrade($member_info['member_id'],$member_info['member_name']);
    }



    /**
     *
     * 获得上级会员列表
     *
     * @param $inviter_id
     * @param int $level
     * @return array
     */
    private function _getInviter($inviter_id,$level = 3){
        $inviterList = array();
        $model_member = Model('member');
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

    /**
     * 获得上级最近区代理
     * @param $member_info
     * @param $city_id
     * @return array|mixed
     */
    private function _getAgentByArea($member_info,$city_id){
        $agent_info = array();
        $model_member = Model('member');
        $inviter_id = $member_info['inviter_id'];
        while($inviter_id > 0){
            $inviterMember = $model_member->getMemberInfoByID($inviter_id);
            if($inviterMember){
                $inviter_id = $inviterMember['inviter_id'];

                $agent_info = $this->_getAgentInArea($inviterMember['member_id'],$city_id);
                if($agent_info){
                    break;
                }
            }else{
                break;
            }
        }

        return $agent_info;
    }


    /**
     *
     * 判断会员这个区/市的代理
     * @param $member_id
     * @param $city_id   地区ID
     *
     * @return mixed
     */
    private function _getAgentInArea($member_id,$city_id){
        $model_agent_member = Model('agent_member');
        $model_agent = Model('agent');

        $agent_member = $model_agent_member->getAgentMemberInfo(array('member_id'=>$member_id));
        if($agent_member){
            $agent = $model_agent->getAgentInfo(array('agent_id'=>$agent_member['agent_id'],'city_id'=>$city_id,'area_id'=>array('gt',0)));
            if($agent){
                return $agent;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}
