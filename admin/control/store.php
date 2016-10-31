<?php
/**
 * 商户管理界面
 *
 ***/

defined('InSystem') or exit('Access Invalid!');

class storeControl extends SystemControl{
	const EXPORT_SIZE = 1000;
	public function __construct(){
		parent::__construct();
		Language::read('store');
	}

	/**
	 * 商户
	 */
	public function storeOp(){
		$lang = Language::getLangContent();

		$model_store = Model('store');

		if(trim($_GET['owner_and_name']) != ''){
			$condition['member_name']	= array('like', '%'.$_GET['owner_and_name'].'%');
			Tpl::output('owner_and_name',$_GET['owner_and_name']);
		}
		if(trim($_GET['store_name']) != ''){
			$condition['store_name']	= array('like', '%'.trim($_GET['store_name']).'%');
			Tpl::output('store_name',$_GET['store_name']);
		}

        switch ($_GET['store_type']) {
            case 'close':
                $condition['store_state'] = 0;
                break;
            case 'open':
                $condition['store_state'] = 1;
                break;
            case 'expired':
                $condition['store_end_time'] = array('between', array(1, TIMESTAMP));
                $condition['store_state'] = 1;
                break;
            case 'expire':
                $condition['store_end_time'] = array('between', array(TIMESTAMP, TIMESTAMP + 864000));
                $condition['store_state'] = 1;
                break;
        }

        // 默认商户管理不包含自营商户
        $condition['is_own_shop'] = 0;

		//商户列表
		$store_list = $model_store->getStoreList($condition, 10,'store_id desc');

		Tpl::output('store_list',$store_list);
        Tpl::output('store_type', $this->_get_store_type_array());
		Tpl::output('page',$model_store->showpage());
		Tpl::showpage('store.index');
	}

    private function _get_store_type_array() {
        return array(
            'open' => '开启',
            'close' => '关闭',
            'expire' => '即将到期',
            'expired' => '已到期'
        );
    }
	/**
	 * 商户编辑
	 */
	public function store_editOp(){
		$lang = Language::getLangContent();

		$model_store = Model('store');
		//保存
		if (chksubmit()){

			//结束时间
			$time	= '';
			if(trim($_POST['end_time']) != ''){
				$time = strtotime($_POST['end_time']);
			}
			$update_array = array();
			$update_array['store_name'] = trim($_POST['store_name']);
			$update_array['sc_id'] = intval($_POST['sc_id']);
			$update_array['grade_id'] = 0;
			$update_array['store_end_time'] = $time;
			//$update_array['store_state'] = intval($_POST['store_state']);
			$update_array['store_baozh'] = trim($_POST['store_baozh']);//保障服务开关
			$update_array['store_baozhopen'] = trim($_POST['store_baozhopen']);//保证金显示开关
			$update_array['store_baozhrmb'] = trim($_POST['store_baozhrmb']);//新加保证金-金额
			$update_array['store_qtian'] = trim($_POST['store_qtian']);//保障服务-七天退换
			$update_array['store_zhping'] = trim($_POST['store_zhping']);//保障服务-正品保证
			$update_array['store_erxiaoshi'] = trim($_POST['store_erxiaoshi']);//保障服务-两小时发货
			$update_array['store_tuihuo'] = trim($_POST['store_tuihuo']);//保障服务-退货承诺
			$update_array['store_shiyong'] = trim($_POST['store_shiyong']);//保障服务-试用
			$update_array['store_xiaoxie'] = trim($_POST['store_xiaoxie']);//保障服务-消协
			$update_array['store_huodaofk'] = trim($_POST['store_huodaofk']);//保障服务-货到付款
			$update_array['store_shiti'] = trim($_POST['store_shiti']);//保障服务-实体商户
			if ($update_array['store_state'] == 0){
				//根据商户状态修改该商户所有商品状态
				//$model_goods = Model('goods');
				//$model_goods->editProducesOffline(array('store_id' => $_POST['store_id']));
				$update_array['store_close_info'] = trim($_POST['store_close_info']);
				$update_array['store_recommend'] = 0;
			}else {
				//商户开启后商品不在自动上架，需要手动操作
				$update_array['store_close_info'] = '';
				$update_array['store_recommend'] = intval($_POST['store_recommend']);
			}
            if ($_FILES['banner_1']['name'] != '') {
                $update_array['banner_1'] = $this->upload_image('banner_1');
            }
            if ($_FILES['banner_2']['name'] != '') {
                $update_array['banner_2'] = $this->upload_image('banner_2');
            }
            if ($_FILES['banner_3']['name'] != '') {
                $update_array['banner_3'] = $this->upload_image('banner_3');
            }
            if ($_FILES['banner_4']['name'] != '') {
                $update_array['banner_4'] = $this->upload_image('banner_4');
            }
            if ($_FILES['banner_5']['name'] != '') {
                $update_array['banner_5'] = $this->upload_image('banner_5');
            }
            $update_array['store_content'] = $_POST['store_content'];
            $result = $model_store->editStore($update_array, array('store_id' => $_POST['store_id']));
			if ($result){
				$url = array(
				array(
				'url'=>'index.php?act=store&op=store',
				'msg'=>$lang['back_store_list'],
				),
				array(
				'url'=>'index.php?act=store&op=store_edit&store_id='.intval($_POST['store_id']),
				'msg'=>$lang['countinue_add_store'],
				),
				);
				$this->log(L('nc_edit,store').'['.$_POST['store_name'].']',1);
				showMessage($lang['nc_common_save_succ'],$url);
			}else {
				$this->log(L('nc_edit,store').'['.$_POST['store_name'].']',1);
				showMessage($lang['nc_common_save_fail']);
			}
		}
		//取商户信息
		$store_array = $model_store->getStoreInfoByID($_GET['store_id']);
		if (empty($store_array)){
			showMessage($lang['store_no_exist']);
		}
		//整理商户内容
		$store_array['store_end_time'] = $store_array['store_end_time']?date('Y-m-d',$store_array['store_end_time']):'';

		Tpl::output('store_array',$store_array);

		$joinin_detail = Model('store_joinin')->getOne(array('member_id'=>$store_array['member_id']));
        Tpl::output('joinin_detail', $joinin_detail);
		Tpl::showpage('store.edit');
	}

    /**
     * 编辑保存注册信息
     */
    public function edit_save_joininOp() {
        if (chksubmit()) {
            $member_id = $_POST['member_id'];
            if ($member_id <= 0) {
                showMessage(L('param_error'));
            }
            $param = array();
            $param['company_name'] = $_POST['company_name'];
            $param['company_province'] = intval($_POST['province_id']);
            $param['company_address'] = $_POST['company_address'];
            $param['company_address_detail'] = $_POST['company_address_detail'];
            $param['company_phone'] = $_POST['company_phone'];
            $param['company_employee_count'] = intval($_POST['company_employee_count']);
            $param['company_registered_capital'] = intval($_POST['company_registered_capital']);
            $param['contacts_name'] = $_POST['contacts_name'];
            $param['contacts_phone'] = $_POST['contacts_phone'];
            $param['contacts_email'] = $_POST['contacts_email'];
            $param['business_licence_number'] = $_POST['business_licence_number'];
            $param['business_licence_address'] = $_POST['business_licence_address'];
            $param['business_licence_start'] = $_POST['business_licence_start'];
            $param['business_licence_end'] = $_POST['business_licence_end'];
            $param['business_sphere'] = $_POST['business_sphere'];
            $param['legal_name'] = $_POST['legal_name'];
            if ($_FILES['idcard_front']['name'] != '') {
                $param['idcard_front'] = $this->upload_image('idcard_front');
            }
            if ($_FILES['idcard_back']['name'] != '') {
                $param['idcard_back'] = $this->upload_image('idcard_back');
            }
            if ($_FILES['business_licence_number_electronic']['name'] != '') {
                $param['business_licence_number_electronic'] = $this->upload_image('business_licence_number_electronic');
            }
            $param['organization_code'] = $_POST['organization_code'];
            if ($_FILES['organization_code_electronic']['name'] != '') {
                $param['organization_code_electronic'] = $this->upload_image('organization_code_electronic');
            }
            if ($_FILES['general_taxpayer']['name'] != '') {
                $param['general_taxpayer'] = $this->upload_image('general_taxpayer');
            }
            $param['bank_account_name'] = $_POST['bank_account_name'];
            $param['bank_account_number'] = $_POST['bank_account_number'];
            $param['bank_name'] = $_POST['bank_name'];
            $param['bank_code'] = $_POST['bank_code'];
            $param['bank_address'] = $_POST['bank_address'];
            if ($_FILES['bank_licence_electronic']['name'] != '') {
                $param['bank_licence_electronic'] = $this->upload_image('bank_licence_electronic');
            }
            $param['settlement_bank_account_name'] = $_POST['settlement_bank_account_name'];
            $param['settlement_bank_account_number'] = $_POST['settlement_bank_account_number'];
            $param['settlement_bank_name'] = $_POST['settlement_bank_name'];
            $param['settlement_bank_code'] = $_POST['settlement_bank_code'];
            $param['settlement_bank_address'] = $_POST['settlement_bank_address'];
            $param['tax_registration_certificate'] = $_POST['tax_registration_certificate'];
            $param['taxpayer_id'] = $_POST['taxpayer_id'];
            if ($_FILES['tax_registration_certificate_electronic']['name'] != '') {
                $param['tax_registration_certificate_electronic'] = $this->upload_image('tax_registration_certificate_electronic');
            }
            $result = Model('store_joinin')->editStoreJoinin(array('member_id' => $member_id), $param);
            if ($result) {
                showMessage(L('nc_common_op_succ'), 'index.php?act=store&op=store');
            } else {
                showMessage(L('nc_common_op_fail'));
            }
        }
    }
    
    private function upload_image($file) {
        $pic_name = '';
        $upload = new UploadFile();
        $uploaddir = ATTACH_PATH.DS.'store_joinin'.DS;
        $upload->set('default_dir',$uploaddir);
        $upload->set('allow_type',array('jpg','jpeg','gif','png'));
        if (!empty($_FILES[$file]['name'])){
            $result = $upload->upfile($file);
            if ($result){
                $pic_name = $upload->file_name;
                $upload->file_name = '';
            }else{
                echo $upload->error;exit();
            }
        }
        return $pic_name;
    }
    



	/**
	 * 商户 待审核列表
	 */
	public function store_joininOp(){
		//商户列表
		if(!empty($_GET['owner_and_name'])) {
			$condition['member_name'] = array('like','%'.$_GET['owner_and_name'].'%');
		}
		if(!empty($_GET['store_name'])) {
			$condition['store_name'] = array('like','%'.$_GET['store_name'].'%');
		}

		if(!empty($_GET['joinin_state']) && intval($_GET['joinin_state']) > 0) {
            $condition['joinin_state'] = $_GET['joinin_state'] ;
        } else {
            $condition['joinin_state'] = 10;
        }
		$model_store_joinin = Model('store_joinin');
		$store_list = $model_store_joinin->getList($condition, 10, 'joinin_state asc');
		Tpl::output('store_list', $store_list);
        Tpl::output('joinin_state_array', $this->get_store_joinin_state());

		Tpl::output('page',$model_store_joinin->showpage('2'));
		Tpl::showpage('store_joinin');
	}



    private function get_store_joinin_state() {
        $joinin_state_array = array(
            10 => '新申请',
            STORE_JOIN_STATE_PAY => '已付款',
            STORE_JOIN_STATE_VERIFY_SUCCESS => '待付款',
            STORE_JOIN_STATE_VERIFY_FAIL => '审核失败',
            STORE_JOIN_STATE_PAY_FAIL => '付款审核失败',
            40 => '开店成功',
        );
        return $joinin_state_array;
    }



	/**
	 * 审核详细页
	 */
	public function store_joinin_detailOp(){
		$model_store_joinin = Model('store_joinin');
        $joinin_detail = $model_store_joinin->getOne(array('member_id'=>$_GET['member_id']));
        $joinin_detail_title = '查看';
        if(in_array(intval($joinin_detail['joinin_state']), array(10))) {
            $joinin_detail_title = '审核';
        }

        Tpl::output('joinin_detail_title', $joinin_detail_title);
		Tpl::output('joinin_detail', $joinin_detail);
		Tpl::showpage('store_joinin.detail');
	}

	/**
	 * 审核
	 */
	public function store_joinin_verifyOp() {
        $model_store_joinin = Model('store_joinin');
        $joinin_detail = $model_store_joinin->getOne(array('member_id'=>$_POST['member_id']));

        $joinin_detail['seller_name'] = $joinin_detail['seller_name'] ? $joinin_detail['seller_name'] : $joinin_detail['member_name'];
        switch (intval($joinin_detail['joinin_state'])) {
            case 10:
                $this->store_joinin_verify_pass($joinin_detail);
                break;
            case 11:
                $this->store_joinin_verify_open($joinin_detail);
                break;
            default:
                showMessage('参数错误','');
                break;
        }
	}

    private function store_joinin_verify_pass($joinin_detail) {
        $param = array();
        $param['joinin_state'] = $_POST['verify_type'] === 'pass' ? 20 : 0;
        $param['joinin_message'] = $_POST['joinin_message'];
        $param['paying_amount'] = 0;
//        $param['store_class_commis_rates'] = implode(',', $_POST['commis_rate']);
        $model_store_joinin = Model('store_joinin');
        $model_store_joinin->modify($param, array('member_id'=>$_POST['member_id']));
        if ($param['paying_amount'] > 0) {
            showMessage('商户入驻申请审核完成','index.php?act=store&op=store_joinin');
        } else {
            //如果开店支付费用为零，则审核通过后直接开通，无需再上传付款凭证
            $this->store_joinin_verify_open($joinin_detail);
        }
    }

    private function store_joinin_verify_open($joinin_detail) {
        $model_store_joinin = Model('store_joinin');
        $model_store	= Model('store');
        $model_seller = Model('seller');

        //验证卖家用户名是否已经存在
        if($model_seller->isSellerExist(array('seller_name' => $joinin_detail['seller_name']))) {
            showMessage('卖家用户名已存在','');
        }

        $param = array();
        $param['joinin_state'] = $_POST['verify_type'] === 'pass' ? 40 : 31;
        $param['joinin_message'] = $_POST['joinin_message'];
        $model_store_joinin->modify($param, array('member_id'=>$_POST['member_id']));
        if($_POST['verify_type'] === 'pass') {
            //开店
 			$shop_array		= array();
            $shop_array['member_id']	= $joinin_detail['member_id'];
            $shop_array['member_name']	= $joinin_detail['member_name'];
            $shop_array['seller_name'] = $joinin_detail['seller_name']  ;
			$shop_array['grade_id']		= $joinin_detail['sg_id'];
			$shop_array['store_name']	= $joinin_detail['store_name'] ? $joinin_detail['store_name'] : $joinin_detail['member_name'];
			$shop_array['sc_id']		= $joinin_detail['sc_id'] > 0 ? $joinin_detail['sc_id'] : 0;
            $shop_array['store_company_name'] = $joinin_detail['company_name'];

            $shop_array['province_id']	= $joinin_detail['company_province'] ? $joinin_detail['company_province'] : 0;
            $shop_array['city_id']	= $joinin_detail['company_city'] ? $joinin_detail['company_city'] : 0;
            $shop_array['region_id']	= $joinin_detail['	company_region'] ? $joinin_detail['	company_region'] : 0;

            $shop_array['area_info']	= $joinin_detail['company_address'] ? $joinin_detail['company_address'] : '';
			$shop_array['store_address']= $joinin_detail['company_address_detail'] ? $joinin_detail['company_address_detail'] :'';
			$shop_array['store_zip']	= '';
			$shop_array['store_zy']		= '';
			$shop_array['store_state']	= 1;
            $shop_array['store_time']	= time();
            $shop_array['store_end_time'] = strtotime(date('Y-m-d 23:59:59', strtotime('+1 day'))." +".intval($joinin_detail['joinin_year'])." year");
            $store_id = $model_store->addStore($shop_array);

            if($store_id) {
                //写入卖家账号
                $seller_array = array();
                $seller_array['seller_name'] = $joinin_detail['seller_name'] ? $joinin_detail['seller_name'] : $joinin_detail['member_name'];
                $seller_array['member_id'] = $joinin_detail['member_id'];
                $seller_array['seller_group_id'] = 0;
                $seller_array['store_id'] = $store_id;
                $seller_array['is_admin'] = 1;
                $state = $model_seller->addSeller($seller_array);
            }

			if($state) {
				// 添加相册默认
				$album_model = Model('album');
				$album_arr = array();
				$album_arr['aclass_name'] = Language::get('store_save_defaultalbumclass_name');
				$album_arr['store_id'] = $store_id;
				$album_arr['aclass_des'] = '';
				$album_arr['aclass_sort'] = '255';
				$album_arr['aclass_cover'] = '';
				$album_arr['upload_time'] = time();
				$album_arr['is_default'] = '1';
				$album_model->addClass($album_arr);

                //设置会员会商户
                Model('member')->editMember(array('member_id'=>$joinin_detail['member_id']),array('is_store'=>1));
                showMessage('商户开店成功','index.php?act=store&op=store_joinin');
            } else {
                showMessage('商户开店失败','index.php?act=store&op=store_joinin');
            }
        } else {
            showMessage('商户开店拒绝','index.php?act=store&op=store_joinin');
        }
    }

	    public function delOp()
    {
        $storeId = (int) $_GET['id'];
        $storeModel = model('store');

        $storeArray = $storeModel->field('is_own_shop,store_name')->find($storeId);

        if (empty($storeArray)) {
            showMessage('外驻商户不存在', '', 'html', 'error');
        }

        if ($storeArray['is_own_shop']) {
            showMessage('不能在此删除自营商户', '', 'html', 'error');
        }

        $condition = array(
            'store_id' => $storeId,
        );

        if ((int) model('goods')->getGoodsCount($condition) > 0)
            showMessage('已经发布商品的外驻商户不能被删除', '', 'html', 'error');

        // 完全删除商户
        $storeModel->delStoreEntirely($condition);

        $this->log("删除外驻商户: {$storeArray['store_name']}");
        showMessage('操作成功', getReferer());
    }


    public function check_seller_nameOp()
    {
        echo json_encode($this->checkSellerName($_GET['seller_name'], $_GET['id']));
        exit;
    }

    private function checkSellerName($sellerName, $storeId = 0)
    {
        // 判断store_joinin是否存在记录
        $count = (int) Model('store_joinin')->getStoreJoininCount(array(
            'seller_name' => $sellerName,
        ));
        if ($count > 0)
            return false;

        $seller = Model('seller')->getSellerInfo(array(
            'seller_name' => $sellerName,
        ));

        if (empty($seller))
            return true;

        if (!$storeId)
            return false;

        if ($storeId == $seller['store_id'] && $seller['seller_group_id'] == 0 && $seller['is_admin'] == 1)
            return true;

        return false;
    }

    public function check_member_nameOp()
    {
        echo json_encode($this->checkMemberName($_GET['member_name']));
        exit;
    }

    private function checkMemberName($memberName)
    {
        // 判断store_joinin是否存在记录
        $count = (int) Model('store_joinin')->getStoreJoininCount(array(
            'member_name' => $memberName,
        ));
        if ($count > 0)
            return false;

        return ! Model('member')->getMemberCount(array(
            'member_name' => $memberName,
        ));
    }
    /**
     * 验证商户名称是否存在
     */
    public function ckeck_store_nameOp() {
        /**
         * 实例化卖家模型
         */
        $where = array();
        $where['store_name'] = $_GET['store_name'];
        $where['store_id'] = array('neq', $_GET['store_id']);
        $store_info = Model('store')->getStoreInfo($where);
        if(!empty($store_info['store_name'])) {
            echo 'false';
        } else {
            echo 'true';
        }
    }
}
