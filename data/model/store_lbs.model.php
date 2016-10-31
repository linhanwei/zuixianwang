<?php
/**
 * 商户(LBS)管理
 *
 *
 *
 *
 *
 */
defined('InSystem') or exit('Access Invalid!');
class store_lbsModel extends Model {

    public function __construct() {
        parent::__construct('store_lbs');
    }

	/**
	 * 查询商户列表
     *
	 * @param array $condition 查询条件
	 * @param int $page 分页数
	 * @param string $order 排序
	 * @param string $field 字段
	 * @param string $limit 取多少条
     * @return array
	 */
    public function getStoreList($condition, $page = null, $order = '', $field = '*', $limit = '') {
        $result = $this->field($field)->where($condition)->order($order)->limit($limit)->page($page)->select();
        return $result;
    }

	/**
	 * 查询有效商户列表
     *
	 * @param array $condition 查询条件
	 * @param int $page 分页数
	 * @param string $order 排序
	 * @param string $field 字段
     * @return array
	 */
    public function getStoreOnlineList($condition, $page = null, $order = '', $field = '*') {
        $condition['store_state'] = 1;
        return $this->getStoreList($condition, $page, $order, $field);
    }

    /**
     * 商户数量
     * @param array $condition
     * @return int
     */
    public function getStoreCount($condition) {
        return $this->where($condition)->count();
    }


    /**
	 * 查询商户信息
     *
	 * @param array $condition 查询条件
     * @return array
	 */
    public function getStoreInfo($condition) {
        $store_info = $this->where($condition)->find();
        return $store_info;
    }

    /**
	 * 通过商户编号查询商户信息
     *
	 * @param int $store_id 商户编号
     * @return array
	 */
    public function getStoreInfoByID($store_id) {
        $key = md5('store_lbs_info_' . $store_id);
        $store_info = rkcache($key);
        if(empty($store_info)) {
            $store_info = $this->getStoreInfo(array('store_id' => $store_id));
            wkcache($key, $store_info, 60 * 24);
        }
        return $store_info;
    }

	/*
	 * 添加商户
     *
	 * @param array $param 商户信息
	 * @return bool
	 */
    public function addStore($param){
        return $this->insert($param);
    }

	/*
	 * 编辑商户
     *
	 * @param array $update 更新信息
	 * @param array $condition 条件
	 * @return bool
	 */
    public function editStore($update, $condition){
        //清空缓存
        $store_list = $this->getStoreList($condition);
        foreach ($store_list as $value) {
            $key = md5('store_lbs_info_' . $value['store_id']);
            dkcache($key);
        }

        return $this->where($condition)->update($update);
    }

	/*
	 * 删除商户
     *
	 * @param array $condition 条件
	 * @return bool
	 */
    public function delStore($condition){
        $store_info = $this->getStoreInfo($condition);
        //删除商户相关图片
        @unlink(BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.'lbs'.DS.$store_info['store_avatar']);
        @unlink(BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.'lbs'.DS.$store_info['banner_1']);
        @unlink(BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.'lbs'.DS.$store_info['banner_2']);
        @unlink(BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.'lbs'.DS.$store_info['banner_3']);
        @unlink(BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.'lbs'.DS.$store_info['banner_4']);
        @unlink(BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.'lbs'.DS.$store_info['banner_5']);

        //清空缓存
        $key = md5('store_lbs_info_' . $store_info['store_id']);
        dkcache($key);

        return $this->where($condition)->delete();
    }
}
