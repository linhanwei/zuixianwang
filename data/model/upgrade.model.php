<?php
/**
 * 升级管理
 *
 * 
 *
 *
 */
defined('InSystem') or exit('Access Invalid!');

class upgradeModel extends Model {
    public function __construct() {
        parent::__construct('pd_upgrade');
    }

    /**
     * 取得单条充值记录
     * @param array $condition
     * @param string $fields
     */
    public function getUpgradeInfo($condition = array(), $fields = '*') {
        return $this->where($condition)->field($fields)->find();
    }

    /**
     * 取得列表
     * @param array $condition
     * @param string $pagesize
     * @param string $fields
     * @param string $order
     */
    public function getUpgradeList($condition = array(), $pagesize = '', $fields = '*', $order = '', $limit = '') {
        return $this->where($condition)->field($fields)->order($order)->limit($limit)->page($pagesize)->select();
    }

    /**
     * 最后一次升级记录
     * @param $member_id
     * @return array
     */
    public function getLastUpgrade($member_id){
        $condition['pu_member_id'] = $member_id;
        $condition['pu_payment_state']  = 1;

        $list = $this->getUpgradeList($condition,1,'*','pu_payment_time DESC','1');

        if($list){
            return $list[0];
        }
        return array();
    }
}