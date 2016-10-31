<?php
/**
 * 地区模型
 *
 *
 */
defined('InSystem') or exit('Access Invalid!');

class member_bankModel extends Model {

    public function __construct() {
        parent::__construct('member_bank');
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
     * 增加银行卡
     *
     * @param
     * @return int
     */
    public function addBank($data) {
        // 银行卡验证
        $obj_validate = new Validate();
        if($data['card_type'] == '1'){
            $validate = array(
                array("input"=>$data["member_id"],		"require"=>"true",		"message"=>'没有登录'),
                array("input"=>$data["card_type"],		"require"=>"true",		"message"=>'请选择卡片类型'),
                array("input"=>$data["bank_name"],		"require"=>"true",		"message"=>'请选择开户银行'),
                array("input"=>$data["account_name"],		"require"=>"true",		"message"=>'请输入开户姓名'),
                array("input"=>$data["account_no"],		"require"=>"true",		"message"=>'请输入银行帐号'),
                array("input"=>$data["idcard"],		"require"=>"true",		"message"=>'身份证号不能为空'),
                array("input"=>$data["bank_mobile"],		"require"=>"true","validator"=>"mobile",		"message"=>'请输入银行预留手机号码'),
            );
        }elseif($data['card_type'] == '2'){
            $validate = array(
                array("input"=>$data["member_id"],		"require"=>"true",		"message"=>'没有登录'),
                array("input"=>$data["card_type"],		"require"=>"true",		"message"=>'请选择卡片类型'),
                array("input"=>$data["bank_name"],		"require"=>"true",		"message"=>'请选择开户银行'),
                array("input"=>$data["account_name"],		"require"=>"true",		"message"=>'请输入开户姓名'),
                array("input"=>$data["account_no"],		"require"=>"true",		"message"=>'请输入银行帐号'),
                array("input"=>$data["idcard"],		"require"=>"true",		"message"=>'身份证号不能为空'),
                array("input"=>$data["bank_mobile"],		"require"=>"true","validator"=>"mobile",		"message"=>'请输入银行预留手机号码'),
                array("input"=>$data["province_name"],		"require"=>"true",		"message"=>'请选择开户省级'),
                array("input"=>$data["city_name"],		"require"=>"true",		"message"=>'请选择开户市级'),
                array("input"=>$data["branch_name"],		"require"=>"true",		"message"=>'请选择开户支行'),
            );
        }else{
            throw new Exception('请选择卡片类型');
        }

        $obj_validate->validateparam = $validate;
        $error = $obj_validate->validate();
        if ($error != ''){
            throw new Exception($error);
        }

        if($this->getBankInfo(array('account_no'=>$data['account_no']))){
            throw new Exception('帐号已添加');
        }

        $data['create_time'] = TIMESTAMP;
        return $this->insert($data);
    }

    /**
     *
     * 修改银行卡
     *
     * @param $update
     * @param $condition
     * @return mixed
     */
    public function editBank($update,$condition){
        return $this->where($condition)->update($update);
    }

    /**
     *
     * 删除银行卡
     *
     * @param $id
     * @return mixed
     */
    public function delBank($id){
        if($id > 0){
            $condition['id'] = $id;
            return $this->where($condition)->delete();
        }
    }
}
