<?php defined('InSystem') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>帮助说明</h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo '帮助说明';?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>

  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title"><h5><?php echo $lang['nc_prompts'];?></h5><span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td>
        <ul>
            <li>可对帮助内容进行编辑</li>
          </ul></td>
      </tr>
    </tbody>
  </table>
    <table class="table tb-type2 nobdb">
      <thead>
        <tr class="thead">
          <th>帮助内容</th>
          <th class="align-center">更新时间</th>
          <th class="align-center"><?php echo $lang['nc_handle'];?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['help_list']) && is_array($output['help_list'])){ ?>
        <?php foreach($output['help_list'] as $key => $val){ ?>
        <tr class="hover">
          <td><?php echo $val['help_title'];?></td>
          <td class="w150 align-center"><?php echo date('Y-m-d H:i:s',$val['update_time']);?></td>
          <td class="w150 align-center"><a href="index.php?act=help_store&op=edit_help&help_id=<?php echo $val['help_id'];?>"><?php echo $lang['nc_edit'];?></a>
         </td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr class="no_data">
          <td colspan="15"><?php echo $lang['nc_no_record'];?></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <?php if(!empty($output['help_list']) && is_array($output['help_list'])){ ?>
        <tr class="tfoot">
          <td colspan="16">
            <div class="pagination"> <?php echo $output['show_page'];?> </div></td>
        </tr>
        <?php } ?>
      </tfoot>
    </table>
</div>
<script type="text/javascript">
$(function(){
    $('#ncsubmit').click(function(){
    	$('#formSearch').submit();
    });
});
</script>