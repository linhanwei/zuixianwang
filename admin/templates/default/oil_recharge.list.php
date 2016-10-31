<?php defined('InSystem') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>油卡充值管理</h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span>充值列表</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" action="index.php" name="formSearch" id="formSearch">
    <input type="hidden" name="act" value="oil">
    <input type="hidden" name="op" value="recharge">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <th>会员手机号 </th>
          <td><input type="text" name="mname" class="txt" value='<?php echo $_GET['mname'];?>'></td>
          <th>日期区间 </th>
          <td><input type="text" id="query_start_date" name="query_start_date" class="txt date" value="<?php echo $_GET['query_start_date'];?>" >
            <label>~</label>
            <input type="text" id="query_end_date" name="query_end_date" class="txt date" value="<?php echo $_GET['query_start_date'];?>" ></td>
          <td>
              <select id="restate_search" name="restate_search">
                  <option value="">充值状态</option>
                  <option value="0" <?php if($_GET['restate_search'] == '0' ) { ?>selected="selected"<?php } ?>>未充值</option>
                  <option value="1" <?php if($_GET['restate_search'] == '1' ) { ?>selected="selected"<?php } ?>>已充值</option>
              </select>
            <select id="paystate_search" name="paystate_search">
              <option value="">支付状态</option>
              <option value="0" <?php if($_GET['paystate_search'] == '0' ) { ?>selected="selected"<?php } ?>>未支付</option>
              <option value="1" <?php if($_GET['paystate_search'] == '1' ) { ?>selected="selected"<?php } ?>>已支付</option>
            </select>
            <a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a></td>
        </tr>
      </tbody>
    </table>
  </form>
    <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title"><h5><?php echo $lang['nc_prompts'];?></h5><span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td>
        <ul>
            <li>请确认支付成功后才充值</li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <div style="text-align:right;display: none;"><a class="btns" target="_blank" href="index.php?<?php echo $_SERVER['QUERY_STRING'];?>&op=export_step1"><span><?php echo $lang['nc_export'];?>Excel</span></a></div>
  <table class="table tb-type2">
    <thead>
      <tr class="thead">
          <th>充值单号</th>
        <th>会员</th>
          <th>油卡号码</th>
        <th>充值状态</th>
        <th class="align-center">添加时间</th>
        <th class="align-center">支付时间</th>
        <th class="align-center">支付方式</th>
        <th class="align-center">支付金额(<?php echo $lang['currency_zh']; ?>)</th>
        <th class="align-center">支付状态</th>
        <th class="align-center"><?php echo $lang['nc_handle']; ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
      <?php foreach($output['list'] as $k => $v){?>
      <tr class="hover">
        <td><?php echo $v['ol_sn']; ?></td>
        <td><?php echo $v['ol_member_name']; ?></td>
          <td><?php echo $v['oil']['oc_card_number']; ?></td>
          <td><?php
                  if($v['ol_state']==2){
                      echo '充值成功(<span style="color:#FF0000;">' . $v['ol_trade_code'] . '</span>)';
                  }else{
                      echo '等待充值';
                  }
                  ?></td>
        <td class="nowarp align-center"><?php echo @date('Y-m-d H:i:s',$v['ol_add_time']);?></td>
        <td class="nowarp align-center">
        <?php if (intval($v['ol_payment_time'])) {?>
            <?php if (date('His',$v['ol_payment_time']) == 0) {?>
            <?php echo date('Y-m-d',$v['ol_payment_time']);?>
            <?php } else { ?>
            <?php echo date('Y-m-d H:i:s',$v['ol_payment_time']);?>
            <?php } ?>
        <?php } ?>
        </td>
        <td class="align-center"><?php echo $v['ol_payment_name'];?></td>
        <td class="align-center"><?php echo $v['ol_amount'];?></td>
        <td class="align-center"><?php echo str_replace(array('0','1'),array('未支付','已支付'),$v['ol_payment_state']);?></td>
        <td class="w90 align-center">
          <?php if (!intval($v['ol_payment_state'])){?>
          <a href="JavaScript:void(0);" onclick="if(confirm('<?php echo $lang['nc_ensure_del'];?>')){window.location ='index.php?act=oil&op=recharge_del&id=<?php echo $v['ol_id']; ?>'}"><span><?php echo $lang['nc_del'];?></span></a>
          <?php } ?>
          <a href="index.php?act=oil&op=recharge_info&id=<?php echo $v['ol_id']; ?>" class="edit"><?php echo $lang['nc_view']; ?></a>
          </td>
      </tr>
      <?php } ?>
      <?php }else { ?>
      <tr class="no_data">
        <td colspan="10"><?php echo $lang['nc_no_record'];?></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <tr class="tfoot">
        <td colspan="16"><div class="pagination"> <?php echo $output['show_page'];?> </div></td>
      </tr>
    </tfoot>
  </table>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script language="javascript">
$(function(){
	$('#query_start_date').datepicker({dateFormat: 'yy-mm-dd'});
	$('#query_end_date').datepicker({dateFormat: 'yy-mm-dd'});
    $('#ncsubmit').click(function(){
    	$('input[name="op"]').val('recharge');$('#formSearch').submit();
    });	
});
</script>