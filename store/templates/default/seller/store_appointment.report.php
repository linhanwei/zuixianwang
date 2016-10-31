<?php defined('InSystem') or exit('Access Invalid!');?>
<form method="get" action="index.php" target="_self">
  <table class="search-form">
    <input type="hidden" name="act" value="store_appointment_report" />
    <input type="hidden" name="op" value="index" />

    <tr>
      <th>提交预约时间</th>
      <td class="w240"><input type="text" class="text w70" name="query_start_date" id="query_start_date" value="<?php echo $_GET['query_start_date']; ?>" /><label class="add-on"><i class="icon-calendar"></i></label>&nbsp;&#8211;&nbsp;<input id="query_end_date" class="text w70" type="text" name="query_end_date" value="<?php echo $_GET['query_end_date']; ?>" /><label class="add-on"><i class="icon-calendar"></i></label></td>
      <th>负责人</th>
      <td class="w160"><input type="text" class="text w150" name="member_name" value="<?php echo $_GET['member_name']; ?>" /></td>
      <td class="w70 tc"><label class="submit-border">
          <input type="submit" class="submit" value="搜索" />
        </label></td>
    </tr>
  </table>
</form>
<div class="alert alert-info mt10" style="clear:both;">
    <ul class="mt5">
    <li>
		<span class="w210 fl h30" style="display:block;">
			<i title="店铺有效预约单总数" class="tip icon-question-sign"></i>
			预约总单数：<strong><?php echo $output['state_arr']['all'];?></strong>
		</span>
		<span class="w210 fl h30" style="display:block;">
			<i title="店铺洽谈中总数" class="tip icon-question-sign"></i>
			近30天洽谈中数：<strong><?php echo $output['state_arr']['2'];?></strong>
		</span>
		<span class="w210 fl h30" style="display:block;">
			<i title="店铺未达成预约总数" class="tip icon-question-sign"></i>
			近30天下未达成预约数：<strong><?php echo $output['state_arr']['3'];?></strong>
		</span>
		<span class="w210 fl h30" style="display:block;">
			<i title="店铺已完成预约总数" class="tip icon-question-sign"></i>
			近30天下已完成预约数：<strong><?php echo $output['state_arr']['4'];?></strong>
		</span>
    </li>
    <li>
    	<span class="w210 fl h30" style="display:block;">
    		<i title="店铺商品下单总数" class="tip icon-question-sign"></i>
    		总下单数：<strong><?php echo $output['state_arr']['sn'];?></strong>
    	</span>
    </li>
    
  </ul>
  <div style="clear:both;"></div>
</div>

<table class="ncsc-default-table order">
  <thead>
    <tr>
      <th class="w100">预约单号</th>
      <th class="w100">姓名</th>
      <th class="w40">电话</th>
      <th class="w110">预约时间</th>
      <th class="w120">预约类型</th>
      <th class="w100">预算</th>
      <th class="w100">状态</th>
      <th class="w100">负责人</th>
    </tr>
  </thead>
  <?php if (is_array($output['order_list']) and !empty($output['order_list'])) { ?>
  
  <tbody>
  <?php foreach($output['order_list'] as $order_id => $order) { ?>
    <tr>
      <td ><?php echo $order['order_sn']; ?></td>
      <td><?php echo $order['name']; ?></td>
      <td><?php echo $order['phone']; ?></td>
      <td><?php echo $order['time']; ?></td>
      <td><?php echo $order['type']; ?></td>
      <td><?php echo $order['budget']; ?></td>
      <td><?php echo $order['state_desc']; ?></td>
      <td><?php echo $order['member_name']; ?></td>
    </tr>
     <?php if ($order['state'] > 1) { ?>
    <tr>
    	<td colspan="9" style="font-weight:bold;text-align:left;">
    	处理过程：<?php 
    	echo '<br/>1. ' . $order['remark_2'];
    	if($order['remark_3']){
    		echo '<br/>2. ' . $order['remark_3'];
    	}
     if($order['remark_4']){
    		echo '<br/>2. ' . $order['remark_4'];
    	}
     if($order['remark_sn']){
     	echo '<br/>3. ' . $order['remark_sn'];
     }?>
    	</td>
    </tr>
    <?php }?>
    <?php } } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <?php if (is_array($output['order_list']) and !empty($output['order_list'])) { ?>
    <tr>
      <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
    </tr>
    <?php } ?>
  </tfoot>
</table>
<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" ></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript">
$(function(){
    $('#query_start_date').datepicker({dateFormat: 'yy-mm-dd'});
    $('#query_end_date').datepicker({dateFormat: 'yy-mm-dd'});
    $('.checkall_s').click(function(){
        var if_check = $(this).attr('checked');
        $('.checkitem').each(function(){
            if(!this.disabled)
            {
                $(this).attr('checked', if_check);
            }
        });
        $('.checkall_s').attr('checked', if_check);
    });
    $('#skip_off').click(function(){
        url = location.href.replace(/&skip_off=\d*/g,'');
        window.location.href = url + '&skip_off=' + ($('#skip_off').attr('checked') ? '1' : '0');
    });
});
</script> 

