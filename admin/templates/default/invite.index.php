<?php defined('InSystem') or exit('Access Invalid!');?>
<?php  $member_info = $output['member_info'];?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>推荐人统计</h3>

    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch" id="formSearch">
    <input type="hidden" value="invite" name="act">
    <input type="hidden" value="index" name="op">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <td>会员：</td>
          <td><input type="text" value="<?php echo $_GET['member_name'];?>" name="member_name" class="txt"></td>
            <th><label><?php echo $lang['admin_predeposit_maketime']; ?></label></th>
            <td style="display: none;"><input type="text" id="stime" name="stime" class="txt date" value="<?php echo $_GET['stime'];?>" >
                <label>~</label>
                <input type="text" id="etime" name="etime" class="txt date" value="<?php echo $_GET['etime'];?>" ></td>

            <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
            <?php if($output['search_field_value'] != '' or $output['search_sort'] != ''){?>
            <a href="index.php?act=member&op=member" class="btns "><span><?php echo $lang['nc_cancel_search']?></span></a>
            <?php }?></td>
            <td>
                <?php if($member_info['inviter_member']){?>
                <a href="index.php?act=invite&op=index&member_name=<?php echo $member_info['inviter_member']['member_name']; ?>">返回上级</a>
            <?php }?>
            </td>
        </tr>
      </tbody>
    </table>
  </form>
    <?php if($output['member_info']){
       ?>
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title">
            <h5>统计数据</h5>
            <span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td><ul>
            <li>会员：<?php echo $member_info['member_name'];?>&nbsp;&nbsp; 姓名：<?php echo $member_info['member_truename'];?>&nbsp;&nbsp; 获得奖励：<?php echo ncPriceFormat($member_info['invite_amount']);?>元</li>
                <li>邀请人数：<?php echo $member_info['invite_count'];?>人&nbsp;&nbsp; 其中商户：<?php echo $member_info['store_count'];?>人&nbsp;&nbsp;VIP会员：<?php echo $member_info['vip_count'];?>人&nbsp;&nbsp;普通会员：<?php echo $member_info['invite_count'] - $member_info['vip_count'];?>人&nbsp;&nbsp;</li>
          </ul></td>
      </tr>
    </tbody>
  </table>
    <?php }?>
  <form method="post" id="form_member">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2 nobdb">
      <thead>
        <tr class="thead">
          <th>&nbsp;</th>
          <th colspan="2"><?php echo $lang['member_index_name']?></th>
            <th class="align-center">姓名</th>
          <th class="align-center">级别</th>
            <th class="align-center">升级时间</th>
          <th class="align-center">邀请人数</th>
          <th class="align-center">获得奖励</th>
          <th class="align-center"><?php echo $lang['nc_handle']; ?></th>
        </tr>
      <tbody>
        <?php if(!empty($output['member_list']) && is_array($output['member_list'])){ ?>
        <?php foreach($output['member_list'] as $k => $v){ ?>
        <tr class="hover member">
          <td class="w24"><input type="checkbox" name='del_id[]' value="<?php echo $v['member_id']; ?>" class="checkitem"></td>
          <td class="w48 picture"><div class="size-44x44"><span class="thumb size-44x44"><i></i><img src="<?php if ($v['member_avatar'] != ''){ echo $v['member_avatar'];}else { echo UPLOAD_SITE_URL.'/'.ATTACH_COMMON.DS.'default_user_avatar.png';}?>?<?php echo microtime();?>"  onload="javascript:DrawImage(this,44,44);" width="44" height="44"/></span></div></td>
          <td><p class="name"><strong><?php echo $v['member_name']; ?></strong></p>
            <p class="smallfont"><?php echo $lang['member_index_reg_time']?>:&nbsp;<?php echo $v['member_time']; ?></p>
             </td>
          <td class="align-center"><?php echo $v['member_truename']; ?></td>
          <td class="w150 align-center"><p><?php echo $output['member_grade'][$v['grade_id']]['grade_name'];?></p>
           </td><td> <p><?php echo $v['upgrade_date']; ?></p></td>
            <td class="align-center"><?php echo $v['invite_count']; ?></td>
          <td class="align-center"><?php echo ncPriceFormat($v['invite_amount']); ?></td>
          <td class="align-center">
              <?php if($v['invite_count']>0){?>
              <a href="index.php?act=invite&op=index&member_name=<?php echo $v['member_name']; ?>">下级</a></td>
                <?php }?>
                </tr>
        <?php } ?>
        <?php }else { ?>
        <tr class="no_data">
          <td colspan="8"><?php echo $lang['nc_no_record']?></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot class="tfoot">
        <?php if(!empty($output['member_list']) && is_array($output['member_list'])){ ?>
        <tr>
        <td class="w24"><input type="checkbox" class="checkall" id="checkallBottom"></td>
          <td colspan="8">
          <label for="checkallBottom"><?php echo $lang['nc_select_all']; ?></label>
            &nbsp;&nbsp;
            <div class="pagination"> <?php echo $output['page'];?> </div></td>
        </tr>
        <?php } ?>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />

<script>
$(function(){
    $('#stime').datepicker({dateFormat: 'yy-mm-dd'});
    $('#etime').datepicker({dateFormat: 'yy-mm-dd'});
    $('#ncsubmit').click(function(){
    	$('input[name="op"]').val('index');$('#formSearch').submit();
    });	
});
</script>
