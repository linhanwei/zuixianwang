<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.mousewheel.js"></script>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['dashboard_wel_system_info'];?><!--<?php echo $lang['dashboard_wel_lase_login'].$lang['nc_colon'];?><?php echo $output['admin_info']['admin_login_time'];?>--></h3>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="info-panel">
    <dl class="member">
      <dt>
        <div class="ico"><i></i><sub title="<?php echo $lang['dashboard_wel_total_member'];?>"><span><em id="statistics_member"></em></span></sub></div>
        <h3><?php echo $lang['nc_member'];?></h3>
        <h5><?php echo $lang['dashboard_wel_member_des'];?></h5>
      </dt>
      <dd>
        <ul>
          <li class="w50pre normal"><a href="index.php?act=member&op=member"><?php echo $lang['dashboard_wel_new_add'];?><sub><em id="statistics_week_add_member"></em></sub></a></li>
          <li class="w50pre none"><a href="index.php?act=predeposit&op=pd_cash_list"><?php echo $lang['dashboard_wel_predeposit_get'];?><sub><em id="statistics_cashlist">0</em></sub></a></li>
        </ul>
      </dd>
    </dl>
    <dl class="shop">
      <dt>
        <div class="ico"><i></i><sub title="<?php echo $lang['dashboard_wel_count_store_add'];?>"><span><em id="statistics_store"></em></span></sub></div>
        <h3><?php echo $lang['nc_store'];?></h3>
        <h5><?php echo $lang['dashboard_wel_store_des'];?></h5>
      </dt>
      <dd>
        <ul>
          <li class="w20pre none"><a href="index.php?act=store&op=store_joinin">开店审核<sub><em id="statistics_store_joinin">0</em></sub></a></li>
          <li class="w20pre none"><a href="index.php?act=store&op=store_bind_class_applay_list&state=0">类目申请<sub><em id="statistics_store_bind_class_applay">0</em></sub></a></li>
          <li class="w20pre none"><a href="index.php?act=store&op=reopen_list&re_state=1">续签申请<sub><em id="statistics_store_reopen_applay">0</em></sub></a></li>
          <li class="w20pre none"><a href="index.php?act=store&op=store&store_type=expired"><?php echo $lang['dashboard_wel_expired'];?><sub><em id="statistics_store_expired">0</em></sub></a></li>
          <li class="w20pre none"><a href="index.php?act=store&op=store&store_type=expire"><?php echo $lang['dashboard_wel_expire'];?><sub><em id="statistics_store_expire">0</em></sub></a></li>
        </ul>
      </dd>
    </dl>

    <dl class="operation">
      <dt>
        <div class="ico"><i></i></div>
        <h3><?php echo $lang['nc_operation'];?></h3>
        <h5><?php echo $lang['dashboard_wel_stat_des'];?></h5>
      </dt>
      <dd>
        <ul>

          <li class="w17pre none"><a href="<?php echo urlAdmin('mall_consult', 'index');?>">平台客服<sub><em id="statistics_mall_consult">0</em></sub></a></li>
          <li class="w17pre none"><a href="<?php echo urlAdmin('delivery', 'index', array('sign' => 'verify'));?>">服务站<sub><em id="statistics_delivery_point">0</em></sub></a></li>
        </ul>
      </dd>
    </dl>


    <div class="clear"></div>
    <div class="system-info"></div>
  </div>
</div>
<script type="text/javascript">
var normal = ['week_add_member','week_add_product'];
var work = ['store_joinin','store_bind_class_applay','store_reopen_applay','store_expired','store_expire','brand_apply','cashlist','groupbuy_verify_list','points_order','complain_new_list','complain_handle_list', 'product_verify','inform_list','refund','return','vr_refund','cms_article_verify','cms_picture_verify','circle_verify','check_billno','pay_billno','mall_consult','delivery_point','offline'];
$(document).ready(function(){
	$.getJSON("index.php?act=dashboard&op=statistics", function(data){
	  $.each(data, function(k,v){
		  $("#statistics_"+k).html(v);
		  if (v!= 0 && $.inArray(k,work) !== -1){
			$("#statistics_"+k).parent().parent().parent().removeClass('none').addClass('high');
		  }else if (v == 0 && $.inArray(k,normal) !== -1){
			$("#statistics_"+k).parent().parent().parent().removeClass('normal').addClass('none');
		  }
	  });
	});
	//自定义滚定条
	$('#system-info').perfectScrollbar();
});
</script>
