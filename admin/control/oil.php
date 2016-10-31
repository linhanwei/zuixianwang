<?php
/**
 * 油卡管理
 *
 *
 *
 ***/

defined('InSystem') or exit('Access Invalid!');

class oilControl extends SystemControl{
	const EXPORT_SIZE = 1000;
	public function __construct(){
		parent::__construct();
	}

	/**
	 * 油卡管理
	 */
	public function card_listOp(){
		$model_oil_card = Model('predeposit');

		if ($_GET['search_field_value'] != '') {
    		switch ($_GET['search_field_name']){
    			case 'member_name':
    				$condition['oc_member_name'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
    			case 'mobile':
    				$condition['oc_mobile'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
                case 'card_number':
                    $condition['oc_card_number'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
                    break;
    		}
		}
		if($_GET['search_state']){
			$condition['oc_state'] = $_GET['search_state'];
		}

        if ($_GET['paystate_search'] != ''){
            $condition['oc_payment_state'] = $_GET['paystate_search'];
        }

		//排序
		$order = trim($_GET['search_sort']);
		if (empty($order)) {
		    $order = 'oc_id desc';
		}


		$oil_card_list = $model_oil_card->getOilCardList($condition,10, '*', $order);
		//整理油卡信息
		if (is_array($oil_card_list)){
			foreach ($oil_card_list as $k=> $v){
                $oil_card_list[$k]['oc_add_time'] = $v['oc_add_time']?date('Y-m-d H:i:s',$v['oc_add_time']):'';
                $oil_card_list[$k]['oc_payment_time'] = $v['oc_payment_time']?date('Y-m-d H:i:s',$v['oc_payment_time']):'';
                $oil_card_list[$k]['member'] = Model('member')->getMemberInfoByID($v['oc_member_id']);
			}
		}
		Tpl::output('search_sort',trim($_GET['search_sort']));
		Tpl::output('search_field_name',trim($_GET['search_field_name']));
		Tpl::output('search_field_value',trim($_GET['search_field_value']));
		Tpl::output('oil_card_list',$oil_card_list);
		Tpl::output('page',$model_oil_card->showpage());
		Tpl::showpage('oil_card.index');
	}

	/**
	 * 油卡修改
	 */
	public function card_editOp(){
		$model_oil_card = Model('predeposit');
		/**
		 * 保存
		 */
		if (chksubmit()){
				$update_array = array();
				if (!empty($_POST['card_number'])){
					$update_array['oc_card_number'] = $_POST['card_number'];
                    $update_array['oc_state'] = 2;
				}
                if (!empty($_POST['remark'])){
                    $update_array['remark'] = $_POST['remark'];
                }


            $result = $model_oil_card->editOilCard($update_array,array('oc_member_id'=>intval($_POST['oc_member_id']),'oc_type'=>$_POST['oc_type']));
				if ($result){
					$url = array(
					array(
					'url'=>'index.php?act=oil&op=card_list',
					'msg'=>'返回油卡列表',
					),
					array(
					'url'=>'index.php?act=oil&op=card_edit&oc_id='.intval($_POST['oc_id']),
					'msg'=>'继续编辑',
					),
					);
					$this->log('编辑油卡'.'[ID:'.$_POST['oc_member_id'].']',1);
					showMessage('编辑成功',$url);
				}else {
					showMessage('编辑失败');
				}
		}
		$condition['oc_id'] = intval($_GET['oc_id']);
		$oil_array = $model_oil_card->getOilCardInfo($condition);

		Tpl::output('oil_array',$oil_array);
		Tpl::showpage('oil_card.edit');
	}


	/**
	 * 新增油卡
	 */
	public function card_addOp(){
		$lang	= Language::getLangContent();
        $model_oil_card = Model('predeposit');
		/**
		 * 保存
		 */
		if (chksubmit()){
			/**
			 * 验证
			 */
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
			    array("input"=>$_POST["member_name"], "require"=>"true", "message"=>$lang['member_add_name_null']),
			    array("input"=>$_POST["mobile"], "require"=>"true", "message"=>'油卡手机不能为空'),
                array("input"=>$_POST["idcard_name"], "require"=>"true", "message"=>'请输入姓名'),
                array("input"=>$_POST["idcard_number"], "require"=>"true", "message"=>'请输入身份证号'),
                /*array("input"=>$_POST["idcard_front"], "require"=>"true", "message"=>'请上传身份证正面'),
                array("input"=>$_POST["idcard_back"], "require"=>"true", "message"=>'请上传身份证背面'),
                array("input"=>$_POST["address"], "require"=>"true", "message"=>'请输入地址'),*/

                array("input"=>$_POST["card_number"], "require"=>"true", "message"=>'请输入油卡号'),
                array("input"=>$_POST["remark"], "require"=>"true", "message"=>'请输入备注'),

			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {

                if(!checkIdCard($_POST["idcard_number"])){
                    showMessage('请输入正确的身份证号');
                }

                $member_info = Model('member')->getMemberInfo(array('member_name'=>$_POST["member_name"]),'member_id,grade_id');

                if(empty($member_info)){
                    showMessage('会员不存在，请先注册');
                }
                if($member_info['grade_id'] != 1){
                    showMessage('VIP专属，请先升级');
                }

                $card_info = $model_oil_card->getOilCardInfo(array('oc_member_id'=>$member_info['member_id']));
                if($card_info){
                    showMessage('一个帐号只能绑定一张油卡');
                }

                $card_info = $model_oil_card->getOilCardInfo(array('oc_card_number'=>$_POST["card_number"]));
                if($card_info){
                    showMessage('一张油卡只能绑定一个帐号');
                }

                $card_info = $model_oil_card->getOilCardInfo(array('oc_mobile'=>$_POST["mobile"]));
                if($card_info){
                    showMessage('一个手机号码只能绑定一张油卡');
                }
                $insert_array = array();
                $insert_array['oc_sn']	= $model_oil_card->makeSn($member_info['member_id']);
                $insert_array['oc_type'] = $_POST['oc_type'];
                if($_POST['oc_type'] == 1 || $_POST['oc_type'] == 3){
                    $insert_array['oc_amount'] = OIL_PRICE;
                }else{
                    $insert_array['oc_amount'] = OIL_BP_PRICE;
                }
                $insert_array['oc_member_id']	= $member_info['member_id'];
				$insert_array['oc_member_name']	= trim($_POST['member_name']);
				$insert_array['oc_payment_code']	= 'cash';
				$insert_array['oc_payment_name']	= '现金';
				$insert_array['oc_trade_sn']        = '00000000';
				$insert_array['oc_payment_state'] 	= 1;
				$insert_array['oc_add_time'] 		= TIMESTAMP;
				$insert_array['oc_payment_time']	= TIMESTAMP;
                $insert_array['oc_state']           = 2;
                $insert_array['oc_mobile']          = $_POST['mobile'];
                $insert_array['oc_idcard_name']          = $_POST['idcard_name'];
                $insert_array['oc_idcard_number']          = $_POST['idcard_number'];
                $insert_array['oc_idcard_front']    = $this->upload_image('idcard_front');
                $insert_array['oc_idcard_back']     = $this->upload_image('oc_idcard_back');
                $insert_array['oc_address']         = $_POST['address'];
                $insert_array['oc_card_number']     = $_POST['card_number'];

				$result = $model_oil_card->addOilCard($insert_array);
				if ($result){
					$url = array(
					array(
					'url'=>'index.php?act=oil&op=card_list',
					'msg'=>'返回油卡列表',
					),
					array(
					'url'=>'index.php?act=oil&op=card_add',
					'msg'=>'继续添加油卡',
					),
					);
					$this->log('人工绑定新油卡'.'[	'.$_POST['member_name'].']',1);
					showMessage('油卡绑定成功',$url);
				}else {
					showMessage('油卡绑定失败');
				}
			}
		}
		Tpl::showpage('oil_card.add');
	}

    /**
     * 取消申请
     */
    public function cancelOp(){
        $oc_id = intval($_GET['oc_id']);
        if ($oc_id <= 0){
            showMessage('非法操作','index.php?act=oil&op=card_list','','error');
        }
        $model_oil_card = Model('predeposit');
        $condition['oc_id'] = intval($oc_id);
        $oil_array = $model_oil_card->getOilCardInfo($condition);

        if($oil_array['oc_state'] != 1){
            showMessage('只有申请中的油卡才能取消','index.php?act=oil&op=card_list','','error');
        }
        //退回金额
        $model_pd = Model('predeposit');

        $update = array();
        $update['oc_state'] = 3;
        $update['remark'] = $_GET['remark'];
        $model_pd->editOilCard($update,array('oc_id'=>$oc_id));

        $url = array(
            array(
                'url'=>'index.php?act=oil&op=card_list',
                'msg'=>'返回油卡列表',
            ),
        );
        $this->log('取消油卡申请'.'[	'.$oil_array['oc_sn'].']',1);
        showMessage('取消油卡成功',$url);
    }


    /**
     * 充值列表
     */
    public function rechargeOp(){
        $condition = array();
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
        $start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['ol_add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }
        if (!empty($_GET['mname'])){
            $condition['ol_member_name'] = $_GET['mname'];
        }
        if ($_GET['paystate_search'] != ''){
            $condition['ol_payment_state'] = $_GET['paystate_search'];
        }
        if ($_GET['restate_search'] != ''){
            $condition['ol_state'] = $_GET['restate_search'];
        }
        $model_oil_log = Model('oil_log');
        $recharge_list = $model_oil_log->getOilLogList($condition,20,'*','ol_id desc');
        if($recharge_list){
            $model_oil_card =  Model('predeposit');
            foreach($recharge_list as $key=>$val){
                $oil_card = $model_oil_card->getOilCardInfo(array('oc_member_id'=>$val['ol_member_id']));
                $recharge_list[$key]['oil'] = $oil_card;
            }
        }
        //信息输出
        Tpl::output('list',$recharge_list);
        Tpl::output('show_page',$model_oil_log->showpage());
        Tpl::showpage('oil_recharge.list');
    }

    /**
     * 充值编辑(更改成充值状态)
     */
    public function recharge_editOp(){
        $id = intval($_GET['id']);
        if ($id <= 0){
            showMessage('非法操作','index.php?act=oil&op=recharge','','error');
        }
        //查询充值信息
        $model_oil_log = Model('oil_log');
        $condition = array();
        $condition['ol_id'] = $id;
        $condition['ol_state'] = 1;
        $info = $model_oil_log->getOilLogInfo($condition);
        if (empty($info)){
            showMessage('信息不存在','index.php?act=oil&op=recharge','','error');
        }

        $condition = array();
        $condition['ol_id'] = $id;
        $update = array();
        $update['ol_state'] = 2;
        $update['ol_trade_code'] = $_GET['trade_code'];
        $update['ol_admin'] = $this->admin_info['name'];
        $update['ol_to_account_at'] = TIMESTAMP;
        $log_msg = '充值确认操作，单号:'.$info['ol_sn'];

        try {
            //更改充值状态
            $state = $model_oil_log->editOilLog($update,$condition);
            if (!$state) {
                throw Exception('充值失败');
            }
            $this->log($log_msg.'[	'.$info['ol_member_name'].']',1);
            showMessage('充值成功','index.php?act=oil&op=recharge');
        } catch (Exception $e) {
            showMessage($e->getMessage(),'index.php?act=oil&op=recharge','html','error');
        }
    }

    /**
     * 充值查看
     */
    public function recharge_infoOp(){
        $id = intval($_GET['id']);
        if ($id <= 0){
            showMessage('非法操作','index.php?act=oil&op=recharge','','error');
        }
        //查询充值信息
        $model_oil_log = Model('oil_log');
        $condition = array();
        $condition['ol_id'] = $id;
        $info = $model_oil_log->getOilLogInfo($condition);
        if (empty($info)){
            showMessage('信息不存在','index.php?act=oil&op=recharge','','error');
        }
        $oil_card = Model('predeposit')->getOilCardInfo(array('oc_member_id'=>$info['ol_member_id']));
        $info['oil'] = $oil_card;
        Tpl::output('info',$info);
        Tpl::showpage('oil_recharge.info');

    }

    /**
     * 充值删除
     */
    public function recharge_delOp(){
        $ol_id = intval($_GET["id"]);
        if ($ol_id <= 0){
            showMessage('非法操作','index.php?act=oil&op=recharge','','error');
        }
        $model_oil_log = Model('oil_log');
        $condition = array();
        $condition['ol_id'] = "$ol_id";
        $condition['ol_payment_state'] = 0;
        $result = $model_oil_log->delRecharge($condition);
        if ($result){
            showMessage('删除成功','index.php?act=oil&op=recharge');
        }else {
            showMessage('删除失败','index.php?act=oil&op=recharge','','error');
        }
    }
	/**
	 * ajax操作
	 */
	public function ajaxOp(){
		switch ($_GET['branch']){
			/**
			 * 验证油卡是否重复
			 */
			case 'check_user_name':
				$model_member = Model('member');
				$condition['member_name']	= $_GET['member_name'];
				$list = $model_member->getMemberInfo($condition);

                if (empty($list)){
                    echo 'false';
                }else {
                    $model_oil_card = Model('predeposit');
                    $card_info = $model_oil_card->getOilCardInfo(array('oc_member_name'=>$_GET['member_name']));
                    if($card_info){
                        echo 'false';
                    }else{
                        echo 'true';
                    }
                }

                exit;
				break;
            /**
             * 检查油卡
             */
            case 'check_card_number':
                $model_oil_card = Model('predeposit');
                $card_info = $model_oil_card->getOilCardInfo(array('oc_card_number'=>$_GET['card_number']));
                if($card_info){
                    echo 'false';
                }else{
                    echo 'true';
                }
                break;
            case 'check_idcard':
               if(checkIdCard($_GET['idcard_number'])){
                    echo 'true';
                }else{
                    echo 'false';
                }
                break;
            /**
             * 绑定手机号只能一次
             */
            case 'check_mobile':
                $model_oil_card = Model('predeposit');
                $card_info = $model_oil_card->getOilCardInfo(array('oc_mobile'=>$_GET['mobile']));
                if($card_info){
                    echo 'false';
                }else{
                    echo 'true';
                }
                break;
				/**
			 * 验证邮件是否重复
			 */
			case 'check_card_number':
                $model_oil_card = Model('predeposit');
                $card_info = $model_oil_card->getOilCardInfo(array('oc_card_number'=>$_GET["card_number"]));
                if($card_info){
                    echo 'false';
                }else{
                    echo 'true';
                }
                exit();
				break;
		}
	}
    /**
     *
     * 上传文件
     * @param $file
     * @return string
     */
    private function upload_image($file) {
        $pic_name = '';
        $upload = new UploadFile();
        $uploaddir = ATTACH_PATH.DS.'idcard'.DS;
        $upload->set('default_dir',$uploaddir);
        $upload->set('allow_type',array('jpg','jpeg','gif','png'));
        if (!empty($_FILES[$file]['name'])){
            $result = $upload->upfile($file);
            if ($result){
                $pic_name = $upload->file_name;
                $upload->file_name = '';
            }
        }
        return $pic_name;
    }
}
