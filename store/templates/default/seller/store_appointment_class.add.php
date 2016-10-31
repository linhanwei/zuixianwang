<div class="eject_con">
  <div id="warning" class="alert alert-error"></div>
  <form id="category_form" method="post" target="_parent" action="index.php?act=store_appointment_class&op=appointment_save">
    <?php if ($output['class_info']['sac_id']!=0) { ?>
    <input type="hidden" name="sac_id" value="<?php echo $output['class_info']['sac_id']; ?>" />
    <?php } ?>
    <dl>
      <dt><i class="required">*</i><?php echo '分类名称'.$lang['nc_colon'];?></dt>
      <dd>
        <input class="text w200" type="text" name="sac_name" id="sac_name" value="<?php echo $output['class_info']['sac_name']; ?>" />
      </dd>
    </dl>
    <?php if ($output['class_info']['sac_id']==0) { ?>
    <dl>
      <dt><?php echo '上级分类'.$lang['nc_colon'];?></dt>
      <dd>
        <select name="sac_parent_id" id="sac_parent_id">
          <option>请选择</option>
          <?php if(!empty($output['appointment_class']) && is_array($output['appointment_class'])){ ?>
          <?php foreach ($output['appointment_class'] as $val) { ?>
          <option value="<?php echo $val['sac_id']; ?>" <?php if ($val['sac_id'] == $output['class_info']['sac_parent_id']) { ?>selected="selected"<?php } ?>><?php echo $val['sac_name']; ?></option>
          <?php } ?>
          <?php } ?>
        </select>
      </dd>
    </dl>
    <?php } ?>
    <dl>
      <dt><?php echo '排序'.$lang['nc_colon'];?></dt>
      <dd>
        <input class="text w60" type="text" name="sac_sort" value="<?php echo intval($output['class_info']['sac_sort']); ?>"  />
      </dd>
    </dl>
    <dl>
      <dt><?php echo '作为预算'.$lang['nc_colon'];?></dt>
      <dd>
        <label>
          <input type="checkbox" name="sac_is_budget" value="1" <?php if($output['class_info']['sac_is_budget'] == 1) echo 'checked';?>></label>
      </dd>
    </dl>
    <dl>
      <dt><?php echo '是否提供选择'.$lang['nc_colon'];?></dt>
      <dd>
        <label>
          <input type="radio" name="sac_state" value="1" <?php if ($_GET['class_id']=='' or $output['class_info']['sac_state']==1) echo 'checked="checked"'; ?> />
          是</label>
        <label>
          <input type="radio" name="sac_state" value="0" <?php if ($output['class_info']['sac_state']==0 and $_GET['class_id']!='') echo 'checked="checked"'; ?> />
         否</label>
      </dd>
    </dl>
    <div class="bottom">
        <label class="submit-border"><input type="submit" class="submit" value="提交" /></label>
    </div>
  </form>
</div>
<script type="text/javascript">
$(function(){
    $('#category_form').validate({
        errorLabelContainer: $('#warning'),
        invalidHandler: function(form, validator) {
               $('#warning').show();
        },
    	submitHandler:function(form){
    		ajaxpost('category_form', '', '', 'onerror') 
    	},
        rules : {
            sac_name : {
                required : true
            },
            sac_sort : {
                number   : true
            }
        },
        messages : {
            sac_name : {
                required : '<i class="icon-exclamation-sign"></i>分类名称不能为空'

            },
            sac_sort  : {
                number   : '<i class="icon-exclamation-sign"></i>需要输入数字'
            }
        }
    });
});
</script> 
