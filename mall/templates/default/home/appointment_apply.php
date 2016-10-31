<div class="eject_con">
  <div id="warning" class="alert alert-error"></div>
  <form id="apply_form" method="post" target="_parent" action="index.php?act=appointment&op=apply&store_id=<?php echo $_GET['store_id'];?>">
    <input type="hidden" name="form_submit" value="ok" />
     <input type="hidden" name="type" id="type" value=""/>
     <input type="hidden" name="type_" id="type_" value=""/>
      <input type="hidden" name="budget" id="budget" value=""/>
    <dl>
      <dt><i class="required">*</i><?php echo '联系人'.$lang['nc_colon'];?></dt>
      <dd>
        <input class="text w200" type="text" name="name" id="name" />
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i><?php echo '预约日期'.$lang['nc_colon'];?></dt>
      <dd>
        <input type="text" class="text w70" name="time" id="time" value="" /><label class="add-on"><i class="icon-calendar"></i></label>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i><?php echo '手机'.$lang['nc_colon'];?></dt>
      <dd>
        <input class="text w200" type="number" name="mobile" id="mobile" />
      </dd>
    </dl>
    <div class="bottom">
        <label class="submit-border"><input type="submit" class="submit" value="提交" /></label>
    </div>
  </form>
</div>
<script type="text/javascript">
$(function(){
	$("#type").val($("#q_cate_id").text());
	$("#type_").val($("#n_one").text());
	$("#budget").val($("#n_two").text());
    $('#apply_form').validate({
        errorLabelContainer: $('#warning'),
        invalidHandler: function(form, validator) {
               $('#warning').show();
        },
    	submitHandler:function(form){
    		ajaxpost('apply_form', '', '', 'onerror') 
    	},
        rules : {
    		name : {
                required : true
            },
            time : {
            	required   : true
            },
            mobile : {
            	required   : true,
            	minlength : 11,
            	isMobile : true
            }
        },
        messages : {
            name : {
                required : '<i class="icon-exclamation-sign"></i>联系人不能为空'

            },
            time  : {
            	required   : '<i class="icon-exclamation-sign"></i>需要输入日期'
            },
            mobile : {
            	required   : '<i class="icon-exclamation-sign"></i>手机号码不能为空',
            	minlength  : '<i class="icon-exclamation-sign"></i>请输入正确的手机号码'
            }
        }
    });

    $('#time').datepicker({dateFormat: 'yy-mm-dd'});
});
</script> 
