<?php

/**
 * 载入权限
 *
 */
defined('InSystem') or exit('Access Invalid!');
$_limit = array(
    array('name' => $lang['nc_config'], 'child' => array(
            array('name' => $lang['nc_upload_set'], 'op' => null, 'act' => 'upload'),
            array('name' => '协议帮助', 'op' => null, 'act' => 'document'),
            array('name' => '上传设置', 'op' => 'param', 'act' => 'upload'),
            array('name' => '消息通知', 'op' => 'mobile', 'act' => 'message'),
            array('name' => '支付方式', 'op' => 'system', 'act' => 'payment'),
            array('name' => '权限设置', 'op' => 'admin', 'act' => 'admin'),
            array('name' => '清理缓存', 'op' => 'cache', 'act' => 'cache'),
            array('name' => '数据备份', 'op' => 'db', 'act' => 'db'),
            array('name' => '操作日期', 'op' => 'list', 'act' => 'admin_log'),
            array('name' => '问题分类', 'op' => null, 'act' => 'article_class'),
            array('name' => '问题管理', 'op' => null, 'act' => 'article_class'),
        )),
    array('name' => $lang['nc_goods'], 'child' => array(
            array('name' => $lang['nc_goods_manage'], 'op' => null, 'act' => 'goods'),
            array('name' => $lang['nc_class_manage'], 'op' => null, 'act' => 'goods_class'),
            array('name' => $lang['nc_brand_manage'], 'op' => null, 'act' => 'brand'),
            array('name' => $lang['nc_type_manage'], 'op' => null, 'act' => 'type'),
            array('name' => $lang['nc_spec_manage'], 'op' => null, 'act' => 'spec'),
            array('name' => $lang['nc_album_manage'], 'op' => null, 'act' => 'goods_album'),
        )),
    array('name' => $lang['nc_store'], 'child' => array(
            array('name' => $lang['nc_store_manage'], 'op' => null, 'act' => 'store'),
        array('name' => '车主服务商', 'op' => null, 'act' => 'store')
        )),
    array('name' => '代理管理', 'child' => array(
        array('name' => '代理管理', 'op' => null, 'act' => 'agent')
    )),
    array('name' => $lang['nc_member'], 'child' => array(
            array('name' => $lang['nc_member_manage'], 'op' => null, 'act' => 'member'),
            array('name' => '会员级别', 'op' => null, 'act' => 'member_grade'),
            array('name' => $lang['nc_member_notice'], 'op' => null, 'act' => 'notice'),
            array('name' => $lang['nc_member_pointsmanage'], 'op' => null, 'act' => 'points'),
            array('name' => '现金充值', 'op' => 'manual_add', 'act' => 'predeposit'),
            array('name' => '奖励', 'op' => null, 'act' => 'points_inviter'),
            array('name' => '奖励', 'op' => null, 'act' => 'invite'),
            array('name' => $lang['nc_member_predepositmanage'], 'op' => null, 'act' => 'predeposit'),
            array('name' => '油卡管理', 'op' => null, 'act' => 'oil'),
            array('name' => '油卡充值', 'op' => null, 'act' => 'oil')
        )),
    array('name' => $lang['nc_trade'], 'child' => array(
            array('name' => $lang['nc_order_manage'], 'op' => null, 'act' => 'order'),
            array('name' => '虚拟订单', 'op' => null, 'act' => 'vr_order'),
            array('name' => '退款管理', 'op' => null, 'act' => 'refund'),
            array('name' => '退货管理', 'op' => null, 'act' => 'return'),
            array('name' => '虚拟订单退款', 'op' => null, 'act' => 'vr_refund'),
            array('name' => $lang['nc_consult_manage'], 'op' => null, 'act' => 'consulting'),
            array('name' => $lang['nc_inform_config'], 'op' => null, 'act' => 'inform'),
            array('name' => $lang['nc_goods_evaluate'], 'op' => null, 'act' => 'evaluate'),
            array('name' => $lang['nc_complain_config'], 'op' => null, 'act' => 'complain'),
        )),
    array('name' => $lang['nc_operation'], 'child' => array(
            array('name' => $lang['nc_operation_set'], 'op' => null, 'act' => 'operation'),
            array('name' => '电子消费券', 'op' => null, 'act' => 'coupons')
        )),
    array('name' => '0元淘', 'child' => array(
            array('name' => '商品列表', 'op' => null, 'act' => 'zero_goods'),
            array('name' => '订单列表', 'op' => null, 'act' => 'zero_order')
        )),
);
$_limit[] = array('name' => $lang['nc_mobile'], 'child' => array(
    array('name' => '首页设置', 'op' => NULL, 'act' => 'mb_special'),
    array('name' => 'banner管理', 'op' => NULL, 'act' => 'banner'),
    /* array('name'=>'专题设置', 'op'=>NULL, 'act'=>'mb_special'),
      array('name'=>$lang['nc_mobile_catepic'], 'op'=>NULL, 'act'=>'mb_category'),
      array('name'=>'下载设置', 'op'=>NULL, 'act'=>'mb_app'),
      array('name'=>$lang['nc_mobile_feedback'], 'op'=>NULL, 'act'=>'mb_feedback'),
      array('name'=>'手机支付', 'op'=>NULL, 'act'=>'mb_payment'), */
        ));
return $_limit;
