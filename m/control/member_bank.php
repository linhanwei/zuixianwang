<?php
/**
 * 会员银行相关
 *
 *
 *
 *
 */

defined('InSystem') or exit('Access Invalid!');

class member_bankControl extends mobileMemberControl{

    public function __construct() {
        parent::__construct();
    }

    /**
     * 可用银行列表
     */
    public function bankListOp() {
        $model_bank = Model('bank');
        $bank_list = $model_bank->getBanks();
        output_data(array('bank_list' => $bank_list));
    }

    /**
     * 我的银行卡
     */
    public function myBankListOp(){
        $model_member_bank = Model('member_bank');
        $member_id = $this->member_info['member_id'];

        $condition = array();
        $condition['member_id'] = $member_id;
        $my_bank_list = $model_member_bank->getBankList($condition);

        if($my_bank_list){
            foreach($my_bank_list as $key=>$val){
                if($val['card_type'] == 1) {
                    $my_bank_list[$key]['card_type_name'] = '信用卡';
                    //unset($my_bank_list[$key]['province_id']);
                    //unset($my_bank_list[$key]['city_id']);
                    //unset($my_bank_list[$key]['bank_name']);
                }elseif($val['card_type'] == '2') {
                    $my_bank_list[$key]['card_type_name'] = '借记卡';
                }

                $my_bank_list[$key]['account_no'] = substr($val['account_no'],-4);
            }
        }
        output_data(array('bank_list'=>$my_bank_list));
    }

    /**
     * 增加银行卡
     */
    public function addBankOp(){
        $data = array();
        $data['member_id'] = $this->member_info['member_id'];
        $data['card_type'] = $_POST['card_type'];
        $data['bank_name'] = $_POST['bank_name'];
        $data['account_name'] = $_POST['account_name'];
        $data['account_no'] = $_POST['account_no'];
        $data['idcard'] = $_POST['idcard'];
        $data['bank_mobile'] = $_POST['bank_mobile'];
        $data['province_name'] = $_POST['province_name'];
        $data['city_name'] = $_POST['city_name'];
        $data['branch_name'] = $_POST['branch_name'];

        try{
            $id = Model('member_bank')->addBank($data);
            output_data(array('id'=>$id));
        }catch (Exception $ex){
            output_error($ex->getMessage());
        }
    }

    /**
     * 删除银行卡
     */
    public function delBankOp(){
        $id = $_POST['id'];
        Model('member_bank')->delBank($id);
        output_data(array('id'=>$id));
    }
}
