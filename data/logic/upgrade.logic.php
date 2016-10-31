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

        $inviter_list = $this->_getInviter($member_info['member_id'],3);

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



        //俱乐部返现
        //省
        if($member_info['member_provinceid'] > 0){
            $province_condition['is_agent'] = 1;
            $province_condition['agent_provinceid'] = $member_info['member_provinceid'];
            $province_condition['agent_cityid'] = 0;
            $province_condition['agent_areaid'] = 0;
            $agent_list = Model('member')->getMemberList($province_condition);


            if($agent_list){
                foreach($agent_list as $agent){
                    $data = array();
                    $data['member_id'] = $agent['member_id'];
                    $data['member_name'] = $agent['member_name'];

                    $data['amount'] = UPGRADE_AGENT_PROVINCE;
                    $head_price -= $data['amount'];

                    $data['pu_sn'] = $pay_sn;
                    $data['lg_desc'] = '俱乐部[' . $member_info['member_name'] . '] 升级帐号,单号:'.$data['pu_sn'];

                    $model_pd->changePd('club_upgrade',$data);
                }
            }

        }

        //市
        if($member_info['member_cityid'] > 0){
            $city_condition['is_agent'] = 1;
            $city_condition['agent_cityid'] = $member_info['agent_cityid'];
            $city_condition['agent_areaid'] = 0;
            $agent_list = Model('member')->getMemberList($city_condition);


            if($agent_list){
                foreach($agent_list as $agent){
                    $data = array();
                    $data['member_id'] = $agent['member_id'];
                    $data['member_name'] = $agent['member_name'];

                    $data['amount'] = UPGRADE_AGENT_CITY;
                    $head_price -= $data['amount'];

                    $data['pu_sn'] = $pay_sn;
                    $data['lg_desc'] = '俱乐部[' . $member_info['member_name'] . '] 升级帐号,单号:'.$data['pu_sn'];

                    $model_pd->changePd('club_upgrade',$data);
                }
            }

        }

        //区县
        if($member_info['member_areaid'] > 0){
            $area_condition['is_agent'] = 1;
            $area_condition['agent_areaid'] = $member_info['member_areaid'];
            $agent_list = Model('member')->getMemberList($area_condition);


            if($agent_list){
                foreach($agent_list as $agent){
                    $data = array();
                    $data['member_id'] = $agent['member_id'];
                    $data['member_name'] = $agent['member_name'];

                    $data['amount'] = UPGRADE_AGENT_AREA;
                    $head_price -= $data['amount'];

                    $data['pu_sn'] = $pay_sn;
                    $data['lg_desc'] = '俱乐部[' . $member_info['member_name'] . '] 升级帐号,单号:'.$data['pu_sn'];

                    $model_pd->changePd('club_upgrade',$data);
                }
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


    private function _getInviter($member_id,$level = 3){
        $inviterList = array();
        $model_member = Model('member');
        $memberInfo= $model_member->getMemberInfoByID($member_id);
        $inviter_id = $memberInfo['inviter_id'];
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
