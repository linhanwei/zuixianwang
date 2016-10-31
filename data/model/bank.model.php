<?php
/**
 * 地区模型
 *
 *
 */
defined('InSystem') or exit('Access Invalid!');

class bankModel extends Model {

    public function __construct() {
        parent::__construct('bank');
    }

    /**
     * 获取地址列表
     *
     * @return mixed
     */
    public function getBankList($condition = array(), $fields = '*', $group = '') {
        return $this->where($condition)->field($fields)->limit(false)->group($group)->select();
    }

    /**
     * 获取地址详情
     *
     * @return mixed
     */
    public function getBankInfo($condition = array(), $fileds = '*') {
        return $this->where($condition)->field($fileds)->find();
    }


    /**
     * 获取地区缓存
     *
     * @return array
     */
    public function getBanks() {
        return $this->getCache();
    }


    /**
     * @return array|mixed
     * @throws Exception
     */
    protected function getCache() {
        // 对象属性中有数据则返回
        if ($this->cachedData !== null)
            return $this->cachedData;

        // 缓存中有数据则返回
        if ($data = rkcache('bank')) {
            $this->cachedData = $data;
            return $data;
        }

        // 查库
        $data = array();
        $bank_all_array = $this->limit(false)->select();

        foreach ((array) $bank_all_array as $a) {
            $data[$a['bank_id']] = $a['bank_name'];
        }

        wkcache('bank', $data);
        $this->cachedData = $data;

        return $data;
    }

    protected $cachedData;
}
