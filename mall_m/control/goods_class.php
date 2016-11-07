<?php
/**
 * 商品分类
 *
 *
 *
 */


defined('InSystem') or exit('Access Invalid!');
class goods_classControl extends mobileHomeControl{

	public function __construct() {
        parent::__construct();
    }

    public function indexOp() {

        /* if(!empty($_GET['gc_id']) && intval($_GET['gc_id']) > 0) {
             $this->_get_class_list($_GET['gc_id']);
         } else {
             $this->_get_root_class();
         }*/

        $gc_id = $_GET['gc_id'];
        $is_ajax = $_GET['is_ajax'];

        $model_goods_class = Model('goods_class');

        //获取最顶级分类
        $top_list = $model_goods_class->getChildClass(0,false,false);

        //获取下级分类
        if($gc_id){
            if($is_ajax){
                $child_list = $this->_get_all_child($gc_id,$model_goods_class);
                output_data(array('child_list' => $child_list));
            }

            if($top_list){
                foreach($top_list as $tk=>$tv){
                    if($tv['gc_id'] == $gc_id){
                        $info = $top_list[$tk];
                        unset($top_list[$tk]);
                    }
                }
            }
        }else{
            $key = 0;
            $info = $top_list[$key];
            $gc_id = $info['gc_id'];
            unset($top_list[$key]);
        }

        //获取顶级所有子分类
        $child_list = $this->_get_all_child($gc_id,$model_goods_class);

        Tpl::output('info',$info);
        Tpl::output('top_list',$top_list);
        Tpl::output('child_list',$child_list);
        Tpl::showpage('goods_class.index');

    }

    //获取数据
    public function ajax_dataOp(){

        $gc_id = $_GET['gc_id'];
        $is_ajax = $_GET['is_ajax'];

        $model_goods_class = Model('goods_class');

        //获取最顶级分类
        $top_list = array();
        if(!$is_ajax){
            $top_list = $model_goods_class->getChildClass(0,false,false);
            if(!$gc_id){
                $gc_id = $top_list[0]['gc_id'];
            }
        }

        $child_list = $this->_get_all_child($gc_id,$model_goods_class);
        output_data(array('top_list'=>$top_list,'child_list'=>$child_list));
    }

    /**
     * 获取顶级所有子分类
     * @param $parent_id
     */
    private function _get_all_child($parent_id,$model_goods_class){
        $child_list = $model_goods_class->getChildClass($parent_id,false,false);

        if($child_list){
            foreach($child_list as $k=>$child){
                $list = $model_goods_class->getChildClass($child['gc_id'],false,false);
                if($list){
                    foreach($list as $ck=>$cv){
                        $list[$ck]['image'] = UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.'category-pic-'.intval($cv['gc_id']).'.jpg';
                    }
                }
                $child_list[$k]['image'] = UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.'category-pic-'.intval($child['gc_id']).'.jpg';
                $child_list[$k]['child'] = $list;
            }
        }

        return $child_list;
    }

    /**
     * 返回一级分类列表
     */
    private function _get_root_class() {
		$model_goods_class = Model('goods_class');
        $model_mb_category = Model('mb_category');

        $goods_class_array = Model('goods_class')->getGoodsClassForCacheModel();

		$class_list = $model_goods_class->getGoodsClassListByParentId(0);
        $mb_categroy = $model_mb_category->getLinkList(array());
        $mb_categroy = array_under_reset($mb_categroy, 'gc_id');
        foreach ($class_list as $key => $value) {
            if(!empty($mb_categroy[$value['gc_id']])) {
                $class_list[$key]['image'] = UPLOAD_SITE_URL.DS.ATTACH_MOBILE.DS.'category'.DS.$mb_categroy[$value['gc_id']]['gc_thumb'];
            } else {
                $class_list[$key]['image'] = '';
            }

            $class_list[$key]['text'] = '';
            $child_class_string = $goods_class_array[$value['gc_id']]['child'];
            $child_class_array = explode(',', $child_class_string);
            foreach ($child_class_array as $child_class) {
                $class_list[$key]['text'] .= $goods_class_array[$child_class]['gc_name'] . '/';
            }
            $class_list[$key]['text'] = rtrim($class_list[$key]['text'], '/');
        }

        output_data(array('class_list' => $class_list));
    }

    /**
     * 根据分类编号返回下级分类列表
     */
    private function _get_class_list($gc_id) {
        $goods_class_array = Model('goods_class')->getGoodsClassForCacheModel();

        $goods_class = $goods_class_array[$gc_id];

        if(empty($goods_class['child'])) {
            //无下级分类返回0
            output_data(array('class_list' => '0'));
        } else {
            //返回下级分类列表
            $class_list = array();
            $child_class_string = $goods_class_array[$gc_id]['child'];
            $child_class_array = explode(',', $child_class_string);
            foreach ($child_class_array as $child_class) {
                $class_item = array();
                $class_item['gc_id'] .= $goods_class_array[$child_class]['gc_id'];
                $class_item['gc_name'] .= $goods_class_array[$child_class]['gc_name'];
                $class_list[] = $class_item;
            }
            output_data(array('class_list' => $class_list));
        }
    }
}
