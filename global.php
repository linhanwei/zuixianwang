<?php
/**
 * 入口文件
 *
 * 统一入口，进行初始化信息
 *
 *
 */

error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE );
define('BASE_ROOT_PATH',str_replace('\\','/',dirname(__FILE__)));

define('BASE_CORE_PATH',BASE_ROOT_PATH.'/core');
define('BASE_DATA_PATH',BASE_ROOT_PATH.'/data');
define('DS','/');
define('InSystem',true);
define('StartTime',microtime(true));
define('TIMESTAMP',time());
define('DIR_ADMIN','admin');
define('DIR_API','api');

define('DIR_RESOURCE','data/resource');
define('DIR_UPLOAD','data/upload');

define('ATTACH_PATH','m');
define('ATTACH_COMMON',ATTACH_PATH.'/common');
define('ATTACH_AVATAR',ATTACH_PATH.'/avatar');
define('ATTACH_IDCARD',ATTACH_PATH.'/idcard');
define('ATTACH_EDITOR',ATTACH_PATH.'/editor');
define('ATTACH_STORE',ATTACH_PATH.'/store');
define('ATTACH_GOODS',ATTACH_PATH.'/store/goods');
define('ATTACH_STORE_DECORATION',ATTACH_PATH.'/store/decoration');
define('ATTACH_ZERO_GOODS',ATTACH_PATH.'/store/zero_goods');
define('ATTACH_MEMBER',ATTACH_PATH.'/member');
define('ATTACH_QRCODE',ATTACH_PATH.'/qrcode');
define('ATTACH_STORE_JOININ',ATTACH_PATH.'/store_joinin');
define('ATTACH_MOBILE','mobile');

define('TPL_ADMIN_NAME', 'default');

//升级分佣
define('UPGRADE_COMMIS',json_encode(array(100,100,200)));
define('UPGRADE_AGENT_PROVINCE',0);
define('UPGRADE_AGENT_CITY',100);
define('UPGRADE_AGENT_AREA',300);

//流量分佣
define('INVITE_RATE',json_encode(array(0.02,0.02,0.02)));
define('INVITE_AGENT_PROVINCE',0.005);
define('INVITE_AGENT_CITY',0.01);
define('INVITE_AGENT_AREA',0.01);

//油卡相关
define('OIL_PRICE',500);
define('OIL_RATE',0.95);

/**
 * 商品图片
 */
define('GOODS_IMAGES_WIDTH', '60,240,360,1280');
define('GOODS_IMAGES_HEIGHT', '60,240,360,12800');
define('GOODS_IMAGES_EXT', '_60,_240,_360,_1280');

/**
 *  订单状态
 */
//已取消
define('ORDER_STATE_CANCEL', 0);
//已产生但未支付
define('ORDER_STATE_NEW', 10);
//已支付
define('ORDER_STATE_PAY', 20);
//已发货
define('ORDER_STATE_SEND', 30);
//已收货，交易成功
define('ORDER_STATE_SUCCESS', 40);
//未付款订单，自动取消的天数
define('ORDER_AUTO_CANCEL_DAY', 3);
//已发货订单，自动确认收货的天数
define('ORDER_AUTO_RECEIVE_DAY', 7);
//兑换码支持过期退款，可退款的期限，默认为7天
define('CODE_INVALID_REFUND', 7);
//默认未删除
define('ORDER_DEL_STATE_DEFAULT', 0);
//已删除
define('ORDER_DEL_STATE_DELETE', 1);
//彻底删除
define('ORDER_DEL_STATE_DROP', 2);
//订单结束后可评论时间，15天，60*60*24*15
define('ORDER_EVALUATE_TIME', 1296000);
//抢购订单状态
define('OFFLINE_ORDER_CANCEL_TIME', 3);//单位为天

