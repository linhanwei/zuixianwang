<?php
/**
 * 会员管理
 *
 *
 *
 ***/

defined('InSystem') or exit('Access Invalid!');

class memberControl extends SystemControl{
	const EXPORT_SIZE = 1000;
	public function __construct(){
		parent::__construct();
		Language::read('member');
	}

	/**
	 * 会员管理
	 */
	public function memberOp(){
		$lang	= Language::getLangContent();
		$model_member = Model('member');
				/**
		 * 删除
		 */
		if (chksubmit()){
			/**
			 * 判断是否是管理员，如果是，则不能删除
			 */
			/**
			 * 删除
			 */
			if (!empty($_POST['del_id'])){
				if (is_array($_POST['del_id'])){
					foreach ($_POST['del_id'] as $k => $v){
						$v = intval($v);
						$rs = true;//$model_member->del($v);
						if ($rs){
							//删除该会员商品,商户
							//获得该会员商户信息
							$member = $model_member->getMemberInfo(array(
								'member_id'=>$v
							));
							//删除用户
							$model_member->del($v);
						}
					}
				}
				showMessage($lang['nc_common_del_succ']);
			}else {
				showMessage($lang['nc_common_del_fail']);
			}
		}
		//会员级别
		$member_grade = unserialize(C('member_grade'));

		if ($_GET['search_field_value'] != '') {
    		switch ($_GET['search_field_name']){
    			case 'member_name':
    				$condition['member_name'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
    			case 'member_email':
    				$condition['member_email'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
    			case 'member_truename':
    				$condition['member_truename'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
    		}
		}
		switch ($_GET['search_state']){
			case 'no_informallow':
				$condition['inform_allow'] = '2';
				break;
			case 'no_isbuy':
				$condition['is_buy'] = '0';
				break;
			case 'no_isallowtalk':
				$condition['is_allowtalk'] = '0';
				break;
			case 'no_memberstate':
				$condition['member_state'] = '0';
				break;
		}
		//会员等级
		$search_grade = $_GET['search_grade'] ? intval($_GET['search_grade']) : -1  ;
		if ($search_grade >= 0 && $member_grade){
		    //$condition['member_exppoints'] = array(array('egt',$member_grade[$search_grade]['exppoints']),array('lt',$member_grade[$search_grade+1]['exppoints']),'and');
            $condition['grade_id'] = $search_grade;
        }

		//排序
		$order = trim($_GET['search_sort']);
		if (empty($order)) {
		    $order = 'member_id desc';
		}

        //商户
        $search_store = trim($_GET['search_store']);
        if ($search_store) {
            $condition['is_store'] = '1';
        }
        //合伙人
        $search_agent = trim($_GET['search_agent']);
        if ($search_agent) {
            $condition['is_agent'] = '1';
        }

		$member_list = $model_member->getMemberList($condition, '*', 10, $order);		
		//整理会员信息
		if (is_array($member_list)){
			foreach ($member_list as $k=> $v){
				$member_list[$k]['member_time'] = $v['member_time']?date('Y-m-d H:i:s',$v['member_time']):'';
				$member_list[$k]['member_login_time'] = $v['member_login_time']?date('Y-m-d H:i:s',$v['member_login_time']):'';
				$member_list[$k]['member_grade'] = ($t = $model_member->getOneMemberGrade($v['member_exppoints'], false, $member_grade))?$t['level_name']:'';
			}
		}
		Tpl::output('member_grade',$member_grade);
		Tpl::output('search_sort',trim($_GET['search_sort']));
		Tpl::output('search_field_name',trim($_GET['search_field_name']));
		Tpl::output('search_field_value',trim($_GET['search_field_value']));
        Tpl::output('search_store',$search_store);
        Tpl::output('search_agent',$search_agent);
		Tpl::output('member_list',$member_list);
		Tpl::output('page',$model_member->showpage());
		Tpl::showpage('member.index');
	}

	/**
	 * 会员修改
	 */
	public function member_editOp(){
		$lang	= Language::getLangContent();
		$model_member = Model('member');
		/**
		 * 保存
		 */
		if (chksubmit()){
			/**
			 * 验证
			 */
			/*$obj_validate = new Validate();
			$obj_validate->validateparam = array(
			array("input"=>$_POST["member_email"], "require"=>"true", 'validator'=>'Email', "message"=>$lang['member_edit_valid_email']),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {*/
				$update_array = array();
				$update_array['member_id']			= intval($_POST['member_id']);
				if (!empty($_POST['member_passwd'])){
					$update_array['member_passwd'] = md5($_POST['member_passwd']);
				}
				$update_array['member_email']		= $_POST['member_email'];
				$update_array['member_truename']	= $_POST['member_truename'];
				$update_array['member_sex'] 		= $_POST['member_sex'];
				$update_array['member_qq'] 			= $_POST['member_qq'];
				$update_array['member_ww']			= $_POST['member_ww'];
				$update_array['inform_allow'] 		= $_POST['inform_allow'];
				$update_array['is_buy'] 			= $_POST['isbuy'];
				$update_array['is_allowtalk'] 		= $_POST['allowtalk'];
				$update_array['member_state'] 		= $_POST['memberstate'];

                $model_area = Model('area');
                //代理地区
                $agent_area = $model_area->getAreaByID($_POST['agent_area_id']);
                if(!$this->checkAgent($agent_area)){
                    showMessage('请检查代理的数量，省代理限2人，市代码限1人，县代理限1人');
                }
                $update_array['agent_areaid']		= $agent_area['area_id'];
                $update_array['agent_cityid']		= $agent_area['city_id'];
                $update_array['agent_provinceid']	= $agent_area['province_id'];
                $update_array['agent_areainfo']	= $_POST['agent_area_info'];

                //所在地区
                $member_area = $model_area->getAreaByID($_POST['area_id']);
                $update_array['member_areaid']		= $member_area['area_id'];
                $update_array['member_cityid']		= $member_area['city_id'];
                $update_array['member_provinceid']	= $member_area['province_id'];
                $update_array['member_areainfo']	= $_POST['area_info'];

                $update_array['is_store']           = $_POST['is_store'];
                $update_array['is_agent']           = $_POST['is_agent'];
				if (!empty($_POST['member_avatar'])){
					$update_array['member_avatar'] = $_POST['member_avatar'];
				}


                //添加奖励会员积分invite_code
                $invite_code = $_POST['invite_code'];

                if(!empty($invite_code)){
                    //会员名
                    $pinfo = $model_member->getMemberInfo(array('member_name'=>$invite_code),'member_id,member_name');
                    if($_POST['member_name'] == $invite_code){
                        showMessage('推荐人不能为自己');
                    }
                    if($pinfo){
                        $update_array['inviter_id'] = $pinfo['member_id'];
                    }else{
                        showMessage('推荐人不存在');
                    }
                }


				$result = $model_member->editMember(array('member_id'=>intval($_POST['member_id'])),$update_array);
				if ($result){
                    //是否关闭店铺
                    Model('store')->editStore(array('store_state'=>$update_array['is_store']),array('member_id'=>intval($_POST['member_id'])));
					$url = array(
					array(
					'url'=>'index.php?act=member&op=member',
					'msg'=>$lang['member_edit_back_to_list'],
					),
					array(
					'url'=>'index.php?act=member&op=member_edit&member_id='.intval($_POST['member_id']),
					'msg'=>$lang['member_edit_again'],
					),
					);
					$this->log(L('nc_edit,member_index_name').'[ID:'.$_POST['member_id'].']',1);
					showMessage($lang['member_edit_succ'],$url);
				}else {
					showMessage($lang['member_edit_fail']);
				}
			//}
		}
		$condition['member_id'] = intval($_GET['member_id']);
		$member_array = $model_member->getMemberInfo($condition);

		Tpl::output('member_array',$member_array);
		Tpl::showpage('member.edit');
	}

    /**
     * 判断代理是否满员
     */
    private function checkAgent($area_info){

        if($area_info){
            if($area_info['province_id'] > 0 && $area_info['city_id'] == 0 && $area_info['area_id']==0){
                $where = "agent_provinceid = {$area_info['province_id']} AND agent_cityid = 0 AND agent_areaid = 0";

                $count = Model('member')->getMemberCount($where);

                if($count >= 2){
                    return false;
                }
            }elseif($area_info['city_id'] > 0 && $area_info['area_id'] == 0){
                $where = "agent_provinceid = {$area_info['province_id']} AND agent_cityid = {$area_info['city_id']} AND agent_areaid = 0";
                $count = Model('member')->getMemberCount($where);

                if($count >= 1){
                    return false;
                }
            }elseif($area_info['area_id'] > 0){
                $where = "agent_provinceid = {$area_info['province_id']} AND agent_cityid = {$area_info['city_id']} AND agent_areaid = {$area_info['area_id']}";
                $count = Model('member')->getMemberCount($where);

                if($count >= 1){
                    return false;
                }
            }
        }

        return true;
    }
	/**
	 * 新增会员
	 */
	public function member_addOp(){
		$lang	= Language::getLangContent();
		$model_member = Model('member');
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
			    array("input"=>$_POST["member_passwd"], "require"=>"true", "message"=>'密码不能为空')
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {
				$insert_array = array();
				$insert_array['member_name']	= trim($_POST['member_name']);
				$insert_array['member_passwd']	= trim($_POST['member_passwd']);
                $insert_array['member_paypwd']  = trim($_POST['member_passwd']);
                $insert_array['member_email']	= trim($_POST['member_name']);
				$insert_array['member_truename']= trim($_POST['member_truename']);
				$insert_array['member_sex'] 	= trim($_POST['member_sex']);
				$insert_array['member_qq'] 		= trim($_POST['member_qq']);
				$insert_array['member_ww']		= trim($_POST['member_ww']);

                //添加奖励会员积分invite_code
                $invite_code = $_POST['invite_code'];
                if(empty($invite_code)){
                    $inviter_id = 1;            //总部
                }else{
                    //会员名
                    $pinfo = $model_member->getMemberInfo(array('member_name'=>$invite_code),'member_id');
                    if($pinfo){
                        $inviter_id = $pinfo['member_id'];
                    }else{
                        showMessage('推荐人不存在');
                    }
                }

                $insert_array['inviter_id'] = $inviter_id;
                //默认允许举报商品
                $insert_array['inform_allow'] 	= '1';
				if (!empty($_POST['member_avatar'])){
					$insert_array['member_avatar'] = trim($_POST['member_avatar']);
				}

				$result = $model_member->addMember($insert_array);
				if ($result){
					$url = array(
					array(
					'url'=>'index.php?act=member&op=member',
					'msg'=>$lang['member_add_back_to_list'],
					),
					array(
					'url'=>'index.php?act=member&op=member_add',
					'msg'=>$lang['member_add_again'],
					),
					);
					$this->log(L('nc_add,member_index_name').'[	'.$_POST['member_name'].']',1);
					showMessage($lang['member_add_succ'],$url);
				}else {
					showMessage($lang['member_add_fail']);
				}
			}
		}
		Tpl::showpage('member.add');
	}

	/**
	 * ajax操作
	 */
	public function ajaxOp(){
		switch ($_GET['branch']){
			/**
			 * 验证会员是否重复
			 */
			case 'check_user_name':
				$model_member = Model('member');
				$condition['member_name']	= $_GET['member_name'];
				$condition['member_id']	= array('neq',intval($_GET['member_id']));
				$list = $model_member->getMemberInfo($condition);
				if (empty($list)){
					echo 'true';exit;
				}else {
					echo 'false';exit;
				}
				break;
				/**
			 * 验证邮件是否重复
			 */
			case 'check_email':
				$model_member = Model('member');
				$condition['member_email'] = $_GET['member_email'];
				$condition['member_id'] = array('neq',intval($_GET['member_id']));
				$list = $model_member->getMemberInfo($condition);
				if (empty($list)){
					echo 'true';exit;
				}else {
					echo 'false';exit;
				}
				break;
		}
	}

    /**
     * 导出帐户余额充值记录
     *
     */
    public function export_step1Op(){
        $condition = array();
        //会员级别
        $member_grade = unserialize(C('member_grade'));

        if ($_GET['search_field_value'] != '') {
            switch ($_GET['search_field_name']){
                case 'member_name':
                    $condition['member_name'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
                    break;
                case 'member_email':
                    $condition['member_email'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
                    break;
                case 'member_truename':
                    $condition['member_truename'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
                    break;
            }
        }
        switch ($_GET['search_state']){
            case 'no_informallow':
                $condition['inform_allow'] = '2';
                break;
            case 'no_isbuy':
                $condition['is_buy'] = '0';
                break;
            case 'no_isallowtalk':
                $condition['is_allowtalk'] = '0';
                break;
            case 'no_memberstate':
                $condition['member_state'] = '0';
                break;
        }
        //会员等级
        $search_grade = $_GET['search_grade'] ? intval($_GET['search_grade']) : -1  ;
        if ($search_grade >= 0 && $member_grade){
            //$condition['member_exppoints'] = array(array('egt',$member_grade[$search_grade]['exppoints']),array('lt',$member_grade[$search_grade+1]['exppoints']),'and');
            $condition['grade_id'] = $search_grade;
        }

        $model_member = Model('member');
        if (!is_numeric($_GET['curpage'])){
            $count = $model_member->getMemberCount($condition);
            $array = array();
            if ($count > self::EXPORT_SIZE ){	//显示下载链接
                $page = ceil($count/self::EXPORT_SIZE);
                for ($i=1;$i<=$page;$i++){
                    $limit1 = ($i-1)*self::EXPORT_SIZE + 1;
                    $limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
                    $array[$i] = $limit1.' ~ '.$limit2 ;
                }
                Tpl::output('list',$array);
                Tpl::output('murl','index.php?act=member&op=member');
                Tpl::showpage('export.excel');
            }else{	//如果数量小，直接下载
                $data = $model_member->getMemberList($condition,'*',self::EXPORT_SIZE,'member_id desc');

                foreach ($data as $k=>$v) {
                    $inviter_member = $model_member->getMemberInfoByID($v['inviter_id']);
                    $data[$k]['inviter_name'] = $inviter_member['member_name'];
                }

                $this->createExcel($data);
            }
        }else{	//下载
            $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;
            $data = $model_member->getMemberList($condition,'*','','member_id desc',"{$limit1},{$limit2}");

            foreach ($data as $k=>$v) {
                $inviter_member = $model_member->getMemberInfoByID($v['inviter_id']);
                $data[$k]['inviter_name'] = $inviter_member['member_name'];
            }
            $this->createExcel($data);
        }
    }

    /**
     * 生成导出帐户余额充值excel
     *
     * @param array $data
     */
    private function createExcel($data = array()){
        Language::read('export');
        import('libraries.excel');
        $excel_obj = new Excel();
        $excel_data = array();
        //设置样式
        $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
        //header
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'序号');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'会员帐号');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'姓名');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'身份证');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'所在区域');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'会员等级');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'推荐人');
        $row = 1;
        foreach ((array)$data as $k=>$v){
            $tmp = array();
            $tmp[] = array('data'=>$row++);
            $tmp[] = array('data'=>$v['member_name']);
            $tmp[] = array('data'=>$v['member_truename']);
            $tmp[] = array('data'=>$v['member_idcard']);
            $tmp[] = array('data'=>$v['member_areainfo']);
            $tmp[] = array('data'=>$v['grade_name']);
            $tmp[] = array('data'=>$v['inviter_name']);
            $excel_data[] = $tmp;
        }
        $excel_data = $excel_obj->charset($excel_data,CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset('会员信息',CHARSET));
        $excel_obj->generateXML($excel_obj->charset('会员信息',CHARSET).$_GET['curpage'].'-'.date('Y-m-d-H',time()));
    }

}
