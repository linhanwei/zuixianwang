<?php
/**
 * 活动申请管理
 *
 *
 *
 *
 *
 */
defined('InSystem') or exit('Access Invalid!');
class activity_applyModel extends Model {

    public function __construct() {
        parent::__construct('activity_apply');
    }

    /**
     * 查询列表
     *
     * @param array $condition 查询条件
     * @param int $page 分页数
     * @param string $order 排序
     * @param string $field 字段
     * @param string $limit 取多少条
     * @return array
     */
    public function getApplyList($condition, $page = null, $order = '', $field = '*', $limit = '') {
        $result = $this->field($field)->where($condition)->order($order)->limit($limit)->page($page)->select();
        return $result;
    }


    /**
     * 数量
     * @param array $condition
     * @return int
     */
    public function getApplyCount($condition) {
        return $this->where($condition)->count();
    }


    /**
     * 查询信息
     *
     * @param array $condition 查询条件
     * @return array
     */
    public function getApplyInfo($condition) {
        $project_info = $this->where($condition)->find();
        return $project_info;
    }

    /**
     * 通过编号查询信息
     *
     * @param int $apply_id 编号
     * @return array
     */
    public function getApplyInfoByID($apply_id) {
        $key = md5('activity_apply_info_' . $apply_id);
        $project_info = rkcache($key);
        if(empty($project_info)) {
            $project_info = $this->getApplyInfo(array('apply_id' => $apply_id));
            wkcache($key, $project_info, 60 * 24);
        }
        return $project_info;
    }

    /*
     * 添加
     *
     * @param array $param 信息
     * @return bool
     */
    public function addApply($param){
        return $this->insert($param);
    }

    /*
     * 编辑
     *
     * @param array $update 更新信息
     * @param array $condition 条件
     * @return bool
     */
    public function editApply($update, $condition){
        //清空缓存
        $apply_list = $this->getApplyList($condition);
        foreach ($apply_list as $value) {
            $key = md5('activity_apply_info_' . $value['apply_id']);
            dkcache($key);
        }

        return $this->where($condition)->update($update);
    }

    /*
     * 删除
     *
     * @param array $condition 条件
     * @return bool
     */
    public function delApply($condition){
        $apply_info = $this->getApplyInfo($condition);

        //清空缓存
        $key = md5('activity_apply_info_' . $apply_info['apply_id']);
        dkcache($key);

        return $this->where($condition)->delete();
    }
}
