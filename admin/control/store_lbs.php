<?php
/**
 * 商户管理界面
 *
 ***/

defined('InSystem') or exit('Access Invalid!');

class store_lbsControl extends SystemControl{
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

		$model_store_lbs = Model('store_lbs');

		if(trim($_GET['store_name']) != ''){
			$condition['store_name']	= array('like', '%'.trim($_GET['store_name']).'%');
			Tpl::output('store_name',$_GET['store_name']);
		}


		//商户列表
		$store_list = $model_store_lbs->getStoreList($condition, 10,'store_id desc');

		Tpl::output('store_list',$store_list);
		Tpl::output('page',$model_store_lbs->showpage());
		Tpl::showpage('store_lbs.index');
	}

	/**
	 * 商户编辑
	 */
	public function store_editOp(){
        if (chksubmit())
        {
            $store_id = $_POST['store_id'];
            $model_store_lbs = model('store_lbs');
            $saveArray = array();
            $saveArray['store_name'] = $_POST['store_name'];
            $saveArray['store_title'] = $_POST['store_title'];
            if($store_avatar = $this->upload_image('store_avatar')){
                $saveArray['store_avatar'] = $store_avatar;
            }

            if($banner_1 = $this->upload_image('banner_1')){
                $saveArray['banner_1'] = $banner_1;
            }

            if($banner_2 = $this->upload_image('banner_2')){
                $saveArray['banner_2'] = $banner_2;
            }
            if($banner_3 = $this->upload_image('banner_3')){
                $saveArray['banner_3'] = $banner_3;
            }
            if($banner_4 = $this->upload_image('banner_4')){
                $saveArray['banner_4'] = $banner_4;
            }
            if($banner_5 = $this->upload_image('banner_5')){
                $saveArray['banner_5'] = $banner_5;
            }
             //所在地区
            $area = Model('area')->getAreaByID($_POST['region_id']);
            $saveArray['region_id']		= $area['area_id'];
            $saveArray['city_id']		= $area['city_id'];
            $saveArray['province_id']	= $area['province_id'];
            $saveArray['area_info']	    = $_POST['area_info'];

            $saveArray['store_address'] = $_POST['store_address'];
            $saveArray['store_phone'] = $_POST['store_phone'];
            $saveArray['store_content'] = $_POST['store_content'];
            $saveArray['longitude'] = $_POST['longitude'];
            $saveArray['latitude'] = $_POST['latitude'];

            $saveArray['store_state'] = 1;
            $saveArray['store_time'] = time();

            $model_store_lbs->editStore($saveArray,array('store_id'=>$store_id));


            $this->log("修改4S店铺: {$saveArray['store_name']}");
            showMessage('操作成功', urlAdmin('store_lbs', 'store'));
            return;
        }

        $store_id = $_GET['store_id'];
        $store = Model('store_lbs')->getStoreInfoByID($store_id);
        Tpl::output('store',$store);
        Tpl::showpage('store_lbs.add');
	}

    private function upload_image($file) {
        $pic_name = '';
        $upload = new UploadFile();
        $uploaddir =ATTACH_STORE.DS.'lbs'.DS;
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

    public function store_addOp()
    {
        if (chksubmit())
        {

            $model_store_lbs = model('store_lbs');

            $saveArray = array();
            $saveArray['store_name'] = $_POST['store_name'];
            $saveArray['store_name'] = $_POST['store_title'];
            $saveArray['store_avatar'] = $this->upload_image('store_avatar');
            $saveArray['banner_1'] = $this->upload_image('banner_1');
            $saveArray['banner_2'] = $this->upload_image('banner_2');
            $saveArray['banner_3'] = $this->upload_image('banner_3');
            $saveArray['banner_4'] = $this->upload_image('banner_4');
            $saveArray['banner_5'] = $this->upload_image('banner_5');

            //所在地区
            $area = Model('area')->getAreaByID($_POST['region_id']);
            $saveArray['region_id']		= $area['area_id'];
            $saveArray['city_id']		= $area['city_id'];
            $saveArray['province_id']	= $area['province_id'];
            $saveArray['area_info']	    = $_POST['area_info'];

            $saveArray['store_address'] = $_POST['store_address'];
            $saveArray['store_phone'] = $_POST['store_phone'];
            $saveArray['store_content'] = $_POST['store_content'];
            $saveArray['longitude'] = $_POST['longitude'];
            $saveArray['latitude'] = $_POST['latitude'];

            $saveArray['store_state'] = 1;
            $saveArray['store_time'] = time();

            $model_store_lbs->addStore($saveArray);


            $this->log("新增4S店铺: {$saveArray['store_name']}");
            showMessage('操作成功', urlAdmin('store_lbs', 'store'));
            return;
        }

        Tpl::showpage('store_lbs.add');
    }
    


	    public function delOp()
    {
        $store_id = (int) $_GET['store_id'];
        $model_store_lbs = model('store_lbs');

        $condition = array(
            'store_id' => $store_id,
        );

        $model_store_lbs->delStore($condition);
        $this->log("删除服务商: {$store_id}");
        showMessage('操作成功', urlAdmin('store_lbs', 'store'));
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
        if($_GET['store_id']) $where['store_id'] = array('neq', $_GET['store_id']);
        $store_info = Model('store_lbs')->getStoreInfo($where);
        if(!empty($store_info['store_name'])) {
            echo 'false';
        } else {
            echo 'true';
        }
    }
}
