<?php
/**
 * 店铺申请
 *
 *
 *
 *
 */

defined('InSystem') or exit('Access Invalid!');

class storeControl extends mobileMemberControl{

    public function __construct() {
        $this->need_login = false;
        parent::__construct();
    }
    /**
     * 商户详细
     */
    public function viewOp(){
        $store_id = $_GET['store_id'];
        $store_info = Model('store')->getStoreInfoByID($store_id);
        
        Tpl::output('html_title',$store_info['store_name']);
        Tpl::output('store_info',$store_info);
        Tpl::showpage('store_info');
    }
    
    /**
     * 商户资料
     */
    public function infoOp(){
        $store_id = $this->member_info['store_id'];
        if(empty($store_id)){
            output_data(array('error'=>'没有店铺信息!'));
        }
        
        $store_info = Model('store')->getStoreInfoByID($store_id);
        
        output_data(array('store_info'=>$store_info));
        
    }
    
    public function joininOp(){
        if($this->member_info['grade_id']!=1){
            if(TIMESTAMP > strtotime('2016-07-15 23:59:59')){
                output_error('请先升级为VIP会员');
            }
        }
        if($this->member_info['is_store'] == 1){
            output_error('店铺申请成功');
        }
        $model_store_joinin = Model('store_joinin');
        $joinin_info = $model_store_joinin->getOne(array('member_id' => $this->member_info['member_id']));

        if($joinin_info){
            if($joinin_info['joinin_state'] == 31){
                $model_store_joinin->drop(array('member_id' => $this->member_info['member_id']));
            }else{
                output_error('您已经申请，请等候申核');
            }
        }

        $param = array();
        $param['member_name'] = $this->member_info['member_name'];
        $param['seller_name'] = $this->member_info['member_name'];
        $param['company_name'] = $_POST['company_name'];
        $param['company_address'] = $_POST['company_address'];

        $param['company_province'] = $_POST['company_province'];
        $param['company_city'] = $_POST['company_city'];
        $param['company_region'] = $_POST['company_region'];

        if($param['company_region']>0){
            $model_area = Model('area');
            $area_info = $model_area->getAreaInfo(array('area_id'=>$param['company_region']));
            $city_info = $model_area->getAreaInfo(array('area_id'=>$param['company_city']));
            $province_info = $model_area->getAreaInfo(array('area_id'=>$param['company_province']));

            $param['company_address'] = $province_info['area_name'] . '-' . $city_info['area_name'] .'-'. $area_info['area_name'];
        }

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
        $param['business_licence_number_electronic'] = $this->upload_image('business_licence_number_electronic');
        $param['organization_code'] = $_POST['organization_code'];
        //$param['organization_code_electronic'] = $this->upload_image('organization_code_electronic');
        //$param['general_taxpayer'] = $this->upload_image('general_taxpayer');
        $param['store_name'] = $_POST['store_name'] ? $_POST['store_name'] :  $param['member_name'] . '的店铺';
        $param['legal_name'] = $_POST['legal_name'];
        $param['idcard_no'] = $_POST['idcard_no'];
        $param['idcard_front'] = $this->upload_image('idcard_front');
        $param['idcard_back'] = $this->upload_image('idcard_back');
        $param['joinin_state'] = 10;
        $param['joinin_year'] = 1;
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
           // array("input"=>$param['company_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"公司名称不能为空且必须小于50个字"),
           // array("input"=>$param['company_address'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"公司地址不能为空且必须小于50个字"),
            array("input"=>$param['company_province'], "require"=>"true","message"=>"请选择省"),
            array("input"=>$param['company_city'], "require"=>"true","message"=>"请选择市"),
            //array("input"=>$param['company_address_detail'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"公司详细地址不能为空且必须小于50个字"),
            //array("input"=>$param['company_registered_capital'], "require"=>"true","validator"=>"Number","注册资金不能为空且必须是数字"),
           // array("input"=>$param['contacts_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"联系人姓名不能为空且必须小于20个字"),
           // array("input"=>$param['contacts_phone'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"联系人电话不能为空"),
           // array("input"=>$param['contacts_email'], "require"=>"true","validator"=>"email","message"=>"电子邮箱不能为空"),
            //array("input"=>$param['business_licence_number'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"营业执照号不能为空且必须小于20个字"),
            //array("input"=>$param['business_licence_address'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"营业执照所在地不能为空且必须小于50个字"),
           // array("input"=>$param['business_licence_start'], "require"=>"true","message"=>"营业执照有效期不能为空"),
           // array("input"=>$param['business_licence_end'], "require"=>"true","message"=>"营业执照有效期不能为空"),
            //array("input"=>$param['business_sphere'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"500","message"=>"法定经营范围不能为空且必须小于50个字"),
            array("input"=>$param['business_licence_number_electronic'], "require"=>"true","message"=>"营业执照电子版不能为空"),
            //array("input"=>$param['organization_code'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"组织机构代码不能为空且必须小于20个字"),
            // array("input"=>$param['organization_code_electronic'], "require"=>"true","message"=>"组织机构代码电子版不能为空"),
            array("input"=>$param['store_name'], "require"=>"true","message"=>"店铺名称不能为空"),
            array("input"=>$param['legal_name'], "require"=>"true","message"=>"法人不能为空"),
            //array("input"=>$param['idcard_no'], "require"=>"true","message"=>"身份证号码不能为空"),
            array("input"=>$param['idcard_front'], "require"=>"true","message"=>"请上传身份证正面"),
            array("input"=>$param['idcard_back'], "require"=>"true","message"=>"请上传身份证反面")
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            output_error($error);
        }



        $param['member_id'] = $this->member_info['member_id'];
        $model_store_joinin->save($param);
        output_data(array('msg'=>'资料提交成功'));
    }

    /**
     * 店铺列表
     */
    public function listOp(){
        $company_province = $_POST['company_province'];
        $company_city = $_POST['company_city'];
        $company_region = $_POST['company_region'];

        $keyword = $_POST['keyword'];

        $condition = array();
        if($company_province > 0){
            $condition['province_id'] = $company_province;
        }
        if($company_city > 0){
            $condition['city_id'] = $company_city;
        }
        if($company_region > 0){
            $condition['region_id'] = $company_region;
        }

        if($keyword){
            $condition['store_name'] = array('like','%' . $keyword . '%');
        }

        $model_store = Model('store');
        //商户列表
        $store_list = $model_store->getStoreList($condition, $this->page,'store_id desc');
        if(empty($store_list)){
            output_error('暂无联盟商户');
        }
        foreach($store_list as $key=>$val){
            if($val['store_avatar']){

                $store_list[$key]['store_avatar'] = UPLOAD_SITE_URL.DS.ATTACH_PATH.DS.'store_joinin'.DS.$val['store_avatar'];
            }else{
                $store_list[$key]['store_avatar'] = UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.'default_store_avatar.gif';
            }
            $store_list[$key]['store_phone'] = $val['store_phone'] ? $val['store_phone'] : $val['member_name'];
            unset($store_list[$key]['legal_name']);
            unset($store_list[$key]['idcard_no']);
            unset($store_list[$key]['idcard_back']);
            unset($store_list[$key]['idcard_front']);
        }
        $page_count = $model_store->gettotalpage();
        output_data(array('store_list' => $store_list), mobile_page($page_count));
    }

    /**
     * 编辑店铺资料
     */
    public function editOp(){

        $model_store = Model('store');
        $update_array = array();
        if($_POST['store_name']){
            $update_array['store_name'] = $_POST['store_name'];
        }
        $store_phone = $_POST['store_phone'] ? $_POST['store_phone'] : '';

        if(empty($_POST['region_id'])){
            output_error('请选择地区');
        }

        $update_array['province_id'] = $_POST['province_id'];
        $update_array['city_id'] = $_POST['city_id'];
        $update_array['region_id'] = $_POST['region_id'];

        $model_area = Model('area');
        $area_info = $model_area->getAreaInfo(array('area_id'=>$update_array['province_id']));
        $city_info = $model_area->getAreaInfo(array('area_id'=>$update_array['city_id']));
        $province_info = $model_area->getAreaInfo(array('area_id'=>$update_array['region_id']));

        $update_array['area_info'] = $province_info['area_name'] . '-' . $city_info['area_name'] .'-'. $area_info['area_name'];

        if($_POST['store_address']) {
            $update_array['store_address'] = $_POST['store_address'];
        }else{
            output_error('请输入店铺详细地址');
        }

        if($store_phone){
            $update_array['store_phone'] = $store_phone;
        }

        $store_avatar = $this->upload_image('store_avatar');
        if($store_avatar) $update_array['store_avatar'] = $store_avatar;

        $banner_1 = $this->upload_image('banner_1');
        $banner_2 = $this->upload_image('banner_2');
        $banner_3 = $this->upload_image('banner_3');
        $banner_4 = $this->upload_image('banner_4');
        $banner_5 = $this->upload_image('banner_5');

        if($banner_1) $update_array['banner_1'] = $banner_1;
        if($banner_2) $update_array['banner_2'] = $banner_2;
        if($banner_3) $update_array['banner_3'] = $banner_3;
        if($banner_4) $update_array['banner_4'] = $banner_4;
        if($banner_5) $update_array['banner_5'] = $banner_5;

        $result = $model_store->editStore($update_array, array('store_id' => $this->member_info['store_info']['store_id']));
        if ($result){
            output_data(array('store_id'=>$this->member_info['store_info']['store_id'],'msg'=>'编辑成功'));
        }else {
            output_error('编辑失败');
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
            }
        }
        return $pic_name;
    }
    
    /**
     * 删除图片
     */
    public function del_imageOp() {
        $banner_num = $_POST['banner_num'];
        
        if(empty($banner_num)){
            output_error('请选择删除的图片!');
        }
        
        if(!in_array($banner_num, array(1,2,3,4,5))){
            output_error('参数错误!');
        }
        $store_id = $this->member_info['store_id'];
        if(empty($store_id)){
            output_data(array('error'=>'没有店铺信息!'));
        }
        
        $Store = Model('store');
        $store_info = $Store->getStoreInfoByID($store_id);
        
        $field_name = 'banner_'.$banner_num;
        $image_path = BASE_UPLOAD_PATH.DS.ATTACH_PATH.DS.'store_joinin'.DS.$store_info[$field_name];
        
        $is_exists = file_exists($image_path);
        
        if(!$is_exists){
            output_error('没有该图片!');
        }
        
        $update_array[$field_name] = '';
        $edit_result = $Store->editStore($update_array, array('store_id' => $this->member_info['store_info']['store_id']));
        
        if($edit_result){
            $result = unlink($image_path);
        }
        if($result){
            output_data(array('msg'=>'删除成功'));
        }else{
            output_error('删除失败!');
        }
        
    }

}
