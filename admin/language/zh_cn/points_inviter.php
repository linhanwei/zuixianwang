<?php
defined('InSystem') or exit('Access Invalid!');
/**
 * 奖励功能公用
 */
$lang['admin_points_unavailable']	 		= '系统未开启奖励功能';
$lang['admin_points_mod_tip']				= '修改奖励';
$lang['admin_points_system_desc']			= '管理员手动操作奖励';
$lang['admin_points_userrecord_error']		= '会员信息错误';
$lang['admin_points_membername']			= '会员名称';
$lang['admin_points_operatetype']			= '增减类型';
$lang['admin_points_operatetype_add']		= '增加';
$lang['admin_points_operatetype_reduce']	= '减少';
$lang['admin_points_pointsnum']				= '奖励值';
$lang['admin_points_pointsdesc']			= '描述';
$lang['admin_points_pointsdesc_notice']		= '积分可转换到会员积分进行返还';

/**
 * 奖励添加
 */
$lang['admin_points_member_error_again']	= '会员信息错误，请重新填写会员名';
$lang['admin_points_points_null_error']		= '请添加奖励值';
$lang['admin_points_points_min_error']		= '奖励值必须大于0';
$lang['admin_points_points_short_error']	= '奖励不足，会员当前奖励数为';
$lang['admin_points_addmembername_error']	= '请输入会员名';
$lang['admin_points_member_tip_1']			= '会员';
$lang['admin_points_member_tip_2']			= ', 当前奖励数为';
/**
 * 奖励日志
 */
$lang['admin_points_log_title']			= '奖励明细';
$lang['admin_points_adminname']				= '管理员名称';
$lang['admin_points_stage']					= '操作阶段';
$lang['admin_points_stage_regist']				= '注册';
$lang['admin_points_stage_login']				= '登录';
$lang['admin_points_stage_comments']				= '商品评论';
$lang['admin_points_stage_order']				= '订单消费';
$lang['admin_points_stage_system']				= '奖励管理';
$lang['admin_points_stage_pointorder']		= '礼品兑换';
$lang['admin_points_stage_app']		= '奖励兑换';
$lang['admin_points_addtime']				= '添加时间';
$lang['admin_points_addtime_to']				= '至';
$lang['admin_points_log_help1']				= '奖励明细，展示了被操作人员（会员）、操作人员（管理员）、操作奖励数（奖励值，“-”表示减少，无符号表示增加）、操作时间（添加时间）等信息';