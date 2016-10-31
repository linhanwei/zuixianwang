<?php
/**
 * 会员油卡充值管理
 *
 * 
 *
 *
 */
defined('InSystem') or exit('Access Invalid!');

class oil_logModel extends Model {
    public function __construct() {
        parent::__construct('oil_log');
    }
    /**
     *
     * 充值油卡记录
     *
     * @param $data
     * @return mixed
     */
    public function addOilLog($data){
        return $this->insert($data);
    }

    /**
     * 编辑油卡充值记录
     * @param array $data
     * @param array $condition
     * @return mixed
     */
    public function editOilLog($data,$condition = array()) {
        return $this->where($condition)->update($data);
    }

    /**
     * 取得单条充值记录
     * @param array $condition
     * @param string $fields
     */
    public function getOilLogInfo($condition = array(), $fields = '*') {
        return $this->where($condition)->field($fields)->find();
    }

    /**
     * 取得油卡充值列表
     * @param array $condition
     * @param string $pagesize
     * @param string $fields
     * @param string $order
     */
    public function getOilLogList($condition = array(), $pagesize = '', $fields = '*', $order = '', $limit = '') {
        return $this->where($condition)->field($fields)->order($order)->limit($limit)->page($pagesize)->select();
    }

    /**
     * 删除充值记录
     * @param unknown $condition
     */
    public function delRecharge($condition) {
        return $this->where($condition)->delete();
    }

}