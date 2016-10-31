<?php
/**
* 电子券
 */
defined('InSystem') or exit('Access Invalid!');

class couponsLogic {
    /**
     *
     * 升级发放电子券
     *
     * @param $member_id
     * @param $member_name
     */
    public function upgrade($member_id,$member_name){
        $batchflag = 'upgrade';
        $to_date = strtotime(date("Y-m-d",strtotime("+1 year")));
        $denomination = 200;

        for($i=0;$i<6;$i++){
            $this->_add($member_id,$member_name,'U-',$denomination,$batchflag,$to_date);
        }
    }

    /**
     *
     * 创建新的SN
     * @param $pre
     * @return string
     */
    private function _createSN($pre){
        $sn = $pre . random(4) . '-' . random(4) . '-' . random(4) . '-' . random(4);
        if(Model('coupons')->getCouponsBySN($sn)){
            $this->_createSN($sn);
        }
        return $sn;
    }

    /**
     *
     * 创建电子券
     *
     * @param $member_id
     * @param $member_name
     * @param $pre
     * @param $denomination
     * @param string $batchflag
     * @param int $to_date
     * @return bool
     */
    private function _add($member_id,$member_name,$pre,$denomination,$batchflag='',$to_date = 0){
        $adminName = 'system';

        $snToInsert = array();

        $snToInsert[] = array(
            'sn' => $this->_createSN($pre),
            'denomination' => $denomination,
            'batchflag' => $batchflag,
            'admin_name' => $adminName,
            'to_date'=>$to_date,
            'create_at' => TIMESTAMP,
            'member_id' => $member_id,
            'member_name' => $member_name
        );

        $model = Model('coupons');
        if (!$model->insertAll($snToInsert)) {
            return true;
        }
    }
}