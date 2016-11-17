<?php

/**
 * 菜单
 *
 */
defined('InSystem') or exit('Access Invalid!');
/**
 * top 数组是顶部菜单 ，left数组是左侧菜单
 * left数组中'args'=>'welcome,dashboard,dashboard',三个分别为op,act,nav，权限依据act来判断
 */
$arr = array(
    'top' => array(
        0 => array(
            'args' => 'dashboard',
            'text' => $lang['nc_console']),
        1 => array(
            'args' => 'setting',
            'text' => $lang['nc_config']),
        2 => array(
            'args' => 'goods',
            'text' => $lang['nc_goods']),
        3 => array(
            'args' => 'store',
            'text' => $lang['nc_store']),
        4 => array(
            'args' => 'agent',
            'text' => '代理商'),
        5 => array(
            'args' => 'member',
            'text' => $lang['nc_member']),
        6 => array(
            'args' => 'trade',
            'text' => $lang['nc_trade']),
        7 => array(
            'args' => 'operation',
            'text' => $lang['nc_operation']),
        8 => array(
            'args' => 'zero_buy',
            'text' => '0元淘'),
        9 => array(
            'args' => 'activity_project',
            'text' => '活动'),
    ),
    'left' => array(
        0 => array(
            'nav' => 'dashboard',
            'text' => $lang['nc_normal_handle'],
            'list' => array(
                array('args' => 'welcome,dashboard,dashboard', 'text' => $lang['nc_welcome_page']),
                array('args' => 'base,setting,dashboard', 'text' => $lang['nc_web_set']),
                array('args' => 'member,member,dashboard', 'text' => $lang['nc_member_manage']),
                array('args' => 'store,store,dashboard', 'text' => $lang['nc_store_manage']),
                array('args' => 'goods,goods,dashboard', 'text' => $lang['nc_goods_manage']),
                array('args' => 'index,order,dashboard', 'text' => $lang['nc_order_manage']),
            )
        ),
        1 => array(
            'nav' => 'setting',
            'text' => $lang['nc_config'],
            'list' => array(
                /* array('args'=>'base,setting,setting',			'text'=>$lang['nc_web_set']), */
                array('args' => 'document,document,setting', 'text' => '协议帮助'),
                array('args' => 'param,upload,setting', 'text' => $lang['nc_upload_set']),
                array('args' => 'mobile,message,setting', 'text' => $lang['nc_message_set']),
                array('args' => 'system,payment,setting', 'text' => $lang['nc_pay_method']),
                array('args' => 'admin,admin,setting', 'text' => $lang['nc_limit_manage']),
                array('args' => 'clear,cache,setting', 'text' => $lang['nc_admin_clear_cache']),
                array('args' => 'db,db,setting', 'text' => '数据备份'),
                array('args' => 'list,admin_log,setting', 'text' => $lang['nc_admin_log']),
                array('args' => 'article_class,question_class,setting', 'text' => '问题分类'),
                array('args' => 'article,question,setting', 'text' => '问题管理')
            )
        ),
        2 => array(
            'nav' => 'goods',
            'text' => $lang['nc_goods'],
            'list' => array(
                array('args' => 'goods_class,goods_class,goods', 'text' => $lang['nc_class_manage']),
                array('args' => 'brand,brand,goods', 'text' => $lang['nc_brand_manage']),
                array('args' => 'goods,goods,goods', 'text' => $lang['nc_goods_manage']),
                array('args' => 'type,type,goods', 'text' => $lang['nc_type_manage']),
                array('args' => 'spec,spec,goods', 'text' => $lang['nc_spec_manage']),
                array('args' => 'list,goods_album,goods', 'text' => $lang['nc_album_manage']),
            )
        ),
        3 => array(
            'nav' => 'store',
            'text' => $lang['nc_store'],
            'list' => array(
                array('args' => 'store,store,store', 'text' => $lang['nc_store_manage']),
                array('args' => 'store,store_lbs,store', 'text' => '车主服务商'),
            )
        ),
        4 => array(
            'nav' => 'agent',
            'text' => '代理商',
            'list' => array(
                array('args' => 'agent,agent,agent', 'text' => '代理商管理'),
            )
        ),
        5 => array(
            'nav' => 'member',
            'text' => $lang['nc_member'],
            'list' => array(
                array('args' => 'member,member,member', 'text' => $lang['nc_member_manage']),
                array('args' => 'index,member_grade,member', 'text' => '会员级别'),
                array('args' => 'notice,notice,member', 'text' => $lang['nc_member_notice']),
                array('args' => 'addpoints,points,member', 'text' => $lang['nc_member_pointsmanage']),
                array('args' => 'manual_add,predeposit,member', 'text' => '现金充值'),
                array('args' => 'points_inviter_log,points_inviter,member', 'text' => '奖励'),
                array('args' => 'index,invite,member', 'text' => '推荐人统计'),
                array('args' => 'predeposit,predeposit,member', 'text' => $lang['nc_member_predepositmanage']),
                array('args' => 'feedback,agent,member', 'text' => '加盟申请'),
                array('args' => 'card_list,oil,member', 'text' => '油卡管理'),
                array('args' => 'recharge,oil,member', 'text' => '油卡充值')
            )
        ),
        6 => array(
            'nav' => 'trade',
            'text' => $lang['nc_trade'],
            'list' => array(
                array('args' => 'index,order,trade', 'text' => $lang['nc_order_manage']),
                array('args' => 'index,vr_order,trade', 'text' => '虚拟订单'),
                array('args' => 'refund_manage,refund,trade', 'text' => '退款管理'),
                array('args' => 'return_manage,return,trade', 'text' => '退货管理'),
                array('args' => 'refund_manage,vr_refund,trade', 'text' => '虚拟订单退款'),
                array('args' => 'consulting,consulting,trade', 'text' => $lang['nc_consult_manage']),
                array('args' => 'inform_list,inform,trade', 'text' => $lang['nc_inform_config']),
                array('args' => 'evalgoods_list,evaluate,trade', 'text' => $lang['nc_goods_evaluate']),
                array('args' => 'complain_new_list,complain,trade', 'text' => $lang['nc_complain_config']),
            )
        ),
        7 => array(
            'nav' => 'operation',
            'text' => $lang['nc_operation'],
            'list' => array(
                array('args' => 'setting,operation,operation', 'text' => $lang['nc_operation_set']),
                array('args' => 'setting,coupons,operation', 'text' => '电子消费券'),
                array('args' => 'index,fund,operation', 'text' => '公益慈善')
            )
        ),
        8 => array(
            'nav' => 'zero_buy',
            'text' => '0元淘',
            'list' => array(
                array('args' => 'index,zero_goods,zero_buy', 'text' => '商品列表'),
                array('args' => 'index,zero_order,zero_buy', 'text' => '订单列表'),
            )
        ),
        9 => array(
            'nav' => 'activity_project',
            'text' => '活动',
            'list' => array(
                array('args' => 'fang,activity_project,activity_project', 'text' => '房商城'),
                array('args' => 'che,activity_project,activity_project', 'text' => '车商城'),
                array('args' => 'baoxian,activity_project,activity_project', 'text' => '汽车保险'),
                array('args' => 'huodong,activity_project,activity_project', 'text' => '会员活动'),
                array('args' => 'zongcou,activity_project,activity_project', 'text' => '车主众筹')
            )
        ),
    ),
);

//手机端商城
$arr['top'][] = array(
    'args' => 'mobile',
    'text' => $lang['nc_mobile']);
$arr['left'][] = array(
    'nav' => 'mobile',
    'text' => $lang['nc_mobile'],
    'list' => array(
        array('args' => 'index_edit,mb_special,mobile', 'text' => '首页编辑'),
        array('args' => 'index,banner,mobile', 'text' => 'banner管理'),
//     array('args'=>'special_list,mb_special,mobile',				'text'=>'专题设置'),
     array('args'=>'upload_apk,mb_index,mobile',				'text'=>'apk上传'),
        /* array('args'=>'mb_category_list,mb_category,mobile',	'text'=>$lang['nc_mobile_catepic']),
         array('args'=>'mb_app,mb_app,mobile',				'text'=>'下载设置'),
         array('args'=>'flist,mb_feedback,mobile',					'text'=>$lang['nc_mobile_feedback']),
         array('args'=>'mb_payment,mb_payment,mobile',				'text'=>'手机支付'), */
    )
);
return $arr;
