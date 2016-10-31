<?php defined('InSystem') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>活动管理</h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span>管理</span></a></li>
          <li><a href="index.php?act=activity_project&op=project_add&project_type=<?php echo $output['project_type'];?>" ><span>新增活动</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch" id="formSearch">
  <input type="hidden" value="activity_project" name="act">
  <input type="hidden" value="<?php echo $output['project_type'];?>" name="op">
  <table class="tb-type1 noborder search">
  <tbody>
    <tr>
      <th><label for="project_name">名称</label></th>
      <td><input type="text" value="<?php echo $output['project_name'];?>" name="project_name" id="project_name" class="txt"></td>
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
            <li>添加/修改活动内容</li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <form method="post" id="project_form">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
            <th>名称</th>
          <th>地址</th>
          <th >电话</th>
            <th>报名人数</th>
            <th >状态</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['project_list']) && is_array($output['project_list'])){ ?>
        <?php foreach($output['project_list'] as $k => $v){ ?>
        <tr class="hover edit">
            <td>
                <?php echo $v['project_name'];?>
            </td>
          <td><?php echo $v['project_address'];?></td>
          <td ><?php echo $v['project_phone'];?></td>
            <td ><?php echo $v['apply_count'];?></td>
            <td ><?php echo $v['project_state'] == '1' ? '进行中' : '过期';?></td>
        <td class="align-center w200">
            <a href="index.php?act=activity_project&op=project_edit&project_id=<?php echo $v['project_id']?>"><?php echo $lang['nc_edit'];?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
            <a href="index.php?act=activity_project&op=apply_list&project_id=<?php echo $v['project_id']?>">报表情况</a>&nbsp;&nbsp;|&nbsp;&nbsp;
            <a href="javascript:if(confirm('确认删除此活动？')) document.location.href='index.php?act=activity_project&op=del&project_id=<?php echo $v['project_id']?>&project_type=<?php echo $_GET['project_type'];?>';"><?php echo $lang['nc_del'];?></a>&nbsp;&nbsp;
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
