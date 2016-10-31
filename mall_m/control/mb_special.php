<?php
/**
 * 专题频道页面
 */
defined('InSystem') or exit('Access Invalid!');

class mb_specialControl extends mobileHomeControl {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 专题首页接口
     */
    public function indexOp() {
        $special_id = $_GET['sp_id'];

        $model_mb_special = Model('mb_special');
        $info = $model_mb_special->getMbSpecialInfo(array('special_id'=>$special_id),'*');
//        dump($info);
        $mb_item_list = $model_mb_special->getMbSpecialItemUsableListByID($special_id);
//        dump($mb_item_list);

        $condition = array();
        $page = 20;
        $condition['special_type'] = $info['special_type'];
        $mb_list = $model_mb_special->getMbSpecialList($condition, $page, 'special_id desc','*');
//        dump($mb_list);
        Tpl::output('info',$info);
        Tpl::output('mb_item_list',$mb_item_list);
        Tpl::output('mb_list',$mb_list);
        Tpl::showpage('mb_special.index');
    }
    
}
