<?php
defined('InSystem') or exit('Access Invalid!');
/**
 * 会员积分功能公用
 */
$lang['admin_points_unavailable']	 		= '系统未开启会员积分功能';
$lang['admin_points_mod_tip']				= '修改会员积分';
$lang['admin_points_system_desc']			= '管理员手动操作会员积分';
$lang['admin_points_userrecord_error']		= '会员信息错误';
$lang['admin_points_membername']			= '会员名称';
$lang['admin_points_operatetype']			= '增减类型';
$lang['admin_points_operatetype_add']		= '增加';
$lang['admin_points_operatetype_reduce']	= '减少';
$lang['admin_points_pointsnum']				= '会员积分值';
$lang['admin_points_pointsdesc']			= '描述';
$lang['admin_points_pointsdesc_notice']		= '所增加积分会在第二天返还，请填写相关凭证。，会员和管理员都可见';

/**
 * 会员积分添加
 */
$lang['admin_points_member_error_again']	= '会员信息错误，请重新填写会员名';
$lang['admin_points_points_null_error']		= '请添加会员积分值';
$lang['admin_points_points_min_error']		= '会员积分值必须大于0';
$lang['admin_points_points_short_error']	= '会员积分不足，会员当前会员积分数为';
$lang['admin_points_addmembername_error']	= '请输入会员名';
$lang['admin_points_member_tip_1']			= '会员';
$lang['admin_points_member_tip_2']			= ', 当前会员积分数为';
/**
 * 会员积分日志
 */
$lang['admin_points_log_title']			= '会员积分明细';
$lang['admin_points_adminname']				= '管理员名称';
$lang['admin_points_stage']					= '操作阶段';
$lang['admin_points_stage_regist']				= '注册';
$lang['admin_points_stage_login']				= '登录';
$lang['admin_points_stage_comments']				= '商品评论';
$lang['admin_points_stage_order']				= '订单消费';
$lang['admin_points_stage_system']				= '会员积分管理';
$lang['admin_points_stage_pointorder']		= '礼品兑换';
$lang['admin_points_stage_app']		= '会员积分兑换';
$lang['admin_points_addtime']				= '添加时间';
$lang['admin_points_addtime_to']				= '至';
$lang['admin_points_log_help1']				= '会员积分明细，展示了被操作人员（会员）、操作人员（管理员）、操作会员积分数（会员积分值，“-”表示减少，无符号表示增加）、操作时间（添加时间）等信息';



