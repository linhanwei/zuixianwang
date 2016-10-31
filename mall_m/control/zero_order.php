<?php

/**
 * 0元淘订单
 *
 *
 */
defined('InSystem') or exit('Access Invalid!');

class zero_orderControl extends mobileMemberControl {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 订单列表
     */
    public function order_listOp() {
        //status: 0:全部,1:待发货,2:已发货,3:已完成
        $status = $_GET['status'];
        $member_id = $this->member_info['member_id'];

        $model_zero_order = Model('zero_order');

        //查询条件
        $condition = array();

        $condition['buyer_id'] = $member_id;
        switch ($status){
            case 0:

                break;
            case 1:
                $condition['order_state'] = 20;
                break;
            case 2:
                $condition['order_state'] = 30;
                break;
            case 3:
                $condition['order_state'] = 40;
                break;
        }

        //关联表
        $list = $model_zero_order->getMemberOrderList($condition,20);
//        $page_count = $model_zero_order->gettotalpage();

//        for($i=0;$i<=15;$i++){
//            $list[] = $list[0];
//        }
        $out_data['status'] = $status;
        $out_data['goods_list'] = $list;
        $out_data['base_site_url'] = UPLOAD_SITE_URL.DS;
        $out_data['wap_site_url'] = $config['wap_site_url'].DS;

        output_data($out_data, mobile_page($page_count));
    }

    /**
     * 订单详情
     */
    public function order_detailOp(){
        $order_id = $_GET['oid'];
        $condition['order_id'] = $order_id;

        $model_zero_order = Model('zero_order');
        $info = $model_zero_order->getMemberOrderDetail($condition);

        if (empty($info)) {
            output_error('订单不存在');
        }

        if(in_array($info['order_state'],array(30,40))){
            $model_express = Model('express');
            $shipping_list = $model_express->getAllList('','id,e_name');

            foreach ($shipping_list as $sv){
                if($sv['id'] == $info['shipping_express_id']){
                    $shipping_name = $sv['e_name'];
                }
            }
            $info['shipping_name'] = $shipping_name;
        }

        $output_data['goods_detail'] = $info;
        output_data($output_data);
    }

    /**
     * 会员确认收货
     *
     */
    public function edit_orderOp(){
        $order_id = $_POST['oid'];

        if(empty($order_id)){
            output_error('请选择订单!');
        }

        $member_id = $this->member_info['member_id'];

        if(empty($member_id)){
            output_error('您还没登录,请先登录!');
        }

        $model_zero_order = Model('zero_order');

        $condition['buyer_id'] = $member_id;
        $condition['order_id'] = $order_id;
        $condition['order_state'] = 30;

        $count = $model_zero_order->getCount($condition);

        if($count == 0){
            output_error('非法操作!!');
        }

        $update['order_state'] = 40;
        $result = $model_zero_order->editData($update, $condition);

        if($result){
            $out_data['status'] = 1;
            $out_data['msg'] = '确认收货成功!';
        }else{
            $out_data['status'] = 0;
            $out_data['msg'] = '确认收货失败!';
        }

        output_data($out_data);
    }

    /*
     * 取消订单(没发货可以取消)
     */
    public function cancel_orderOp() {
        $order_id = $_POST['oid'];

        if(empty($order_id)){
            output_error('请选择订单!');
        }

        $member_id = $this->member_info['member_id'];

        if(empty($member_id)){
            output_error('您还没登录,请先登录!');
        }

        $model_zero_order = Model('zero_order');
        $model_pd = Model('predeposit');

        $order_where['order_id'] = $order_id;
        $order_where['buyer_id'] = $member_id;
        $info = $model_zero_order->getInfo($order_where,'*');

        if(empty($info)){
            output_error('订单不存在!');
        }

        if($info['order_state'] == 0){
            output_error('该订单已经取消!');
        }

        if(in_array($info['order_state'],array(30,40))){
            output_error('该订单已经发货,不能取消!');
        }

        $model_pd->beginTransaction();
        $is_add_success = TRUE;

        //更改订单信息
        $data = array();
        $data['refund_amount'] = $info['shipping_amount'];
        $data['order_state'] = 0;

        $edit_order_result = $model_zero_order->editData($data, $order_where);
        if(!$edit_order_result){
            $is_add_success = false;
        }

        //加余额
        $data = array();
        $data['member_id'] = $member_id;
        $data['member_name'] = $info['buyer_name'];
        $data['amount'] = $info['shipping_amount'];
        $data['order_sn'] = $info['order_sn'];

        $edit_money_result = $model_pd->changePd('zero_order_cancel',$data);
        if(!$edit_money_result){
            $is_add_success = false;
        }

        if($is_add_success){
            $model_pd->commit();
            $output_data = array('status'=>1,'msg'=>'取消订单成功');
            output_data($output_data);
        }else{
            $model_pd->rollback();
            output_error('取消订单失败');
        }

        return FALSE;
    }

    /*
     * 下订单
     */
    public function create_orderOp() {
        $goods_number = $_POST['goods_number'];
        $payment_code = $_POST['payment_code'] ? $_POST['payment_code'] : 'predeposit';
        $goods_id = $_POST['goods_id'];
        $prov = $_POST['prov_id'];
        $city = $_POST['city_id'];
        $region = $_POST['area_id'];
        $area_info = $_POST['area_info'];
        $address = $_POST['address'];
        $mob_phone = $_POST['mob_phone'];
        $buyer_name = $_POST['true_name'];
        $goods_freight = $_POST['goods_freight'];

        if($goods_number<= 0){
            output_error('购买数量最少1个!');
        }
        
        if(empty($goods_id)){
            output_error('请选择商品!');
        }
        
        if(empty($prov)){
            output_error('请选择省份!');
        }
        
        if(empty($city)){
            output_error('请选择城市!');
        }
        
        if(empty($region)){
            output_error('请选择区县!');
        }
        
        if(empty($address)){
            output_error('请填写详细地址!');
        }
        
        if(empty($mob_phone)){
            output_error('请填写手机号码!');
        }
        
        if(empty($buyer_name)){
            output_error('请填写收货人!');
        }
        
        $model_zero_order = Model('zero_order');
        $model_zero_order_goods = Model('zero_order_goods');
        $model_pd = Model('predeposit');
        $model_zero_goods = Model('zero_goods');
        
        $goods_info = $model_zero_goods->getGoodsDetail($goods_id);
        
        if(empty($goods_info)){
            output_error('选择支付的商品不存在');
        }
        
        if($goods_info['goods_surplus_num'] == 0){
            output_error('该商品已经卖完,请选择其他的产品!');
        }
        
        if($goods_info['goods_state'] != 1){
            output_error('该商品暂时不能销售,请选择其他的产品!');
        }
        
        if($goods_info['goods_verify'] != 1){
            output_error('该商品没有通过审核暂时不能销售,请选择其他的产品!');
        }
        
        //获取运费的总价
        $goods_amount = $this->compute_amount($prov,$goods_info,$goods_number);


        if(!in_array($payment_code,array('predeposit'))){
            output_error('请输入正确的支付方式');
        }

        if($goods_amount > $this->member_info['available_predeposit']){
            output_error('您的账户余额不够,请先充值!');
        }

        $data = array();
        //添加订单信息
        $data['order_sn'] = $model_pd->makeSn($this->member_info['member_id']);//订单号
        $data['buyer_id'] = $this->member_info['member_id'];
        $data['buyer_name'] = $buyer_name;
        $data['tel'] = $mob_phone;
        $data['shipping_amount'] = $goods_amount;
        $data['pd_amount'] = $goods_amount;
        $data['reciver_province_id'] = $prov;
        $data['reciver_city_id'] = $city;
        $data['reciver_area_id'] = $region;
        $data['area_info'] = $area_info;
        $data['address'] = $address;
        $data['evaluation_state'] = 0;
        $data['order_state'] = 10;
        $data['lock_state'] = 0;
        $data['delete_state'] = 0;
        $data['order_from'] = 1;
        $data['add_time'] = TIMESTAMP;

        $order_id = $model_zero_order->addData($data);
        
        if($order_id){
            $order_sn = $data['order_sn'];
            //添加订单商品信息
            $data = array();
            $data['order_id'] = $order_id;
            $data['goods_id'] = $goods_id;
            $data['goods_name'] = $goods_info['goods_name'];
            $data['goods_price'] = $goods_info['goods_price'];
            $data['goods_num'] = $goods_number;
            $data['goods_image'] = $goods_info['goods_image_index'];
            $data['goods_pay_price'] = $goods_freight;
            $data['buyer_id'] = $this->member_info['member_id'];
            $model_zero_order_goods->addData($data);

            //修改商品信息
            $model_zero_goods->editGoodsProcess($goods_id,$goods_number);

            //余额支付
            if($payment_code == 'predeposit'){

                $model_pd->beginTransaction();
                
                $pay_sn = $model_pd->makeSn($this->member_info['member_id']);//支付单号

                //减余额
                $data = array();
                $data['member_id'] = $this->member_info['member_id'];
                $data['member_name'] = $buyer_name;
                $data['amount'] = $goods_amount;
                $data['order_sn'] = $order_sn;

                try{

                    if($model_pd->changePd('zero_order_pay',$data)){
                        $is_add_success = TRUE;
                
                        $update = array();
                        $update['order_state'] = 20;
                        $update['payment_time'] = TIMESTAMP;
                        $update['payment_code'] = 'predeposit';
                        $update['pay_sn'] = $pay_sn;
                        $update = $model_zero_order->editData($update,array('order_sn'=>$order_sn));
                        if(!$update){
                            $is_add_success = FALSE;
                        }


                        if($is_add_success){
                            $model_pd->commit();
                            output_data(array('order_sn'=>$order_sn));
                        }else{
                            $model_pd->rollback();
                            output_error('生成订单失败');
                        }
                    }
                }catch (Exception $ex){
                    $model_pd->rollback();
                    output_error('生成订单失败'.$ex->getMessage());
                }

            }
        }
        
        return FALSE;
    }

    
    /**
     * 计算邮费
     * @param int $prov
     * @param int $goods_info
     * @param int $goods_num
     * @return array
     */
    private function compute_amount($prov,$goods_info,$goods_num) {
        $freight_1 = array(20,24,21,12,17,14,18,13,23,22,19,25,15,16,10,27,9,11,1,2,28,26,30);
        $freight_2 = array(3,4,6,7,29,31,5,8);
        $freight = 0;


        $next_weight_times = ceil(($goods_num * $goods_info['goods_weight'] - 500) / 500);


        if(in_array($prov,$freight_1)){
            $freight = intval($goods_info['goods_freight'] + $next_weight_times * 10);
        }else if(in_array($prov,$freight_2)){
            $freight = intval($goods_info['goods_freight'] + $next_weight_times * 17);
        }else{
            $freight = intval($goods_info['goods_freight'] + $next_weight_times * 10);
        }

        return $freight * $goods_num;
    }

}
