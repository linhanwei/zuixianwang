<?php
/**
 * 公益管理
 *
 *
 **/

defined('InSystem') or exit('Access Invalid!');
class fundControl extends SystemControl{
	public function __construct(){
		parent::__construct();
	}

	/**
	 * 管理首页
	 */
	public function indexOp(){
		$this->fundOp();
		exit;
	}

	/**
	 * 列表
	 */
	public function fundOp(){
        $_GET['fund_id'] = 1;
        $this->editOp();exit();
		$model_doc	= Model('fund');
		$fund_list	= $model_doc->getList();
		Tpl::output('fund_list',$fund_list);
		Tpl::showpage('fund.index');
	}

	/**
	 * 编辑
	 */
	public function editOp(){
		$lang	= Language::getLangContent();
		/**
		 * 更新
		 */
		if(chksubmit()){
			/**
			 * 验证
			 */
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
				array("input"=>$_POST["fund_name"], "require"=>"true", "message"=>$lang['fund_index_title_null']),
				array("input"=>$_POST["fund_content"], "require"=>"true", "message"=>$lang['fund_index_content_null'])
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {

                //上传网站Logo
                if (!empty($_FILES['fund_banner']['name'])){
                    $upload = new UploadFile();
                    $upload->set('default_dir',ATTACH_COMMON);
                    $result = $upload->upfile('fund_banner');
                    if ($result){
                        $_POST['fund_banner'] = $upload->file_name;
                    }else {
                        showMessage($upload->error,'','','error');
                    }
                }
				$param	= array();
				$param['fund_id']	= intval($_POST['fund_id']);
				$param['fund_name']	= trim($_POST['fund_name']);
				$param['fund_content']= trim($_POST['fund_content']);
                if($_POST['fund_banner'])
                    $param['fund_banner'] = $_POST['fund_banner'];
                $param['fund_to'] = $_POST['fund_to'];
				$param['fund_at']	= time();
				$model_doc	= Model('fund');


				$result	= $model_doc->update($param);

				if($result){
					$this->log('编辑合粉公益[ID:'.$_POST['fund_id'].']',1);
				}else {
					showMessage($lang['nc_common_save_fail']);
				}
			}
		}
		/**
		 * 编辑
		 */
		if(empty($_GET['fund_id'])){
			showMessage($lang['miss_argument']);
		}
		$model_doc	= Model('fund');
        $fund	= $model_doc->getOneById(intval($_GET['fund_id']));

		/**
		 * 模型实例化
		 */
		$model_upload = Model('upload');
		$condition['upload_type'] = '4';
		$condition['item_id'] = $fund['fund_id'];
		$file_upload = $model_upload->getUploadList($condition);
		if (is_array($file_upload)){
			foreach ($file_upload as $k => $v){
				$file_upload[$k]['upload_path'] = UPLOAD_SITE_URL.'/'.ATTACH_ARTICLE.'/'.$file_upload[$k]['file_name'];
			}
		}

		Tpl::output('PHPSESSID',session_id());
		Tpl::output('file_upload',$file_upload);
		Tpl::output('fund',$fund);
		Tpl::showpage('fund.edit');
	}

	/**
	 * 图片上传
	 */
	public function fund_pic_uploadOp(){
	    /**
	     * 上传图片
	     */
	    $upload = new UploadFile();
	    $upload->set('default_dir',ATTACH_COMMON);

	    $result = $upload->upfile('fileupload');
	    if ($result){
	        $_POST['pic'] = $upload->file_name;
	    }else {
	        echo 'error';exit;
	    }
	    /**
	     * 模型实例化
	     */
	    $model_upload = Model('upload');
	    /**
	     * 图片数据入库
	    */
	    $insert_array = array();
	    $insert_array['file_name'] = $_POST['pic'];
	    $insert_array['upload_type'] = '4';
	    $insert_array['file_size'] = $_FILES['fileupload']['size'];
	    $insert_array['item_id'] = intval($_POST['item_id']);
	    $insert_array['upload_time'] = time();
	    $result = $model_upload->add($insert_array);
	    if ($result){
	        $data = array();
	        $data['file_id'] = $result;
	        $data['file_name'] = $_POST['pic'];
	        $data['file_path'] = $_POST['pic'];
	        /**
	         * 整理为json格式
	         */
	        $output = json_encode($data);
	        echo $output;
	    }

	}
	/**
	 * ajax操作
	 */
	public function ajaxOp(){
		switch ($_GET['branch']){
			/**
			 * 删除文章图片
			 */
			case 'del_file_upload':
				if (intval($_GET['file_id']) > 0){
					$model_upload = Model('upload');
					/**
					 * 删除图片
					 */
					$file_array = $model_upload->getOneUpload(intval($_GET['file_id']));
					@unlink(BASE_UPLOAD_PATH.DS.ATTACH_ARTICLE.DS.$file_array['file_name']);
					/**
					 * 删除信息
					 */
					$model_upload->del(intval($_GET['file_id']));
					echo 'true';exit;
				}else {
					echo 'false';exit;
				}
				break;
		}
	}
}
