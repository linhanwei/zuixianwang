<?php
/**
 * 区域人员关联表
 *
 *
 */
defined('InSystem') or exit('Access Invalid!');
class agentModel extends Model
{
    public function __construct() {
        parent::__construct('agent');
    }

    /**
     * 列表
     *
     * @return mixed
     */
    public function getAgentList($condition = array(), $pagesize = '', $fields = '*', $order = '', $limit = '') {
        return $this->where($condition)->field($fields)->order($order)->limit($limit)->page($pagesize)->select();
    }

    /**
     * 获取详情
     *
     * @return mixed
     */
    public function getAgentInfo($condition = array(), $fileds = '*') {
        return $this->where($condition)->field($fileds)->find();
    }

    /**
     * 添加加盟商
     *
     * @param
     * @return int
     */
    public function addAgent($data) {
        return $this->insert($data);
    }

    /**
     *
     * 修改加盟商
     *
     * @param $update
     * @param $condition
     * @return mixed
     */
    public function editAgent($update,$condition){
        return $this->where($condition)->update($update);
    }

    /**
     *
     * 删除加盟商
     *
     * @param $id
     * @return mixed
     */
    public function delAgent($id){
        if($id > 0){
            $condition['agent_id'] = $id;
            return $this->where($condition)->delete();
        }
    }

    /**
     *
     * 数量
     * @param $codition
     */
    public function getAgentCount($codition){
        return $this->where($codition)->count();
    }
}