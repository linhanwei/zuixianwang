<?php
/**
 * 电子消费券
 */

defined('InSystem') or exit('Access Invalid!');

class couponsModel extends Model
{
    public function __construct()
    {
        parent::__construct('coupons');
    }

    /**
     * 获取列表
     *
     * @param array $condition 条件数组
     * @param int $pageSize 分页长度
     *
     * @return array 列表
     */
    public function getCouponsList($condition, $pageSize = 20, $limit = null)
    {
        if ($condition) {
            $this->where($condition);
        }

        $this->order('id desc');

        if ($limit) {
            $this->limit($limit);
        } else {
            $this->page($pageSize);
        }


        return $this->select();
    }

    /**
     * 通过卡号获取单条数据
     *
     * @param string $sn 卡号
     *
     * @return array|null 数据
     */
    public function getCouponsBySN($sn)
    {
        return $this->where(array(
            'sn' => (string) $sn,
        ))->find();
    }

    /**
     * 设置为已使用
     *
     * @param int $id 表字增ID
     * @param int $memberId 会员ID
     * @param string $memberName 会员名称
     * @param int $store_id 所在店铺
     *
     * @return boolean
     */
    public function setCouponsUsedById($id, $memberId, $memberName,$store_id)
    {
        return $this->where(array(
            'id' => (string) $id,
        ))->update(array(
            'use_at' => TIMESTAMP,
            'state' => 1,
            'member_id' => $memberId,
            'member_name' => $memberName,
            'store_id' =>$store_id
        ));
    }

    /**
     * 通过ID删除（自动添加未使用标记）
     *
     * @param int|array $id 表字增ID(s)
     *
     * @return boolean
     */
    public function delCouponsById($id)
    {
        return $this->where(array(
            'id' => array('in', (array) $id),
            'state' => 0,
        ))->delete();
    }

    /**
     * 通过给定的卡号数组过滤出来不能被新插入的卡号（卡号存在的）
     *
     * @param array $sns 卡号数组
     *
     * @return array
     */
    public function getOccupiedCouponsSNsBySNs(array $sns)
    {
        $array = $this->field('sn')->where(array(
            'sn' => array('in', $sns),
        ))->select();

        $data = array();

        foreach ((array) $array as $v) {
            $data[] = $v['sn'];
        }

        return $data;
    }

    public function getCouponsCount($condition) {
        return $this->where($condition)->count();
    }
}
