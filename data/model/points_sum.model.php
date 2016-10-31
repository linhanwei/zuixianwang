<?php
/**
 * 每天会员积分数
 *
 *
 *
 *
 *
 */
defined('InSystem') or exit('Access Invalid!');

class points_sumModel extends Model{

    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * 统计当天当前用户的统计记录
     *
     * @param $member_id
     * @param $member_name
     * @param int $points
     * @return bool|int
     * @throws Exception
     */
    public function saveSum($member_id,$member_name,$points = 0){
        $today = strtotime(date('Y-m-d 00:00:00'));

        $condition = array();
        $condition['pd_memberid'] = $member_id;
        $condition['pd_addtime'] = $today;
        $today_sum = $this->getSumInfo($condition);

        if($today_sum){
            $update_data = array();
            $update_data['pd_points'] = array('exp','pd_points+'.$points);
            return $this->editSum($condition,$update_data);
        }else{
            $data = array();
            $data['pd_memberid'] = $member_id;
            $data['pd_membername'] = $member_name;
            $data['pd_points'] = $points;
            $data['pd_addtime'] = $today;
            $data['pd_days'] = 7300;
            $data['pd_pay'] = 0;
            $data['pd_less'] = $data['pd_days'];
            $data['pd_price'] = 0;              //首次返还计算
            $data['pd_last_day'] = $today;

            return $this->addSum($data);
        }
    }

	/**
	 * 增加日统计
	 *
	 * @param
	 * @return int
	 */
	public function addSum($data) {
		return $this->table('points_log_sum')->insert($data);
	}

	/**
	 * 删除日统计
	 *
	 * @param
	 * @return bool
	 */
	public function delSum($condition) {
		if (empty($condition)) {
			return false;
		} else {
			$result = $this->table('points_log_sum')->where($condition)->delete();
			return $result;
		}
	}


	/**
	 * 修改日统计
	 *
	 * @param
	 * @return bool
	 */
	public function editSum($condition, $data) {
		if (empty($condition)) {
			return false;
		}

		if (is_array($data)) {
			$result = $this->table('points_log_sum')->where($condition)->update($data);
			return $result;
		} else {
			return false;
		}
	}


    /**
     * 日统计记录
     *
     * @param array $condition
     * @param string $page
     * @param string $limit
     * @param string $fields
     * @param string $group
     * @return mixed
     */
    public function getSumList($condition = array(), $page = '', $limit = '', $fields = '*',$group = '') {
		$result = $this->table('points_log_sum')->field($fields)->group($group)->where($condition)->page($page)->limit($limit)->order('pd_pay desc')->select();
		return $result;
    }

    /**
     * 获取统计详情
     *
     * @return mixed
     */
    public function getSumInfo($condition = array(), $fileds = '*') {
        return $this->table('points_log_sum')->where($condition)->field($fileds)->find();
    }
}