<?php
/**
 * 电子券管理
 *
 *
 *
 *
 */

defined('InSystem') or exit('Access Invalid!');

class couponsControl extends mobileMemberControl{

    public function __construct() {
        parent::__construct();
    }

    public function indexOp()
    {
        $model = Model('coupons');
        $where  = " member_name = '{$this->member_info['member_name']}'";

        $useable = $_POST['useable'];


        if ($useable == '1') {
            $where .= " AND state = 0 AND to_date > " . strtotime("-1 day");
        }elseif($useable == '2'){
            $where .= " AND (state = 1 OR to_date < " . strtotime("1 day") . ")";
        }


        $list = $model->getCouponsList($where, 20);
        if($list){
            foreach($list as $key=>$val){
                $list[$key]['to_date'] = date('Y-m-d',$val['to_date']);
                $list[$key]['create_at'] = date('Y-m-d',$val['create_date']);
                if($val['use_at'] > 0){
                    $list[$key]['use_at'] = date('Y-m-d',$val['use_at']);
                }
            }
            output_data(array('list'=>$list));
        }else{
            output_error('暂无电子消费券');
        }

    }

    /**
     * 使用
     */
    public function useOp(){
        if($this->member_info['is_store'] != 1){
            output_error('仅店铺才能核销电子消费券');
        }
        $sn = $_POST['sn'];
        $model_coupons = Model('coupons');
        $coupons_info = $model_coupons->getCouponsBySN($sn);

        if(!$coupons_info){
            output_error('电子消费券不存在');
            exit;
        }

        if($coupons_info['state'] != '0'){
            output_error('电子消费券已使用');
            exit;
        }

        if($coupons_info['to_date']<strtotime(date('Y-m-d',time()))){
            output_error('电子消费券已过有效期');
            exit;
        }


        $member_name = $_POST['mobile'];
        $member_info = Model('member')->getMemberInfo(array('member_name'=>$member_name));
        if(empty($member_info)){
            output_error('会员不存在');
            exit;
        }

        if($coupons_info['member_id'] > 0  && ($coupons_info['member_id'] != $member_info['member_id'])){
            output_error('此券不属于该会员');
            exit;
        }

        $model_coupons->setCouponsUsedById($coupons_info['id'],$member_info['member_id'],$member_info['member_name'],$this->member_info['store_id']);

        output_data(array('msg'=>'兑换成功,金额:' . $coupons_info['denomination']));
    }
}
