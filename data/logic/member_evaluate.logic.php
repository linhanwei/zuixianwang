<?php
/**
 * 评价行为
 *
 */
defined('InSystem') or exit('Access Invalid!');
class member_evaluateLogic
{

    public function evaluateListDity($goods_eval_list)
    {
        foreach ($goods_eval_list as $key => $value) {
            $goods_eval_list[$key]['member_avatar'] = getMemberAvatarForID($value['geval_frommemberid']);
        }
        return $goods_eval_list;
    }

    /**
     * 验证订单信息
     * @param $order_id
     * @param $member_id
     * @return array
     */
    public function validation($order_id, $member_id)
    {
        $result = array('state' => 0, 'msg' => '', 'data' => '');
        if (!$order_id) {
            $result['msg'] = '参数错误!';
            return $result;
        }

        $model_order = Model('order');
        $model_store = Model('store');

        //获取订单信息
        $order_info = $model_order->getOrderInfo(array('order_id' => $order_id));
        //判断订单身份
        if ($order_info['buyer_id'] != $member_id) {
            $result['msg'] = '非法操作!';
            return $result;
        }

        //订单为'已收货'状态，并且未评论
        $order_info['evaluate_able'] = $model_order->getOrderOperateState('evaluation', $order_info);
        if (empty($order_info) || !$order_info['evaluate_able']) {
            $result['msg'] = '订单信息错误!';
            return $result;
        }

        //查询店铺信息
        $store_info = $model_store->getStoreInfoByID($order_info['store_id']);
        if (empty($store_info)) {
            $result['msg'] = '店铺信息错误!';
            return $result;
        }

        //获取订单商品
        $order_goods = $model_order->getOrderGoodsList(array('order_id' => $order_id));
        if (empty($order_goods)) {
            $result['msg'] = '订单商品信息错误!';
            return $result;
        }

        for ($i = 0, $j = count($order_goods); $i < $j; $i++) {
            $order_goods[$i]['goods_image_url'] = cthumb($order_goods[$i]['goods_image'], 60, $store_info['store_id']);
        }

        $result['state'] = 1;
        $result['data']['order_info'] = $order_info;
        $result['data']['store_info'] = $store_info;
        $result['data']['order_goods'] = $order_goods;

        return $result;
    }

    /**
     *保存评论
     * @param $add_data 评论数据
     * @param $order_info
     * @param $store_info
     * @param $order_goods
     * @param $member_id
     * @param $member_name
     */
    public function save($add_data, $order_info, $store_info, $order_goods, $member_id, $member_name)
    {
        $result = array('state' => 0, 'msg' => '', 'data' => '');

        $model_order = Model('order');
        $model_evaluate_goods = Model('evaluate_goods');

        $order_id = $order_info['order_id'];
        $evaluate_goods_array = array();
        $goodsid_array = array();
        foreach ($order_goods as $value) {
            $evaluate_goods_img_array = array();
            //如果未评分，默认为5分
            $evaluate_score = intval($add_data['goods'][$value['goods_id']]['score']);
            if ($evaluate_score <= 0 || $evaluate_score > 5) {
                $evaluate_score = 5;
            }
            //默认评语
            $evaluate_comment = $add_data['goods'][$value['goods_id']]['comment'];
            if (empty($evaluate_comment)) {
                $evaluate_comment = '不错哦';
            }

            //晒图图片
            for($gi=0;$gi<=4;$gi++){
                $comment_img = $add_data['goods'][$value['goods_id']]['evaluate_image'][$gi];
                if($comment_img){
                    $evaluate_goods_img_array[] = $comment_img;
                }
            }

            if(empty($evaluate_goods_img_array)){
                $evaluate_goods_img_array = null;
            }

            $evaluate_goods_info = array();
            $evaluate_goods_info['geval_orderid'] = $order_id;
            $evaluate_goods_info['geval_orderno'] = $order_info['order_sn'];
            $evaluate_goods_info['geval_ordergoodsid'] = $value['rec_id'];
            $evaluate_goods_info['geval_goodsid'] = $value['goods_id'];
            $evaluate_goods_info['geval_goodsname'] = $value['goods_name'];
            $evaluate_goods_info['geval_goodsprice'] = $value['goods_price'];
            $evaluate_goods_info['geval_goodsimage'] = $value['goods_image'];
            $evaluate_goods_info['geval_scores'] = $evaluate_score;
            $evaluate_goods_info['geval_content'] = $evaluate_comment;
            $evaluate_goods_info['geval_isanonymous'] = $add_data['anony'] ? 1 : 0;
            $evaluate_goods_info['geval_addtime'] = TIMESTAMP;
            $evaluate_goods_info['geval_storeid'] = $store_info['store_id'];
            $evaluate_goods_info['geval_storename'] = $store_info['store_name'];
            $evaluate_goods_info['geval_frommemberid'] = $member_id;
            $evaluate_goods_info['geval_frommembername'] = $member_name;
            $evaluate_goods_info['geval_image'] = serialize($evaluate_goods_img_array);

            $evaluate_goods_array[] = $evaluate_goods_info;

            $goodsid_array[] = $value['goods_id'];
        }

        $model_evaluate_goods->addEvaluateGoodsArray($evaluate_goods_array, $goodsid_array);

        $store_desccredit = intval($add_data['store_desccredit']);
        if ($store_desccredit <= 0 || $store_desccredit > 5) {
            $store_desccredit = 5;
        }
        $store_servicecredit = intval($add_data['store_servicecredit']);
        if ($store_servicecredit <= 0 || $store_servicecredit > 5) {
            $store_servicecredit = 5;
        }
        $store_deliverycredit = intval($add_data['store_deliverycredit']);
        if ($store_deliverycredit <= 0 || $store_deliverycredit > 5) {
            $store_deliverycredit = 5;
        }

        //添加店铺评价
        if (!$store_info['is_own_shop']) {
            $model_evaluate_store = Model('evaluate_store');

            $evaluate_store_info = array();
            $evaluate_store_info['seval_orderid'] = $order_id;
            $evaluate_store_info['seval_orderno'] = $order_info['order_sn'];
            $evaluate_store_info['seval_addtime'] = time();
            $evaluate_store_info['seval_storeid'] = $store_info['store_id'];
            $evaluate_store_info['seval_storename'] = $store_info['store_name'];
            $evaluate_store_info['seval_memberid'] = $member_id;
            $evaluate_store_info['seval_membername'] = $member_name;
            $evaluate_store_info['seval_desccredit'] = $store_desccredit;
            $evaluate_store_info['seval_servicecredit'] = $store_servicecredit;
            $evaluate_store_info['seval_deliverycredit'] = $store_deliverycredit;

            $model_evaluate_store->addEvaluateStore($evaluate_store_info);
        }


        //更新订单信息并记录订单日志
        $state = $model_order->editOrder(array('evaluation_state' => 1), array('order_id' => $order_id));
        $model_order->editOrderCommon(array('evaluation_time' => TIMESTAMP), array('order_id' => $order_id));
        if ($state) {
            $data = array();
            $data['order_id'] = $order_id;
            $data['log_role'] = 'buyer';
            $data['log_msg'] = L('order_log_eval');
            $model_order->addOrderLog($data);
        }

        //添加会员积分
//        if (C('points_isuse') == 1) {
//            $points_model = Model('points');
//            $points_model->savePointsLog('comments', array('pl_memberid' => $member_id, 'pl_membername' => $member_name));
//        }

        //添加会员经验值
//        Model('exppoints')->saveExppointsLog('comments', array('exp_memberid' => $member_id, 'exp_membername' => $member_name));

        $result['state'] = 1;
        return $result;
    }
}
