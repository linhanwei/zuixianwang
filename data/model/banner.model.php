<?php

/**
 * 系统banner
 *
 * 
 *
 *
 */
defined('InSystem') or exit('Access Invalid!');

class bannerModel extends Model{

    public function __construct() {
        parent::__construct('banner');
    }

    /**
     * 取单个内容
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getInfo($condition, $field = '*') {
        return $this->field($field)->where($condition)->find();
    }

    /**
     * 根据编号查询一条
     * 
     * @param unknown_type $id
     */
    public function getOneById($id) {
        $condition['id'] = $id;
        $result = $this->getInfo($condition,'*');
        return $result;
    }

    /**
     * 列表
     * @param array $condition
     * @param string $field
     * @param string $order
     * @param number $page
     * @param string $limit
     * @return array
     */
    public function getList($condition, $field = '*', $page = 0, $order = 'edit_time desc', $limit = '') {
        return $this->where($condition)->field($field)->order($order)->page($page)->limit($limit)->select();
    }
    
    /**
     * 获取符合类型banner列表
     * @param array $condition
     * @param string $field
     * @param string $order
     * @param number $page
     * @param string $limit
     * @return array
     */
    public function getTypeAllList($condition, $field = '*', $order = 'edit_time desc') {
        $condition['is_show'] = 1;
        $list = $this->where($condition)->field($field)->order($order)->select();
        
        if($list){
            foreach ($list as $k => $v) {
                $list[$k]['image_name'] = getBannerImageUrl($v['image_name']);
            }
        }
        return $list;
    }

    /**
     * 添加
     * @param array $insert
     * @return boolean
     */
    public function addData($insert) {
        $time = time();
        $insert['edit_time'] = $time;
        $insert['add_time'] = $time;
        return $this->insert($insert);
    }

    /**
     * 更新
     * @param array $condition
     * @param array $update
     * @return boolean
     */
    public function editData($condition, $update) {
       
        $update['edit_time'] = time();
        return $this->where($condition)->update($update);
    }
    
    /**
     * 删除
     * @param type $where
     */
    public function delData($where){
        return $this->where($where)->delete();
    }

}
