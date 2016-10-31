<?php
/**
 * 区域人员关联表
 *
 *
 */
defined('InSystem') or exit('Access Invalid!');
class agent_memberModel extends Model
{
    public function __construct() {
        parent::__construct('agent_member');
    }

    /**
     * 列表
     *
     * @return mixed
     */
    public function getAgentMemberList($condition = array(), $pagesize = '', $fields = '*', $order = '', $limit = '') {
        return $this->where($condition)->field($fields)->order($order)->limit($limit)->page($pagesize)->select();
    }

    /**
     * 获取详情
     *
     * @return mixed
     */
    public function getAgentMemberInfo($condition = array(), $fileds = '*') {
        return $this->where($condition)->field($fileds)->find();
    }

    /**
     * 添加加盟商
     *
     * @param
     * @return int
     */
    public function addAgentMember($data) {
        return $this->insert($data);
    }

    /**
     *
     * 修改
     *
     * @param $update
     * @param $condition
     * @return mixed
     */
    public function editAgentMember($update,$condition){
        return $this->where($condition)->update($update);
    }

    /**
     *
     * 删除
     *
     * @param $id
     * @return mixed
     */
    public function delAgentMember($id){
        if($id > 0){
            $condition['id'] = $id;
            return $this->where($condition)->delete();
        }
    }

    /**
     *
     * 数量
     * @param $codition
     */
    public function getAgentMemberCount($codition){
        return $this->where($codition)->count();
    }

    /**
     *
     * 获得代理信息
     * @param $member_id
     */
    public function getAgentInfo($member_id){
        $agent_member = Model('agent_member')->getAgentMemberInfo(array('member_id'=>$member_id));
        return Model('agent')->getAgentInfo(array('agent_id'=>$agent_member['agent_id']));
    }

    /**
     *
     * 获得所在区代理
     * @param $member_id
     */
    public function getAreaAgentInfo($member_id){

    }
}