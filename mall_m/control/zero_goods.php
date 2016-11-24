<?php

/**
 * 0元淘商品
 *
 *
 */
defined('InSystem') or exit('Access Invalid!');

class zero_goodsControl extends mobileHomeControl {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 商品列表
     */
    public function goods_listOp() {

        $model_zero_goods = Model('zero_goods');

        //查询条件
        $condition = array();
       
        //所需字段
        $fieldstr = "goods_id,goods_name,goods_weight,goods_freight,goods_image_index,goods_progress,goods_join_num,goods_surplus_num,goods_total_num";

        //排序方式
        $order = $this->_goods_list_order($_GET['key'], $_GET['order']);

        $goods_list = $model_zero_goods->getGoodsOnlineList($condition, $fieldstr, $this->page, $order, 0, '',false, 0);
        $page_count = $model_zero_goods->gettotalpage();

//        for($i=0;$i<=15;$i++){
//            $goods_list[] = $goods_list[0];
//        }
        $out_data['goods_list'] = $goods_list;
        $out_data['base_site_url'] = UPLOAD_SITE_URL.DS;
        $out_data['wap_site_url'] = $config['wap_site_url'].DS;
        output_data($out_data, mobile_page($page_count));
    }

    /**
     * 商品列表排序方式
     */
    private function _goods_list_order($key, $order) {
        $result = 'goods_progress desc,goods_id desc';
        if (!empty($key)) {

            $sequence = 'desc';
            if ($order == 1) {
                $sequence = 'asc';
            }

            switch ($key) {
                //推荐
                case '1' :
                    $result = 'goods_commend' . ' ' . $sequence;
                    break;
                //浏览量
                case '2' :
                    $result = 'goods_click' . ' ' . $sequence;
                    break;
                //剩余总人数
                case '3' :
                    $result = 'goods_surplus_num' . ' ' . $sequence;
                    break;
            }
        }
        return $result;
    }

    /**
     * 商品详细页
     */
    public function goods_detailOp() {
        $goods_id = intval($_GET ['goods_id']);
        
        // 商品详细信息
        $model_zero_goods = Model('zero_goods');
        
        $goods_detail = $model_zero_goods->getGoodsDetail($goods_id);
//        dump($goods_detail);die;
        if (empty($goods_detail)) {
            output_error('商品不存在');
        }
        
        $output_data['goods_detail'] = $goods_detail;
        output_data($output_data);
    }

}
