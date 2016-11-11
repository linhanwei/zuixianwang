<?php defined('InSystem') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
  <a href="javascript:void(0)" class="ncsc-btn ncsc-btn-green" nc_type="dialog" dialog_title="新建分类" dialog_id="my_category_add" dialog_width="480" uri="<?php echo urlShop('store_appointment_class', 'index', array('type' => 'ok'));?>" title="">新增分类</a></div>
<table class="ncsc-default-table" id="my_category" server="index.php?act=store_appointment_class&op=appointment_ajax" >
  <thead>
    <tr nc_type="table_header">
      <th class="w30"></th>
      <th coltype="editable" column="sac_name" checker="check_required" inputwidth="50%">分类名称（标红为预算分类）</th>
      <th class="w60" coltype="editable" column="sac_sort" checker="check_max" inputwidth="30px"><?php echo $lang['appointment_class_sort'];?></th>
      <th class="w120" coltype="switchable" column="sac_state" checker="" onclass="showclass-ico-btn" offclass="noshowclass-ico-btn">是否提供选择</th>
      <th class="w100"><?php echo $lang['nc_handle'];?></th>
    </tr>
    <?php if (!empty($output['appointment_class'])) { ?>
    <tr>
      <td class="tc"><input id="all" type="checkbox" class="checkall" /></td>
      <td colspan="20"><label for="all"><?php echo $lang['nc_select_all'];?></label>
        <a href="javascript:void(0)" class="ncsc-btn-mini" nc_type="batchbutton" uri="index.php?act=store_appointment_class&op=drop_appointment_class" name="class_id" confirm="您确实要删除该分类吗?"><i class="icon-trash"></i><?php echo $lang['nc_del'];?></a></td>
    </tr>
    <?php } ?>
  </thead>
  <tbody id="treet1">
    <?php if (!empty($output['appointment_class'])) { ?>
    <?php foreach ($output['appointment_class'] as $key => $val) { ?>
    <tr class="bd-line" nc_type="table_item" idvalue="<?php echo $val['sac_id']; ?>" >
      <td class="tc"><input type="checkbox" class="checkitem" value="<?php echo $val['sac_id']; ?>" /></td>
      <td class="tl"><span class="ml5 <?php if($val['sac_is_budget'] == 1) echo 'red';?>" nc_type="editobj" ><?php echo $val['sac_name']; ?></span>
        <?php if ($val['sac_parent_id'] == 0) { ?>
        <span class="add_ico_a"> <a href="javascript:void(0)" class="ncsc-btn" nc_type="dialog" dialog_width="480" dialog_title="新增下级" dialog_id="my_category_add" uri="index.php?act=store_appointment_class&top_class_id=<?php echo $val['sac_id']; ?>&type=ok" >新增下级</a></span>
        <?php } ?></td>
      <td><span nc_type="editobj"><?php echo $val['sac_sort']; ?></span></td>
      <td><?php if ($val['sac_state'] == 1) { ?>
        <?php echo $lang['store_create_yes'];?>
        <?php } else { ?>
        <?php echo $lang['store_create_no'];?>
        <?php } ?></td>
      <td class="nscs-table-handle"><span><a href="javascript:void(0)" nc_type="dialog" dialog_width="480" dialog_title="<?php echo $lang['appointment_class_edit_class'];?>" dialog_id="my_category_edit" uri="index.php?act=store_appointment_class&class_id=<?php echo $val['sac_id']; ?>&type=ok" class="btn-blue"><i class="icon-edit"></i>
        <p><?php echo $lang['nc_edit'];?></p>
        </a></span> <span><a href="javascript:void(0)" onclick="ajax_get_confirm('确认删除分类?', 'index.php?act=store_appointment_class&op=drop_appointment_class&class_id=<?php echo $val['sac_id']; ?>');" class="btn-red"><i class="icon-trash"></i>
        <p><?php echo $lang['nc_del'];?></p>
        </a></span></td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span> </div></td>
    </tr>
    <?php } ?>
  </tbody>
  <?php if (!empty($output['appointment_class'])) { ?>
  <tfoot>
    <tr>
      <th class="tc"><input id="all2" type="checkbox" class="checkall" /></th>
      <th colspan="15"><label for="all2"><?php echo $lang['nc_select_all'];?></label>
        <a href="javascript:void(0)" class="ncsc-btn-mini" nc_type="batchbutton" uri="index.php?act=store_appointment_class&op=drop_appointment_class" name="class_id" confirm="确认删除分类?"><i class="icon-trash"></i><?php echo $lang['nc_del'];?></a></th>
    </tr>
  </tfoot>
  <?php } ?>
</table>
<style>
<!--
.collapsed{display:none;}
-->
</style>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jqtreetable.js"></script>
<script>
$(function()
{
    var map = [<?php echo $output['map']; ?>];
    var path = "<?php echo MALL_TEMPLATES_URL;?>/images/";
    if (map.length > 0)
    {
        var option = {
		openImg: path + "treetable/tv-collapsable.gif",
		shutImg: path + "treetable/tv-expandable.gif",
		leafImg: path + "treetable/tv-item.gif",
		lastOpenImg: path + "treetable/tv-collapsable-last.gif",
		lastShutImg: path + "treetable/tv-expandable-last.gif",
		lastLeafImg: path + "treetable/tv-item-last.gif",
		vertLineImg: path + "treetable/vertline.gif",
		blankImg: path + "treetable/blank.gif",
		collapse: false,
		column: 1,
		striped: false,
		highlight: false,
		state:false};
        $("#treet1").jqTreeTable(map, option);
    }
});
</script>