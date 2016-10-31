<?php
/**
 * 合粉公益管理
 *
 * 
 *
 *
 */
defined('InSystem') or exit('Access Invalid!');

class fund_logModel extends Model {
    public function __construct() {
        parent::__construct('fund_log');
    }
    /**
     *
     * 添加捐赠记录
     *
     * @param $data
     * @return mixed
     */
    public function addFundLog($data){
        return $this->insert($data);
    }

    /**
     * 编辑记录
     * @param array $data
     * @param array $condition
     * @return mixed
     */
    public function editFundLog($data,$condition = array()) {
        return $this->where($condition)->update($data);
    }

    /**
     * 取得单条捐赠记录
     * @param array $condition
     * @param string $fields
     */
    public function getFundLogInfo($condition = array(), $fields = '*') {
        return $this->where($condition)->field($fields)->find();
    }

    /**
     * 取得捐赠列表
     * @param array $condition
     * @param string $pagesize
     * @param string $fields
     * @param string $order
     */
    public function getFundLogList($condition = array(), $pagesize = '', $fields = '*', $order = '', $limit = '') {
        return $this->where($condition)->field($fields)->order($order)->limit($limit)->page($pagesize)->select();
    }

    /**
     * 删除捐赠记录
     * @param unknown $condition
     */
    public function delRecharge($condition) {
        return $this->where($condition)->delete();
    }

}