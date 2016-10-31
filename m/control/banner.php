<?php

defined('InSystem') or exit('Access Invalid!');

class bannerControl extends mobileHomeControl {

    public function __construct() {
        parent::__construct();
    }

    private function getBannerList($type) {
        $model_banner = Model('banner');

        $condition = array();
        $condition['type'] = $type;
        $field = 'id,type,image_name,url,title';
        $list = $model_banner->getTypeAllList($condition,$field,'sort asc,edit_time desc');

        output_data(array('list'=>$list,'msg'=>'获取成功'));
    }

    /**
     * 首页banner接口
     */
    public function indexOp() {
        
        $this->getBannerList(1);
    }
    
}
