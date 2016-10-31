<?php defined('InSystem') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>奖励</h3>
      <ul class="tab-base">
      <li><a href="index.php?act=points_inviter&op=addpoints"><span><?php echo $lang['nc_manage']?></span></a></li>
      <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['admin_points_log_title'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch" id="formSearch">
    <input type="hidden" name="act" value="points_inviter">
    <input type="hidden" name="op" value="points_inviter_log">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <th><label><?php echo $lang['admin_points_membername']; ?></label></th>
          <td><input type="text" name="mname" class="txt" value='<?php echo $_GET['mname'];?>'></td><th><?php echo $lang['admin_points_addtime']; ?></th>
          <td><input type="text" id="stime" name="stime" class="txt date" value="<?php echo $_GET['stime'];?>" >
            <label>~</label>
            <input type="text" id="etime" name="etime" class="txt date" value="<?php echo $_GET['etime'];?>" ></td>
          </tr><tr><th><label><?php echo $lang['admin_points_adminname']; ?></label></th><td><input type="text" name="aname" class="txt" value='<?php echo $_GET['aname'];?>'></td>

          <th><?php echo $lang['admin_points_pointsdesc']; ?></th>
          <td><input type="text" id="description" name="description" class="txt2" value="<?php echo $_GET['description'];?>" ></td>
          <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
          
            <?php if($output['search_field_value'] != '' or $output['search_sort'] != ''){?>
            <a href="index.php?act=member&op=member" class="btns "><span><?php echo $lang['nc_cancel_search']?></span></a>
            <?php }?></td>
        </tr>
      </tbody>
    </table>
  </form><div style="text-align:right;"><a class="btns" href="javascript:void(0);" id="ncexport"><span><?php echo $lang['nc_export'];?>Excel</span></a></div>
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title"><h5><?php echo $lang['nc_prompts'];?></h5><span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td>
        <ul>
            <li><?php echo $lang['admin_points_log_help1'];?></li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <table class="table tb-type2">
    <thead>
      <tr class="thead">
        <th><?php echo $lang['admin_points_membername']; ?></th>
        <th><?php echo $lang['admin_points_adminname']; ?></th>
        <th class="align-center"><?php echo $lang['admin_points_pointsnum']; ?></th>
        <th class="align-center"><?php echo $lang['admin_points_addtime']; ?></th>
        <th class="align-center"><?php echo $lang['admin_points_stage']; ?></th>
          <th>对应单号</th>
        <th><?php echo $lang['admin_points_pointsdesc']; ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($output['list_log']) && is_array($output['list_log'])){ ?>
      <?php foreach($output['list_log'] as $k => $v){?>
      <tr class="hover">
        <td><?php echo $v['pl_membername'];?></td>
        <td><?php echo $v['pl_adminname'];?></td>
        <td class="align-center"><?php echo $v['pl_points'];?></td>
        <td class="nowrap align-center"><?php echo @date('Y-m-d',$v['pl_addtime']);?></td>
        <td class="align-center"><?php
				switch ($v['pl_stage']){
                    case 'inviter':
                        echo '佣金';
                        break;
                    case 'system':
                        echo '系统操作';
                        break;
                    case 'distill':
                        echo '提取积分';
                        break;
	          }?></td>
          <td><?php echo $v['pl_sn'];?></td>
        <td><?php echo $v['pl_desc'];?></td>

      </tr>
      <?php } ?>
      <?php }else { ?>
      <tr class="no_data">
        <td colspan="15"><?php echo $lang['nc_no_record'];?></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <tr class="tfoot">
        <td colspan="15"><div class="pagination"> <?php echo $output['show_page'];?> </div></td>
      </tr>
    </tfoot>
  </table>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script language="javascript">
$(function(){
	$('#stime').datepicker({dateFormat: 'yy-mm-dd'});
	$('#etime').datepicker({dateFormat: 'yy-mm-dd'});
    $('#ncexport').click(function(){
    	$('input[name="op"]').val('export_step1');
    	$('#formSearch').submit();
    });
    $('#ncsubmit').click(function(){
    	$('input[name="op"]').val('points_inviter_log');$('#formSearch').submit();
    });
});
</script>
