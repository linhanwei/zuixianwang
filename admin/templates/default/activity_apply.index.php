<?php defined('InSystem') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>活动报表 [<a href="JavaScript:history.go(-1);" class="current"><span>返回</span></a>]</h3>

    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch" id="formSearch">
  <input type="hidden" value="activity_project" name="act">
  <input type="hidden" value="apply_list" name="op">
      <input type="hidden" value="<?php echo $_GET['project_id'];?>" name="project_id">
  <table class="tb-type1 noborder search">
  <tbody>
    <tr>
      <th><label for="project_name">会员</label></th>
      <td><input type="text" value="<?php echo $_GET['member_name'];?>" name="member_name" id="member_name" class="txt"></td>
        <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
        </td>
    </tr></tbody>
  </table>
  </form>
   <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title">
            <h5><?php echo $lang['nc_prompts'];?></h5>
            <span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td><ul>
            <li>活动报名列表</li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <form method="post" id="project_form">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
            <th>SN</th>
            <th >会员</th>
            <th>活动名称</th>
          <th>价格</th>

            <th >内容</th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['apply_list']) && is_array($output['apply_list'])){ ?>
        <?php foreach($output['apply_list'] as $k => $v){ ?>
        <tr class="hover edit">
            <td>
                <?php echo $v['sn'];?>
            </td>
            <td ><?php echo $v['member_name'];?></td>
            <td>
                <?php echo $v['project_name'];?>
            </td>
          <td><?php echo $v['project_price'];?></td>

            <td ><?php echo htmlspecialchars_decode($v['apply_remark']);?></td>

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
          <td></td>
          <td colspan="16">
            <div class="pagination"><?php echo $output['page'];?></div></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script>
$(function(){
    $('#ncsubmit').click(function(){
        $('#formSearch').submit();
    });
});
</script>
