<?php defined('InSystem') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>电子消费券</h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span>列表</span></a></li>
          <li><a href="<?php echo urlAdmin('coupons', 'use'); ?>"><span>使用</span></a></li>
        <li><a href="<?php echo urlAdmin('coupons', 'add'); ?>"><span>新增</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="act" value="coupons" />
    <input type="hidden" name="op" value="index" />
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <th><label for="search-sn">电子券</label></th>
          <td><input class="txt" type="text" name="sn" id="search-sn" value="<?php echo $output['sn'];?>" /></td>
          <th><label for="search-batchflag">批次标识</label></th>
          <td><input class="txt" type="text" name="batchflag" id="search-batchflag" value="<?php echo $output['batchflag'];?>" /></td>
            <th><label for="search-batchflag">会员手机</label></th>
            <td><input class="txt" type="text" name="member_name" id="search-member_name" value="<?php echo $output['member_name'];?>" /></td>
            <th><label for="search-state">领取状态</label></th>
          <td>
            <select name="state" id="search-state">
              <option value="">全部</option>
              <option value="0">未使用</option>
                <option value="1">已使用</option>
            </select>
            <script>$('#search-state').val('<?php echo $output['state']; ?>');</script>
          </td>
          <td>
            <a href="javascript:document.formSearch.submit();" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
<?php if ($output['form_submit'] == 'ok'): ?>
            <a class="btns " href="<?php echo urlAdmin('coupons', 'index');?>" title="<?php echo $lang['nc_cancel_search'];?>"><span><?php echo $lang['nc_cancel_search'];?></span></a>
<?php endif; ?>
          </td>
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
            <li>平台发布电子券</li>
          </ul>
        </td>
      </tr>
    </tbody>
  </table>

<div style="text-align: right;"><a class="btns" href="index.php?<?php echo http_build_query($_GET); ?>&op=export_step1" target="_blank"><span>导出Excel</span></a></div>

  <form method="post" action="<?php echo urlAdmin('coupons', 'del_coupons_batch');?>" onsubmit="" name="form1">
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th class="w24"> </th>
          <th class=" ">电子券</th>
          <th class=" ">批次标识</th>
          <th class="w60 align-center">面额(元)</th>
          <th class="w96 align-center">创建管理员</th>
          <th class="w90 align-center">创建/领取时间</th>
            <th class="w90 align-center">有效日期</th>
          <th class="w270 align-center">领取状态</th>
          <th class="w48 align-center"><?php echo $lang['nc_handle'];?> </th>
        </tr>
      </thead>
<?php if (empty($output['card_list'])): ?>
      <tbody>
        <tr class="no_data">
          <td colspan="20"><?php echo $lang['nc_no_record'];?></td>
        </tr>
      </tbody>
<?php else: ?>
      <tbody>
<?php foreach ($output['card_list'] as $val): ?>
        <tr class="space">
          <td class="w24">
<?php if ($val['state'] == 0 && $val['member_id'] == 0): ?>
            <input type="checkbox" class="checkitem" name="ids[]" value="<?php echo $val['id']; ?>" />
<?php else: ?>
            <input type="checkbox" disabled="disabled" />
<?php endif; ?>
          </td>
          <td class=""><?php echo $val['sn']; ?></td>
          <td class=""><?php echo $val['batchflag']; ?></td>
          <td class="align-center"><?php echo $val['denomination']; ?></td>
          <td class="align-center"><?php echo $val['admin_name']; ?></td>
          <td class="align-center"><?php echo date('Y-m-d', $val['create_at']); ?></td>
            <td class="align-center"><?php echo date('Y-m-d', $val['to_date']); ?></td>
          <td class="align-center">
<?php if ($val['state'] == 1 && $val['use_at'] > 0){ ?>
            会员 <?php echo $val['member_name']; ?> 在 <?php echo date('Y-m-d', $val['use_at']); ?> 使用
<?php } else if($val['member_id']>0 && $val['state'] == 0) {?>
    会员 <?php echo $val['member_name']; ?> 在 <?php echo date('Y-m-d', $val['create_at']); ?> 领取
              <?php
}else{?>
            未用
<?php } ?>
          </td>
          <td class="align-center">
<?php if ($val['state'] == 0 && $val['member_id']==0 && $val['use_at']==0): ?>
            <a onclick="return confirm('确定删除？');" href="<?php echo urlAdmin('coupons', 'del_coupons', array('id' => $val['id'])); ?>" class="normal"><?php echo $lang['nc_del'];?></a>
<?php endif; ?>
          </td>
        </tr>
<?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td><input type="checkbox" class="checkall" id="checkallBottom"></td>
          <td colspan="16"><label for="checkallBottom"><?php echo $lang['nc_select_all']; ?></label>
            &nbsp;&nbsp;<a href="javascript:void(0);" class="btn" onclick="if ($('.checkitem:checked ').length == 0) { alert('请选择需要删除的选项！');return false;}  if(confirm('<?php echo $lang['nc_ensure_del'];?>')){document.form1.submit();}"><span><?php echo $lang['nc_del'];?></span></a>
            <div class="pagination"><?php echo $output['show_page'];?></div></td>
        </tr>
      </tfoot>
<?php endif; ?>
    </table>
  </form>
</div>

<script>

</script>
