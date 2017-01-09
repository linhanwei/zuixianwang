<?php
/**
 * 商品
 *
 *
 *
 */

defined('InSystem') or exit('Access Invalid!');
class goodsControl extends mobileHomeControl{

	public function __construct() {

        parent::__construct();
    }

    /**
     * 商品列表页
     */
    public function listOp(){
        $model_goods = Model('goods');
        $model_search = Model('search');

        $is_ajax= $_GET['is_ajax'];
        $this->page = 6;

        //查询条件
        $condition = array();
        if(!empty($_GET['gc_id']) && intval($_GET['gc_id']) > 0) {
            $condition['gc_id'] = $_GET['gc_id'];
        } elseif (!empty($_GET['keyword'])) {
            $condition['goods_name|goods_jingle'] = array('like', '%' . $_GET['keyword'] . '%');
        }

        //所需字段
        $fieldstr = "goods_id,goods_commonid,store_id,goods_name,goods_price,goods_marketprice,goods_image,goods_salenum,evaluation_good_star,evaluation_count";

        // 添加3个状态字段
        $fieldstr .= ',is_virtual,is_presell,is_fcode,have_gift';

        //排序方式
        $order = $this->_goods_list_order($_GET['sort_key'], $_GET['order']);

        //优先从全文索引库里查找
        list($indexer_ids,$indexer_count) = $model_search->indexerSearch($_GET,$this->page);
        if (is_array($indexer_ids)) {
            //商品主键搜索
            $goods_list = $model_goods->getGoodsOnlineList(array('goods_id'=>array('in',$indexer_ids)), $fieldstr, 0, $order, $this->page, null, false);

            //如果有商品下架等情况，则删除下架商品的搜索索引信息
            if (count($goods_list) != count($indexer_ids)) {
                $model_search->delInvalidGoods($goods_list, $indexer_ids);
            }

        } else {
            $goods_list = $model_goods->getGoodsListByColorDistinct($condition, $fieldstr, $order, $this->page);

        }
        $page_count = $model_goods->gettotalpage();

        //处理商品列表(抢购、限时折扣、商品图片)
        $goods_list = $this->_goods_list_extend($goods_list);
//        dump($goods_list);
        if($is_ajax){
            output_data(array('goods_list' => $goods_list), mobile_page($page_count));
        }

        Tpl::output('list',$goods_list);
        Tpl::output('page_count',$page_count);
        Tpl::showpage('goods.list');
    }

    /**
     * 商品列表
     */
    public function goods_listOp() {
        $model_goods = Model('goods');
        $model_search = Model('search');
        $this->page = 6;
        $price = $_GET['price'];

        //查询条件
        $condition = array();
        if(!empty($_GET['gc_id']) && intval($_GET['gc_id']) > 0) {
            $condition['gc_id'] = $_GET['gc_id'];
        } elseif (!empty($_GET['keyword'])) {
            $condition['goods_name|goods_jingle'] = array('like', '%' . $_GET['keyword'] . '%');
        }

        if(!empty($price)){
            if(stripos($price,'-')){
                $price_exp = explode('-',$price);
                $condition['goods_price'] = array('between', array($price_exp[0],$price_exp[1]));
            }
        }

        //所需字段
        $fieldstr = "goods_id,goods_commonid,store_id,goods_name,goods_price,goods_marketprice,goods_image,goods_salenum,evaluation_good_star,evaluation_count";

        // 添加3个状态字段
        $fieldstr .= ',is_virtual,is_presell,is_fcode,have_gift';

        //排序方式
        $order = $this->_goods_list_order($_GET['sort_key'], $_GET['order']);

        //优先从全文索引库里查找
        list($indexer_ids,$indexer_count) = $model_search->indexerSearch($_GET,$this->page);
        if (is_array($indexer_ids)) {
            //商品主键搜索
            $goods_list = $model_goods->getGoodsOnlineList(array('goods_id'=>array('in',$indexer_ids)), $fieldstr, 0, $order, $this->page, null, false);

            //如果有商品下架等情况，则删除下架商品的搜索索引信息
            if (count($goods_list) != count($indexer_ids)) {
                $model_search->delInvalidGoods($goods_list, $indexer_ids);
            }
            pagecmd('setEachNum',$this->page);
            pagecmd('setTotalNum',$indexer_count);
        } else {
            $goods_list = $model_goods->getGoodsListByColorDistinct($condition, $fieldstr, $order, $this->page);
        }

        $page_count = $model_goods->gettotalpage();

        //处理商品列表(抢购、限时折扣、商品图片)
        $goods_list = $this->_goods_list_extend($goods_list);

        output_data(array('goods_list' => $goods_list), mobile_page($page_count));
    }

    /**
     * 推荐商品列表
     */
    public function recommend_goods_listOp() {
        $model_goods = Model('goods');
        $this->page = 10;

        //查询条件
        $condition = array();
        $condition['goods_commend'] = 1;

        //所需字段
        $fieldstr = "goods_id,goods_commonid,store_id,goods_name,goods_price,goods_marketprice,goods_image,goods_salenum,evaluation_good_star,evaluation_count";

        // 添加3个状态字段
        $fieldstr .= ',is_virtual,is_presell,is_fcode,have_gift';

        //排序方式
        $order = $this->_goods_list_order($_GET['sort_key'], $_GET['order']);

        $goods_list = $model_goods->getGoodsListByColorDistinct($condition, $fieldstr, $order, $this->page);
        $page_count = $model_goods->gettotalpage();

        //处理商品列表(抢购、限时折扣、商品图片)
        $goods_list = $this->_goods_list_extend($goods_list);

        output_data(array('goods_list' => $goods_list), mobile_page($page_count));
    }

    /**
     * 商品列表排序方式
     */
    private function _goods_list_order($key, $order) {
        $result = 'is_own_shop desc,goods_id desc';
        if (!empty($key)) {

            $sequence = 'desc';
            if($order == 1) {
                $sequence = 'asc';
            }

            switch ($key) {
                //销量
                case '1' :
                    $result = 'goods_salenum' . ' ' . $sequence;
                    break;
                //浏览量
                case '2' :
                    $result = 'goods_click' . ' ' . $sequence;
                    break;
                //价格
                case '3' :
                    $result = 'goods_price' . ' ' . $sequence;
                    break;
            }
        }
        return $result;
    }

    /**
     * 处理商品列表(抢购、限时折扣、商品图片)
     */
    private function _goods_list_extend($goods_list) {
        //获取商品列表编号数组
        $commonid_array = array();
        $goodsid_array = array();
        foreach($goods_list as $key => $value) {
            $commonid_array[] = $value['goods_commonid'];
            $goodsid_array[] = $value['goods_id'];
        }

        //促销
        $groupbuy_list = Model('groupbuy')->getGroupbuyListByGoodsCommonIDString(implode(',', $commonid_array));
        $xianshi_list = Model('p_xianshi_goods')->getXianshiGoodsListByGoodsString(implode(',', $goodsid_array));
        foreach ($goods_list as $key => $value) {
            //抢购
            if (isset($groupbuy_list[$value['goods_commonid']])) {
                $goods_list[$key]['goods_price'] = $groupbuy_list[$value['goods_commonid']]['groupbuy_price'];
                $goods_list[$key]['group_flag'] = true;
            } else {
                $goods_list[$key]['group_flag'] = false;
            }

            //限时折扣
            if (isset($xianshi_list[$value['goods_id']]) && !$goods_list[$key]['group_flag']) {
                $goods_list[$key]['goods_price'] = $xianshi_list[$value['goods_id']]['xianshi_price'];
                $goods_list[$key]['xianshi_flag'] = true;
            } else {
                $goods_list[$key]['xianshi_flag'] = false;
            }

            //商品图片url
            $goods_list[$key]['goods_image_url'] = cthumb($value['goods_image'], 360, $value['store_id']);

            unset($goods_list[$key]['store_id']);
//            unset($goods_list[$key]['goods_commonid']);
            unset($goods_list[$key]['nc_distinct']);
        }

        return $goods_list;
    }

    /**
     * 商品详情页
     */
    public function detailOp(){
        $goods_id = intval($_GET ['goods_id']);

        // 商品详细信息
        $model_goods = Model('goods');

        $goods_detail = $model_goods->getGoodsDetail($goods_id);
        if (empty($goods_detail)) {
            $this->show_msg('商品不存在');
        }

//        dump($goods_detail);

        Tpl::output('info',$goods_detail);
        Tpl::showpage('goods.detail');
    }

    /**
     * 判断是否是序列化数据
     * @param $data
     * @return bool
     */
    private function is_serialized( $data ) {
        $data = trim( $data );
        if ( 'N;' == $data )
            return true;
        if ( !preg_match( '/^([adObis]):/', $data, $badions ) )
            return false;
        switch ( $badions[1] ) {
            case 'a' :
            case 'O' :
            case 's' :
                if ( preg_match( "/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data ) )
                    return true;
                break;
            case 'b' :
            case 'i' :
            case 'd' :
                if ( preg_match( "/^{$badions[1]}:[0-9.E-]+;\$/", $data ) )
                    return true;
                break;
        }
        return false;
    }

    /**
     * 异步获取商品信息
     */
    public function ajax_detailOp(){
        $goods_id = intval($_GET ['goods_id']);

        // 商品详细信息
        $model_goods = Model('goods');

        $goods_detail = $model_goods->getGoodsInfoByID($goods_id);
        if (empty($goods_detail)) {
            output_error('商品不存在');
        }

        //商品图片url
        $new_detail['goods_image_url'] = cthumb($goods_detail['goods_image'], 360, $goods_detail['store_id']);
        $new_detail['goods_id'] = $goods_detail['goods_id'];
        $new_detail['goods_name'] = $goods_detail['goods_name'];
        $new_detail['goods_promotion_price'] = $goods_detail['goods_promotion_price'];
        $new_detail['goods_storage'] = $goods_detail['goods_storage'];
        $new_detail['goods_storage'] = $goods_detail['goods_storage'];

        output_data($new_detail);
    }

    /**
     * 商品详细页_手机接口
     */
    public function goods_detailOp() {
        $goods_id = intval($_GET ['goods_id']);
        $key = $_GET['key'];

        // 商品详细信息
        $model_goods = Model('goods');

        $goods_detail = $model_goods->getGoodsDetail($goods_id);
        if (empty($goods_detail)) {
            output_error('商品不存在');
        }

        //判断是否收藏商品
        $goods_detail['is_favorites'] = 0;
        if($key){
            $model_mb_user_token = Model('mb_user_token');
            $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);
            $member_id = $mb_user_token_info['member_id'];

            $favorites_model = Model('favorites');
            $result = $favorites_model->checkFavorites($goods_id,'goods',$member_id);
            if($result){
                $goods_detail['is_favorites'] = 1;
            }
        }

        //推荐商品
        $model_store = Model('store');
        $hot_sales = $model_store->getHotSalesList($goods_detail['goods_info']['store_id'], 6);
        $goods_commend_list = array();
        foreach($hot_sales as $value) {
            $goods_commend = array();
            $goods_commend['goods_id'] = $value['goods_id'];
            $goods_commend['goods_name'] = $value['goods_name'];
            $goods_commend['goods_price'] = $value['goods_price'];
            $goods_commend['goods_image_url'] = cthumb($value['goods_image'], 240);
            $goods_commend_list[] = $goods_commend;
        }
        $goods_detail['goods_commend_list'] = $goods_commend_list;
        $store_info = $model_store->getStoreInfoByID($goods_detail['goods_info']['store_id']);

        $goods_detail['store_info']['store_id'] = $store_info['store_id'];
        $goods_detail['store_info']['store_name'] = $store_info['store_name'];
        $goods_detail['store_info']['member_id'] = $store_info['member_id'];
        $goods_detail['store_info']['store_qq'] = $store_info['store_qq'];
        $goods_detail['store_info']['store_ww'] = $store_info['store_ww'];
        $goods_detail['store_info']['store_phone'] = $store_info['store_phone'];
        $goods_detail['store_info']['member_name'] = $store_info['member_name'];
        $goods_detail['store_info']['logo'] = getStoreLogo($store_info['store_label'],'store_logo');
        $goods_detail['store_info']['avatar'] = getStoreLogo($store_info['store_avatar'],'store_avatar');

        //商品详细信息处理
        $goods_detail = $this->_goods_detail_extend($goods_detail);

        //商品属性处理
        $goods_detail['attr_list'] = array();
        $attr_list = $goods_detail['goods_info']['goods_attr'];
        if($attr_list){
            foreach($attr_list as $aval){
                foreach($aval as $ak=>$av){
                    if($ak == 'name'){
                        $aname = $av;
                    }else{
                        $avalue = $av;
                    }
                }
                $goods_detail['attr_list'][] = array('name'=>$aname,'value'=>$avalue);
            }
        }

        //商品规格值处理
        $goods_detail['spec_val_list'] = $goods_detail['goods_info']['goods_spec'] ? array_values($goods_detail['goods_info']['goods_spec']) : array();
        $goods_detail["goods_info"]["spec_value"] = $goods_detail["goods_info"]["spec_value"] ? $goods_detail["goods_info"]["spec_value"] :array();
        $goods_detail['goods_info']['goods_spec'] = $goods_detail['goods_info']['goods_spec'] ? $goods_detail['goods_info']['goods_spec'] : array();

        // 评价列表
        $evaluate_con['geval_goodsid'] = $goods_id;
        $evaluate_con['geval_state'] = 0;
        $evaluate_con['isanonymous'] = true;
        $goods_eval_list = Model("evaluate_goods")->getEvaluateGoodsList($evaluate_con, null, '3');
        $goods_detail['goods_eval_list'] = $goods_eval_list;

        //评价信息
        $goods_evaluate_info = Model('evaluate_goods')->getEvaluateGoodsInfoByGoodsID($goods_id);
        $goods_detail['goods_evaluate_info'] = $goods_evaluate_info;

        $goods_info=$goods_detail['goods_info'];
		//print_r($goods_info);
		$IsHaveBuy=0;
		if(!empty($_COOKIE['username'])){
		   $model_member = Model('member');
		   $member_info= $model_member->getMemberInfo(array('member_name'=>$_COOKIE['username']));
		   $buyer_id=$member_info['member_id'];

		   $promotion_type=$goods_info["promotion_type"];

		   if($promotion_type=='groupbuy')
		   {
		    //检测是否限购数量
			$upper_limit=$goods_info["upper_limit"];
			if($upper_limit>0)
			{
				//查询些会员的订单中，是否已买过了
				$model_order= Model('order');
				 //取商品列表
                $order_goods_list = $model_order->getOrderGoodsList(array('goods_id'=>$goods_id,'buyer_id'=>$buyer_id,'goods_type'=>2));
				if($order_goods_list)
				{
				    //取得上次购买的活动编号(防一个商品参加多次团购活动的问题)
				    $promotions_id=$order_goods_list[0]["promotions_id"];
					//用此编号取数据，检测是否这次活动的订单商品。
					 $model_groupbuy = Model('groupbuy');
					 $groupbuy_info = $model_groupbuy->getGroupbuyInfo(array('groupbuy_id' => $promotions_id));
					 if($groupbuy_info)
					 {
						$IsHaveBuy=1;
					 }
					 else
					 {
						$IsHaveBuy=0;
					 }
				}
			}
		  }
		}
		$goods_detail['IsHaveBuy']=$IsHaveBuy;

        output_data($goods_detail);
    }

    /**
     * 评论列表
     */
    public function goods_evaluateOp() {
        $goods_id = intval($_GET['goods_id']);
        $type = intval($_GET['type']);

        $condition = array();
        $condition['geval_goodsid'] = $goods_id;
        switch ($type) {
            case '1':
                $condition['geval_scores'] = array('in', '5,4');
                break;
            case '2':
                $condition['geval_scores'] = array('in', '3,2');
                break;
            case '3':
                $condition['geval_scores'] = array('in', '1');
                break;
            case '4':
                $condition['geval_image'] = array('neq', '');
                break;
            case '5':
                $condition['geval_content_again'] = array('neq', '');
                break;
        }

        //查询商品评分信息
        $model_evaluate_goods = Model("evaluate_goods");
        $condition['geval_state'] = 0;
        $condition['isanonymous'] = true;
        $goods_eval_list = $model_evaluate_goods->getEvaluateGoodsList($condition, 10);
        $goods_eval_list = Logic('member_evaluate')->evaluateListDity($goods_eval_list);

        $page_count = $model_evaluate_goods->gettotalpage();
        output_data(array('goods_eval_list' => $goods_eval_list), mobile_page($page_count));
    }

    /**
     * 商品详细信息处理
     */
    private function _goods_detail_extend($goods_detail) {
        //整理商品规格
        unset($goods_detail['spec_list']);
        $goods_detail['spec_list'] = $goods_detail['spec_list_mobile'];
        unset($goods_detail['spec_list_mobile']);

        //整理商品图片
        unset($goods_detail['goods_image']);
        $goods_detail['goods_image'] = implode(',', $goods_detail['goods_image_mobile']);
        unset($goods_detail['goods_image_mobile']);

        //商品链接
        $goods_detail['goods_info']['goods_url'] = urlShop('goods', 'index', array('goods_id' => $goods_detail['goods_info']['goods_id']));

        //整理数据
        unset($goods_detail['goods_info']['goods_commonid']);
        unset($goods_detail['goods_info']['gc_id']);
        unset($goods_detail['goods_info']['gc_name']);
        unset($goods_detail['goods_info']['store_name']);
        unset($goods_detail['goods_info']['brand_id']);
        unset($goods_detail['goods_info']['brand_name']);
        unset($goods_detail['goods_info']['type_id']);
        unset($goods_detail['goods_info']['goods_image']);
        unset($goods_detail['goods_info']['goods_body']);
        unset($goods_detail['goods_info']['goods_state']);
        unset($goods_detail['goods_info']['goods_stateremark']);
        unset($goods_detail['goods_info']['goods_verify']);
        unset($goods_detail['goods_info']['goods_verifyremark']);
        unset($goods_detail['goods_info']['goods_lock']);
        unset($goods_detail['goods_info']['goods_addtime']);
        unset($goods_detail['goods_info']['goods_edittime']);
        unset($goods_detail['goods_info']['goods_selltime']);
        unset($goods_detail['goods_info']['goods_show']);
        unset($goods_detail['goods_info']['goods_commend']);
        unset($goods_detail['goods_info']['explain']);
        unset($goods_detail['goods_info']['cart']);
        unset($goods_detail['goods_info']['buynow_text']);
        unset($goods_detail['groupbuy_info']);
        unset($goods_detail['xianshi_info']);

        return $goods_detail;
    }

    /**
     * 商品详细页
     */
    public function goods_bodyOp() {
        $goods_id = intval($_GET ['goods_id']);

        $model_goods = Model('goods');

        $goods_info = $model_goods->getGoodsInfoByID($goods_id, 'goods_commonid');
        $goods_common_info = $model_goods->getGoodeCommonInfoByID($goods_info['goods_commonid']);

        Tpl::output('goods_common_info', $goods_common_info);
        Tpl::showpage('goods_body');
    }
	/**
     * 手机商品详细页
     */
    public function wap_goods_bodyOp() {
        $goods_id = intval($_GET ['goods_id']);

        $model_goods = Model('goods');

        $goods_info =$model_goods->getGoodsInfoByID($goods_id, 'goods_commonid');
        $goods_common_info =$model_goods->getMobileBodyByCommonID($goods_info['goods_commonid']);
        
        $html = '<div class="goods_detail">';
        if($goods_common_info['mobile_body']){
            $mobile_body = unserialize($goods_common_info['mobile_body']);
            
//            foreach ($mobile_body as $k => $v) {
//                if($v['type'] == 'image'){
//                    $html .= '<img src="'.$v['value'].'">';
//                }
//                if($v['type'] == 'text'){
//                    $html .= '<p>'.$v['value'].'</p>';
//                }
//            }
            $html .= $goods_common_info['mobile_body'];
            
        }else{
            $html .= '<div style="width:100%;text-align:center;">暂时没有商品详情</div>';
        }
        $html .= '</div>';
        $goods_common_info['mobile_body'] = $html;

        output_data($goods_common_info);
//        Tpl::output('goods_common_info',$goods_common_info);
//        Tpl::showpage('mobile_body');
    }
}
