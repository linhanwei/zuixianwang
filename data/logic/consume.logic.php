<?php
/**
 * 消费行为
 *
 * 
 */
defined('InSystem') or exit('Access Invalid!');
class consumeLogic {
    /**
     *
     * 商城消费返还
     * @param $from_member_id
     * @param $from_member_name
     * @param $to_member_id
     * @param $to_member_name
     * @param $pdt_amount
     * @param $pdt_remark
     * @param $is_store
     * @return bool
     */
	public function consume($from_member_id,$from_member_name,
							$to_member_id,$to_member_name,
							$pdt_amount,$pdt_remark,$is_store = true){
		$model_pd = Model('predeposit');
        $transfer_info = array();
        $transfer_info['pdt_sn'] = $pay_sn = $model_pd->makeSn();
        $transfer_info['pdt_from_member_id'] = $from_member_id;
        $transfer_info['pdt_from_member_name'] = $from_member_name;
        $transfer_info['pdt_to_member_id'] = $to_member_id;
        $transfer_info['pdt_to_member_name'] = $to_member_name;

        //消费计算手续费
        //15％手续费
        if($is_store){
            $transfer_info['pdt_amount_rate'] = CONSUME_RATE;
            $transfer_info['pdt_amount_out'] = $pdt_amount * $transfer_info['pdt_amount_rate'];
            $transfer_info['pdt_amount_get'] = round($pdt_amount - $transfer_info['pdt_amount_out'],2);
        }

        $transfer_info['pdt_amount'] = $pdt_amount;
        $transfer_info['pdt_add_time'] = TIMESTAMP;
        $transfer_info['pdt_remark'] = $pdt_remark;
		$model_pd->beginTransaction();
        try {
            $insert = $model_pd->addPdTransfer($transfer_info);
            if($insert){
                //转出变更
                $data = array();
                $data['member_id'] = $transfer_info['pdt_from_member_id'];
                $data['member_name'] = $transfer_info['pdt_from_member_name'];

                $data['amount'] = -1 * $transfer_info['pdt_amount'];

                $data['pdt_sn'] = $transfer_info['pdt_sn'];
                $data['lg_desc'] = '向[' . $transfer_info['pdt_to_member_name'] . ']转帐,单号:'.$data['pdt_sn'];
                if($is_store){
                    $data['lg_desc'] = '向[' . $transfer_info['pdt_to_member_name'] . ']消费,单号:'.$data['pdt_sn'];
                }

                if(abs($data['amount'])>0){
                    $model_pd->changePd('transfer',$data);
                }else{
                    $model_pd->rollback();
                    throw new Exception('转帐出错');
                }

                //商户返会员积分，推荐提成
                //收入变更
                if($is_store){
                    //消费者上级佣金
                    Logic('inviter')->buyerCommis($transfer_info['pdt_from_member_id'],$transfer_info['pdt_from_member_name'],$pdt_amount,$transfer_info['pdt_sn']);

                    //变更家商帐户余额
                    //收款85%
                    $data = array();
                    $data['member_id'] = $transfer_info['pdt_to_member_id'];
                    $data['member_name'] = $transfer_info['pdt_to_member_name'];
                    $data['amount'] = $transfer_info['pdt_amount_get'];                 //实际获得85%
                    $data['pdt_sn'] = $transfer_info['pdt_sn'];
                    $data['lg_desc'] = '收到[' . $transfer_info['pdt_from_member_name'] . ']货款,单号:'.$data['pdt_sn'];
                    $data['lg_desc'] .= ',金额:' . $pdt_amount . ',手续费:' . $transfer_info['pdt_amount_rate'] * 100 . '%';
                    $model_pd->changePd('transfer',$data);

                    //会员获得消费会员积分
                    $pointsArray = array(
                        'pl_desc'=>'消费获得会员积分,单号:'.$data['pdt_sn'],
                        'pl_memberid'=>$from_member_id,
                        'pl_membername'=>$from_member_name,
                        'pl_sn'=>$data['pdt_sn'],
                        'transfer_amount'=>$pdt_amount
                    );
                    Model('points')->savePointsLog('transfer',$pointsArray,true);

                    //商家上级佣金
                    Logic('inviter')->sellerCommis($transfer_info['pdt_to_member_id'],$transfer_info['pdt_to_member_name'],$transfer_info['pdt_amount'],$transfer_info['pdt_sn']);
                }else{  //一般会员更变
                    $data = array();
                    $data['member_id'] = $transfer_info['pdt_to_member_id'];
                    $data['member_name'] = $transfer_info['pdt_to_member_name'];
                    $data['amount'] = floatval($transfer_info['pdt_amount']);
                    $data['pdt_sn'] = $transfer_info['pdt_sn'];
                    $data['lg_desc'] = '收到[' . $transfer_info['pdt_from_member_name'] . ' 转帐,单号:'.$data['pdt_sn'];
                    $model_pd->changePd('transfer',$data);
                }

                $model_pd->commit();

                return $data['amount'];
            }
        } catch (Exception $e) {
            $model_pd->rollback();
            return false;
        }
	}

		
		
} 