<?php
/**
 * 商品评价模型
 *
 * by 33hao 好商城V3  www.33hao.com 开发
 */
defined('InSystem') or exit('Access Invalid!');
class evaluate_goodsModel extends Model {

    public function __construct(){
        parent::__construct('evaluate_goods');
    }

	/**
	 * 查询评价列表
     *
	 * @param array $condition 查询条件
	 * @param int $page 分页数
	 * @param string $order 排序
	 * @param string $field 字段
     * @return array
	 */
    public function getEvaluateGoodsList($condition, $page = null, $order = 'geval_id desc', $field = '*') {
        $isanonymous = $condition['isanonymous'];
        if($isanonymous) unset($condition['isanonymous']);
        $list = $this->field($field)->where($condition)->page($page)->order($order)->select();
        if($list){
            $model_member = Model('member');

            foreach($list as $k=>$v){
                $geval_image_list = unserialize($v['geval_image']);
                $list[$k]['geval_image'] = $geval_image_list;
                $list[$k]['geval_addtime_date'] = date('Y-m-d',$v['geval_addtime']);
                $geval_frommembername = $v['geval_frommembername'];
                $member_info = $model_member->getMemberInfoByName($geval_frommembername);

                $geval_frommembername = $member_info['member_nickname'] ? $member_info['member_nickname'] : $geval_frommembername;

                //你们评价
                if($isanonymous && ($v['geval_isanonymous'] == 1 || empty($member_info['member_nickname']))){
                    $list[$k]['geval_frommembername'] = $this->msubstr($geval_frommembername,0,1,true).$this->msubstr($geval_frommembername,-1,1,false);
                }else{
                    $list[$k]['geval_frommembername'] = $geval_frommembername;
                }

                $list[$k]['geval_image_url'] = array();
                if($geval_image_list){
                    foreach($geval_image_list as $egv){
                        $list[$k]['geval_image_url'][] = getEvaluateImg($egv,$v['geval_frommemberid'],240);
                    }
                }
            }
        }
        return $list;
    }

    protected function msubstr($str, $start=0, $length, $suffix=true, $charset="utf-8") {
        if(function_exists("mb_substr"))
            $slice = mb_substr($str, $start, $length, $charset);
        elseif(function_exists('iconv_substr')) {
            $slice = iconv_substr($str,$start,$length,$charset);
        }else{
            $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
            $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
            $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
            preg_match_all($re[$charset], $str, $match);
            $slice = join("",array_slice($match[0], $start, $length));
        }
        return $suffix ? $slice.'***' : $slice;
    }

    /**
     * 根据编号查询商品评价 
     */
    public function getEvaluateGoodsInfoByID($geval_id, $store_id = 0) {
        if(intval($geval_id) <= 0) {
            return null;
        }

        $info = $this->where(array('geval_id' => $geval_id))->find();

        if($store_id > 0 && intval($info['geval_storeid']) !== $store_id) {
            return null;
        } else {
            return $info;
        }
    }

    /**
     * 根据商品编号查询商品评价信息 
     */
    public function getEvaluateGoodsInfoByGoodsID($goods_id) {
        $prefix = 'goods_evaluation';
        $info = rcache($goods_id, $prefix);
        if(empty($info)) {
            $info = array();
            $count_array = $this->field('count(*) as count,geval_scores')->where(array('geval_goodsid' => $goods_id))->group('geval_scores')->key(geval_scores)->select();
            $star1 = intval($count_array['1']['count']);
            $star2 = intval($count_array['2']['count']);
            $star3 = intval($count_array['3']['count']);
            $star4 = intval($count_array['4']['count']);
            $star5 = intval($count_array['5']['count']);
            $info['good'] = $star4 + $star5;
            $info['normal'] = $star2 + $star3;
            $info['bad'] = $star1;
            $info['all'] = $star1 + $star2 + $star3 + $star4 + $star5;
            if(intval($info['all']) > 0) {
                $info['good_percent'] = intval($info['good'] / $info['all'] * 100);
                $info['normal_percent'] = intval($info['normal'] / $info['all'] * 100);
                $info['bad_percent'] = intval($info['bad'] / $info['all'] * 100);
                $info['good_star'] = ceil($info['good'] / $info['all'] * 5);
                $info['star_average'] = ceil(($star1 + $star2 * 2 + $star3 * 3 + $star4 * 4 + $star5 * 5) / $info['all']);
            } else {
                $info['good_percent'] = 100;
                $info['normal_percent'] = 0;
                $info['bad_percent'] = 0;
                $info['good_star'] = 5;
                $info['star_average'] = 5;
            }

            //更新商品表好评星级和评论数
            $model_goods = Model('goods');
            $update = array();
            $update['evaluation_good_star'] = $info['star_average'];
            $update['evaluation_count'] = $info['all'];
            $model_goods->editGoodsById($update, $goods_id);
            wcache($goods_id, $info, $prefix);
        }
        return $info;
    }

    /**
     * 根据抢购编号查询商品评价信息 
     */
    public function getEvaluateGoodsInfoByCommonidID($goods_commonid) {
        $prefix = 'goods_common_evaluation';
        $info = rcache($goods_commonid, $prefix);
        if(empty($info)) {
            $info = array();
            $info['good_percent'] = 100;
            $info['normal_percent'] = 0;
            $info['bad_percent'] = 0;
            $info['good_star'] = 5;
            $info['all'] = 0;
            $info['good'] = 0;
            $info['normal'] = 0;
            $info['bad'] = 0;
            
            $condition = array();
            $condition['goods_commonid'] = $goods_commonid;
            $goods_list = Model('goods')->getGoodsList($condition, 'goods_id');
            if (!empty($goods_list)) {
                $goodsid_array = array();
                foreach ($goods_list as $value) {
                    $goodsid_array[] = $value['goods_id'];
                }
                $good = $this->where(array('geval_goodsid'=>array('in' ,$goodsid_array),'geval_scores' => array('in', '4,5')))->count();
                $info['good'] = $good;
                $normal = $this->where(array('geval_goodsid'=>array('in' ,$goodsid_array),'geval_scores' => array('in', '2,3')))->count();
                $info['normal'] = $normal; 
                $bad = $this->where(array('geval_goodsid'=>array('in' ,$goodsid_array),'geval_scores' => array('in', '1')))->count();
                $info['bad'] = $bad; 
                $info['all'] = $info['good'] + $info['normal'] + $info['bad']; 
                if(intval($info['all']) > 0) {
                    $info['good_percent'] = intval($info['good'] / $info['all'] * 100);
                    $info['normal_percent'] = intval($info['normal'] / $info['all'] * 100);
                    $info['bad_percent'] = intval($info['bad'] / $info['all'] * 100);
                    $info['good_star'] = ceil($info['good'] / $info['all'] * 5);
                }
            }
            wcache($goods_commonid, $info, $prefix, 24*60); // 缓存周期1天。
        }
        return $info;
    }

    /**
     * 批量添加商品评价
     * 
     * @param array $param
     * @param array $goodsid_array 商品id数组，更新缓存使用
     * @return boolean
     */
    public function addEvaluateGoodsArray($param, $goodsid_array) {
        $result = $this->insertAll($param);
        // 删除商品评价缓存
        if ($result && !empty($goodsid_array)) {
            foreach ($goodsid_array as $goods_id) {
                dcache($goods_id, 'goods_evaluation');
            }
        }
        return $result;
    }

    /**
     * 更新商品评价
     * 
     * 现在此方法只是编辑晒单，不需要更新缓存
     * 如果使用此方法修改大星星数量请根据goods_id删除缓存
     * 例：dcache($goods_id, 'goods_evaluation');
     */
    public function editEvaluateGoods($update, $condition) {
        return $this->where($condition)->update($update);
    }

    /**
     * 删除商品评价
     */
    public function delEvaluateGoods($condition) {
        return $this->where($condition)->delete();
    }
}
