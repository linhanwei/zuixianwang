<?php
/**
 * 奖励管理
 *
 *
 *
 *
 *
 */
defined('InSystem') or exit('Access Invalid!');

class points_inviterModel {
	/**
	 * 操作奖励
	 * @param  string $stage 操作阶段 regist(注册),login(登录),comments(评论),order(下单),system(系统),other(其他),pointorder(奖励礼品兑换),app(同步奖励兑换)
	 * @param  array $insertarr 该数组可能包含信息 array('pl_memberid'=>'会员编号','pl_membername'=>'会员名称','pl_adminid'=>'管理员编号','pl_adminname'=>'管理员名称','pl_points'=>'奖励','pl_desc'=>'描述','orderprice'=>'订单金额','order_sn'=>'订单编号','order_id'=>'订单序号','point_ordersn'=>'奖励兑换订单编号');
	 * @param  bool $if_repeat 是否可以重复记录的信息,true可以重复记录，false不可以重复记录，默认为true
	 * @return bool
	 */
	function savePointsLog($stage,$insertarr,$if_repeat = true){
		if (!$insertarr['pl_memberid']){
			return false;
		}
		//记录原因文字
		switch ($stage){
			case 'rebate':
				if (!$insertarr['pl_desc']){
					$insertarr['pl_desc'] = '['.$_SESSION['member_name'].']消费获得佣金';
				}
				$insertarr['pl_points'] = $insertarr['rebate_amount'];
				break;
            case 'distill':
                if (!$insertarr['pl_desc']){
                    $insertarr['pl_desc'] = '提取至奖励';
                }
                $insertarr['pl_points'] = -1 * $insertarr['distill_amount'];
                break;
            case 'system':
                break;
			case 'other':
				break;
		}
		$save_sign = true;
		if ($if_repeat == false){
			//检测是否有相关信息存在，如果没有，入库
			$condition['pl_memberid'] = $insertarr['pl_memberid'];
			$condition['pl_stage'] = $stage;
			$log_array = self::getPointsInfo($condition,$page);
			if (!empty($log_array)){
				$save_sign = false;
			}
		}
		if ($save_sign == false){
			return true;
		}
		//新增日志
		$value_array = array();
		$value_array['pl_memberid'] = $insertarr['pl_memberid'];
		$value_array['pl_membername'] = $insertarr['pl_membername'];
		if ($insertarr['pl_adminid']){
			$value_array['pl_adminid'] = $insertarr['pl_adminid'];
		}
		if ($insertarr['pl_adminname']){
			$value_array['pl_adminname'] = $insertarr['pl_adminname'];
		}
		$value_array['pl_points'] = $insertarr['pl_points'];
		$value_array['pl_addtime'] = time();
		$value_array['pl_desc'] = $insertarr['pl_desc'];
		$value_array['pl_stage'] = $stage;
		$result = false;
		if($value_array['pl_points'] != 0){
			$result = self::addPointsLog($value_array);

            if ($result){
                //更新member内容
                $obj_member = Model('member');
                $upmember_array = array();
                $upmember_array['member_points_inviter'] = array('exp','member_points_inviter+'.$insertarr['pl_points']);

                //记录提取总数
                if($stage == 'distill'){
                    $upmember_array['member_points_inviter_2'] = array('exp','member_points_inviter_2+'.abs($insertarr['pl_points']));
                }
                $obj_member->editMember(array('member_id'=>$insertarr['pl_memberid']),$upmember_array);
            }else {
                throw new Exception('积分添加失败');
            }
		}


	}

	/**
	 * 奖励日志详细信息
	 *
	 * @param array $condition 条件数组
	 * @param array $field   查询字段
	 */
	public function getPointsInfo($condition,$field='*'){
		//得到条件语句
		$condition_str	= $this->getCondition($condition);
		$array			= array();
		$array['table']	= 'points_log_inviter';
		$array['where']	= $condition_str;
		$array['field']	= $field;
		$list		= Db::select($array);
		return $list[0];
	}

	/**
	 * 将条件数组组合为SQL语句的条件部分
	 *
	 * @param	array $condition_array
	 * @return	string
	 */
	private function getCondition($condition_array){
		$condition_sql = '';
		//奖励日志会员编号
		if ($condition_array['pl_memberid']) {
			$condition_sql	.= " and `points_log_inviter`.pl_memberid = '{$condition_array['pl_memberid']}'";
		}
		//操作阶段
		if ($condition_array['pl_stage']) {
			$condition_sql	.= " and `points_log_inviter`.pl_stage = '{$condition_array['pl_stage']}'";
		}
		//会员名称
		if ($condition_array['pl_membername_like']) {
			$condition_sql	.= " and `points_log_inviter`.pl_membername like '%{$condition_array['pl_membername_like']}%'";
		}
		//管理员名称
		if ($condition_array['pl_adminname_like']) {
			$condition_sql	.= " and `points_log_inviter`.pl_adminname like '%{$condition_array['pl_adminname_like']}%'";
		}
		//添加时间
		if ($condition_array['saddtime']){
			$condition_sql	.= " and `points_log_inviter`.pl_addtime >= '{$condition_array['saddtime']}'";
		}
		if ($condition_array['eaddtime']){
			$condition_sql	.= " and `points_log_inviter`.pl_addtime <= '{$condition_array['eaddtime']}'";
		}
		//描述
		if ($condition_array['pl_desc_like']){
			$condition_sql	.= " and `points_log_inviter`.pl_desc like '%{$condition_array['pl_desc_like']}%'";
		}
		return $condition_sql;
	}

	/**
	 * 添加奖励日志信息
	 *
	 * @param array $param 添加信息数组
	 */
	public function addPointsLog($param) {
		if(empty($param)) {
			return false;
		}
		$result	= Db::insert('points_log_inviter',$param);
		return $result;
	}

	/**
	 * 奖励日志列表
	 *
	 * @param array $condition 条件数组
	 * @param array $page   分页
	 * @param array $field   查询字段
	 * @param array $page   分页
	 */
	public function getPointsLogList($condition,$page='',$field='*'){
		$condition_str	= $this->getCondition($condition);
		$param	= array();
		$param['table']	= 'points_log_inviter';
		$param['where']	= $condition_str;
		$param['field'] = $field;
		$param['order'] = $condition['order'] ? $condition['order'] : 'points_log_inviter.pl_id desc';
		$param['limit'] = $condition['limit'];
		$param['group'] = $condition['group'];
		return Db::select($param,$page);
	}
}
