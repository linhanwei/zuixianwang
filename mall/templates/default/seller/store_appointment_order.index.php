<?php defined('InSystem') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<form method="get" action="index.php" target="_self">
  <table class="search-form">
    <input type="hidden" name="act" value="store_appointment" />
    <input type="hidden" name="op" value="index" />
    <?php if ($_GET['state_type']) { ?>
    <input type="hidden" name="state_type" value="<?php echo $_GET['state_type']; ?>" />
    <?php } ?>
    <tr>
      <th>申请预约时间</th>
      <td class="w240"><input type="text" class="text w70" name="query_start_date" id="query_start_date" value="<?php echo $_GET['query_start_date']; ?>" /><label class="add-on"><i class="icon-calendar"></i></label>&nbsp;&#8211;&nbsp;<input id="query_end_date" class="text w70" type="text" name="query_end_date" value="<?php echo $_GET['query_end_date']; ?>" /><label class="add-on"><i class="icon-calendar"></i></label></td>
      <th>预约单号</th>
      <td class="w160"><input type="text" class="text w150" name="order_id" value="<?php echo $_GET['order_id']; ?>" /></td>
      <td class="w70 tc"><label class="submit-border">
          <input type="submit" class="submit" value="搜索" />
        </label></td>
    </tr>
  </table>
</form>
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
      <th class="w150">操作</th>
    </tr>
  </thead>
  <?php if (is_array($output['order_list']) and !empty($output['order_list'])) { ?>
  
  <tbody>
  <?php foreach($output['order_list'] as $order_id => $order) { ?>
    <tr>
      <td ><?php echo $order['order_id']; ?></td>
      <td><?php echo $order['name']; ?></td>
      <td><?php echo $order['phone']; ?></td>
      <td><?php echo $order['time']; ?></td>
      <td><?php echo $order['type'] . '-' . $order['type_']; ?></td>
      <td><?php echo $order['budget']; ?></td>
      <td><?php echo $order['state_desc']; ?></td>
      <td><?php echo $order['member_name']; ?></td>
      <td> 
        <?php if ($order['state'] == 1) { ?>
       <a href="javascript:void(0)" class="ncsc-btn-mini ncsc-btn-green" uri="index.php?act=store_appointment&op=change_state&state=2&order_id=<?php echo $order['order_id']; ?>&order_id=<?php echo $order['order_id']; ?>" dialog_width="330" dialog_title="马上洽谈" nc_type="dialog"  dialog_id="seller_order_state_2" id="order<?php echo $order['order_id']; ?>_action_state_2" />马上洽谈</a>
        <?php } ?>
        <?php if ($order['state'] == 2) { ?>
       <a href="javascript:void(0)" class="ncsc-btn-mini ncsc-btn-green" uri="index.php?act=store_appointment&op=change_state&state=4&order_id=<?php echo $order['order_id']; ?>&order_id=<?php echo $order['order_id']; ?>" dialog_width="330" dialog_title="洽谈成功" nc_type="dialog"  dialog_id="seller_order_state_4" id="order<?php echo $order['order_id']; ?>_action_state_4" />洽谈成功</a>
       <a href="javascript:void(0)" class="ncsc-btn-mini ncsc-btn-red" uri="index.php?act=store_appointment&op=change_state&state=3&order_id=<?php echo $order['order_id']; ?>&order_id=<?php echo $order['order_id']; ?>" dialog_width="330" dialog_title="洽谈成败" nc_type="dialog"  dialog_id="seller_order_state_3" id="order<?php echo $order['order_id']; ?>_action_state_3" />洽谈失败</a>
        <?php } ?>
        <?php if ($order['state'] == 4) { ?>
        
         <a href="javascript:void(0)" class="ncsc-btn-mini ncsc-btn-green" uri="index.php?act=store_appointment&op=change_state&state=sn&order_id=<?php echo $order['order_id']; ?>&order_id=<?php echo $order['order_id']; ?>&goods_order_id=<?php echo $order['goods_order_id']; ?>" dialog_width="330" dialog_title="确认删除预约单号" nc_type="dialog"  dialog_id="seller_order_state_sn" id="order<?php echo $order['order_id']; ?>_action_state_sn" />设置/查看订单号</a>
       <?php }if ($order['state'] == 3 || $order['state'] == 3) { ?>
       <a href="javascript:void(0)" class="ncsc-btn-mini ncsc-btn-red" uri="index.php?act=store_appointment&op=change_state&state=5&order_id=<?php echo $order['order_id']; ?>&order_id=<?php echo $order['order_id']; ?>" dialog_width="330" dialog_title="确认删除预约单号" nc_type="dialog"  dialog_id="seller_order_state_5" id="order<?php echo $order['order_id']; ?>_action_state_5" />删除</a>
        <?php } ?>
        </td>
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
