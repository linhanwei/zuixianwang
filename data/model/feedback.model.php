<?php
/**
 * 客服
 *
 *
 */
defined('InSystem') or exit('Access Invalid!');
class feedbackModel extends Model
{
    public function __construct() {
        parent::__construct('feedback');
    }

    /**
     * 获取地址列表
     *
     * @return mixed
     */
    public function getFeedbackList($condition = array(), $pagesize = '', $fields = '*', $order = '', $limit = '') {
        return $this->where($condition)->field($fields)->order($order)->limit($limit)->page($pagesize)->select();
    }

    /**
     * 获取地址详情
     *
     * @return mixed
     */
    public function getFeedbackInfo($condition = array(), $fileds = '*') {
        return $this->where($condition)->field($fileds)->find();
    }

    /**
     * 发送信息
     *
     * @param
     * @return int
     */
    public function addFeedback($data) {
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
    public function editFeedback($update,$condition){
        return $this->where($condition)->update($update);
    }

    /**
     *
     * 删除留言
     *
     * @param $id
     * @return mixed
     */
    public function delFeedback($id){
        if($id > 0){
            $condition['id'] = $id;
            return $this->where($condition)->delete();
        }
    }
}