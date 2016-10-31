<?php
/**
 * 区域
 *
 *
 */
defined('InSystem') or exit('Access Invalid!');
class agent_areaModel extends Model
{
    public function __construct() {
        parent::__construct('agent_area');
    }

    /**
     * 获得可用的区域列表
     */
    public function getUsableAgentAreaList4Agent(){
        $list = $this->getAgentAreaList();

        if($list){
            $model_agent = Model('agent');
            foreach($list as $key=>$val){
                $condition['aa_id'] = $val['aa_id'];
                $condition['area_id'] = 0;
                if($model_agent->getAgentCount($condition) >= $val['aa_limit']){
                    unset($list[$key]);
                }
            }
        }

        return $list;
    }

    /**
     * 获取区域列表
     *
     * @return mixed
     */
    public function getAgentAreaList($condition = array(), $pagesize = '', $fields = '*', $order = '', $limit = '') {
        return $this->where($condition)->field($fields)->order($order)->limit($limit)->page($pagesize)->select();
    }

    /**
     * 获取区域详情
     *
     * @return mixed
     */
    public function getAgentAreaInfo($condition = array(), $fileds = '*') {
        return $this->where($condition)->field($fileds)->find();
    }

    /**
     * 发送信息
     *
     * @param
     * @return int
     */
    public function addAgentArea($data) {
        return $this->insert($data);
    }

    /**
     *
     * 修改留言
     *
     * @param $update
     * @param $condition
     * @return mixed
     */
    public function editAgentArea($update,$condition){
        return $this->where($condition)->update($update);
    }

    /**
     *
     * 删除留言
     *
     * @param $id
     * @return mixed
     */
    public function delAgentArea($id){
        if($id > 0){
            $condition['aa_id'] = $id;
            return $this->where($condition)->delete();
        }
    }

    /**
     *
     * 判断area_id 是否已使用
     *
     * @param $area_id
     * @param $aa_id
     * @return bool
     */
    public function existAreaID($area_id,$aa_id=0){
        if($aa_id>0){
            $condition['aa_id'] = array('neq',$aa_id);
        }

        $list = $this->getAgentAreaList($condition);

        foreach($list as $k=>$v){
            if($v['aa_area']){
                $aa_area = explode(',',$v['aa_area']);
                if(in_array($area_id,$aa_area)){
                    return true;
                }
            }
        }

        return false;
    }

    /**
     *
     * 获得所在区域
     * @param $province_id
     * @return array()
     */
    public function getMemberArea($province_id){
        $agent_area_list = $this->getAgentAreaList();

        $agent_area = array();
        foreach($agent_area_list as $val){
            if($val['aa_area'] == 'all'){
                $agent_area_all = $val;
                break;
            }
            $aa_area = explode(',',$val['aa_area']);
            if(in_array($province_id,$aa_area)){
                $agent_area = $val;
                break;
            }
        }

        if(empty($agent_area)){
            $agent_area = $agent_area_all;
        }

        return $agent_area;
    }
}