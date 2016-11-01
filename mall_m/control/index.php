<?php
/**
 * cms首页
 *
 *
 *
 */

defined('InSystem') or exit('Access Invalid!');
class indexControl extends mobileHomeControl{

	public function __construct() {
        parent::__construct();
    }

    /**
     * 首页
     */
	public function indexOp() {

        $model_mb_special = Model('mb_special');
        $data = $model_mb_special->getMbSpecialIndex();

        //推荐商品
        /*$recommend_goods_list = $this->get_recommend_goods();
        if($recommend_goods_list){
            $count = count($data);
            $data[$count]['recommend_goods']['item'] = $recommend_goods_list;
            $data[$count]['mb_type'] = 'recommend_goods';
        }*/

        $this->_output_special($data, $_GET['type']);
	}

    /**
     * 专题
     */
	public function specialOp() {
        $model_mb_special = Model('mb_special'); 
        $data = $model_mb_special->getMbSpecialItemUsableListByID($_GET['special_id']);

        $this->_output_special($data, $_GET['type'], $_GET['special_id']);
	}

    /**
     * 输出专题
     */
    private function _output_special($data, $type = 'json', $special_id = 0) {
        $model_special = Model('mb_special');
        if($_GET['type'] == 'html') {
            $html_path = $model_special->getMbSpecialHtmlPath($special_id);
            if(!is_file($html_path)) {
                ob_start();
                Tpl::output('list', $data);
                Tpl::showpage('mb_special');
                file_put_contents($html_path, ob_get_clean());
            }
            header('Location: ' . $model_special->getMbSpecialHtmlUrl($special_id));
            die;
        } else {
            output_data($data);
        }
    }

    /**
     * 获取推荐商品
     */
    private function get_recommend_goods(){

        $recommend_goods = $_GET['recommend_goods'];

        $gc_condition = array();
        if($recommend_goods){
            $goods_ids = str_replace('@',',',$recommend_goods);
            $gc_condition['goods_id'] = array('IN',$goods_ids);
        }

        $model_goods = Model('goods');

        //获取浏览商品的分类
        $goods_gc_ids = $model_goods->getGoodsOnlineList($gc_condition, 'gc_id',20,'goods_id desc', 0,'',false,0);

        $condition = array();
        $goods_order = 'goods_commend desc';

        if($goods_gc_ids){
            foreach($goods_gc_ids as $gc){
                $gc_ids[] = $gc['gc_id'];
            }
            $condition['gc_id'] = array('IN',$gc_ids);
        }

        //获取商品
        $fieldstr = "goods_id,goods_commonid,store_id,goods_name,goods_price,goods_marketprice,goods_image,goods_salenum,evaluation_good_star,evaluation_count";

        $goods_list = $model_goods->getGoodsListByColorDistinct($condition,$fieldstr, $goods_order, 50);
        //随机获取四个商品
        $new_goods_list = array();
        $goods_count = count($goods_list);
        if($goods_count > 0){
            if($goods_count > 4){
                $key = rand (0,$goods_count - 1);
                for($i=0;$i<4;$i++){
                    $new_key = $key + $i;
                    if($new_key > $goods_count - 1){
                        $new_key = $new_key - $goods_count;
                    }
                    $new_goods_list[] = $goods_list[$new_key];
                }
            }else{
                $new_goods_list = $goods_list;
            }
            foreach($new_goods_list as $gk => $goods){
                $new_goods_list[$gk]['goods_image_url'] = cthumb($goods['goods_image'], 240);
            }
        }

        return $new_goods_list;
    }

    /**
     * android客户端版本号
     */
    public function apk_versionOp() {
		$version = C('mobile_apk_version');
		$url = C('mobile_apk');
        if(empty($version)) {
           $version = '';
        }
        if(empty($url)) {
            $url = '';
        }

        output_data(array('version' => $version, 'url' => $url));
    }
}
