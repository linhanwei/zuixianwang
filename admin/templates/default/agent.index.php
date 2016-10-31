<?php defined('InSystem') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>代理</h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span>代理商管理</span></a></li>
          <li><a href="index.php?act=agent&op=agent_add" ><span>添加代理商</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch" id="formSearch">
  <input type="hidden" value="agent" name="act">
  <input type="hidden" value="agent" name="op">
  <table class="tb-type1 noborder search">
  <tbody>
    <tr><th><label for="owner_and_name">代理登录名:</label></th>
      <td><input type="text" value="<?php echo $_GET['agent_name'];?>" name="agent_name" id="agent_name" class="txt"></td><td></td><th><label>区域</label></th>
      <td><input type="text" value="<?php echo $_GET['area_name'];?>" name="area_name" id="area_name" class="txt"></td>
        <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
        <?php if( $output['grade_id'] != ''){?>
        <a href="#" class="btns " title="<?php echo $lang['nc_cancel_search'];?>"><span><?php echo $lang['nc_cancel_search'];?></span></a>
        <?php }?></td>
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
            <li>查看修改代理商,如需要编辑请删除重新添加</li><li>代理商可通过代理后台查询对应数据</li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <form method="post" id="store_form">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th>登录名</th>
            <th>公司名称</th>
            <th>代理地区</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['agent_list']) && is_array($output['agent_list'])){ ?>
        <?php foreach($output['agent_list'] as $k => $v){ ?>
        <tr class="hover edit">
            <td><?php echo $v['agent_name'];?></td>
            <td><?php echo $v['company_name'];?></td>
            <td><?php echo $v['area_info'];?></td>
        <td class="align-center w200">
            <a href="javascript:void(0)" onclick="if(confirm('确定删除代理商?')){location.href='index.php?act=agent&op=agent_del&agent_id=<?php echo $v['agent_id']; ?>'}">删除</a>
            | <a href="index.php?act=agent&op=agent_edit&agent_id=<?php echo $v['agent_id']; ?>">编辑</a>
            | <a href="index.php?act=agent&op=agent_member&agent_id=<?php echo $v['agent_id']; ?>">关联帐号</a>

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
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script>
$(function(){
    $('#ncsubmit').click(function(){
    	$('input[name="op"]').val('agent');$('#formSearch').submit();
    });
});
</script>
