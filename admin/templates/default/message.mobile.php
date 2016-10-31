<?php defined('InSystem') or exit('Access Invalid!');?>
<!-- -->
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['nc_message_set'];?></h3>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="post" id="form_email" name="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
       <tr class="noborder">
          <td colspan="2" class="required">选择短信平台:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          

          <label>
          <input type="radio" name="sms_plugin" value="ccp" <?php if ($output['list_setting']['sms_plugin'] == 'ccp') echo 'checked="checked"';?> />容联云
          </label>
              <label>
                  <input type="radio" name="sms_plugin" value="taobao"   <?php if ($output['list_setting']['sms_plugin'] == 'taobao') echo 'checked="checked"';?>/>106008(不用配置)
              </label>
          

          </td>
            <td class="vatop tips"><label class="field_notice">二选一</label></td>
        </tr>
        
        <tr class="noborder">
          <td colspan="2" class="required">短信服务商名称:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['list_setting']['mobile_host']?$output['list_setting']['mobile_host']:'容联云';;?>" name="mobile_host" id="mobile_host" class="txt"></td>
          <td class="vatop tips"><label class="field_notice"></label></td>
        </tr>
        <tr>
          <td colspan="2" class="required">短信平台账号:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['list_setting']['mobile_sid'];?>" name="mobile_sid" id="mobile_sid " class="txt"></td>
          <td class="vatop tips"><label class="field_notice">主账号，登陆云通讯网站后，可在"控制台-应用"中看到开发者主账号ACCOUNT SID</label></td>
        </tr>
        <tr>
          <td colspan="2" class="required">主账号Token:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['list_setting']['mobile_token'];?>" name="mobile_token" id="mobile_token" class="txt"></td>
          <td class="vatop tips"><label class="field_notice">主账号Token，登陆云通讯网站后，可在控制台-应用中看到开发者主账号AUTH TOKEN</label></td>
        </tr>
        <tr>
          <td colspan="2" class="required">Appid:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['list_setting']['mobile_appid'];?>" name="mobile_appid" id="mobile_appid" class="txt"></td>
          <td class="vatop tips"><label class="field_notice">应用Id，如果是在沙盒环境开发，请配置"控制台-应用-测试DEMO"中的APPID。如切换到生产环境，
                  请使用自己创建应用的APPID</label></td>
        </tr>

         <tr>
          <td colspan="2" class="required">短信内容签名:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['list_setting']['mobile_signature'];?>" name="mobile_signature" id="mobile_signature" class="txt"></td>
          <td class="vatop tips"><label class="field_notice">如： 盖世保</label></td>
        </tr>
        
        <tr>
          <td colspan="2" class="required">备注信息:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          <textarea id="statistics_code" class="tarea" rows="6" name="mobile_memo"><?php echo $output['list_setting']['mobile_memo'];?></textarea></td>
          <td class="vatop tips"><label class="field_notice">可选填写</label></td>
        </tr>
       
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2" ><a href="JavaScript:void(0);" class="btn" onclick="document.settingForm.submit()"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script>
$(document).ready(function(){
	$('#send_test_mobile').click(function(){
		$.ajax({
			type:'POST',
			url:'index.php',
			data:'act=message&op=email_testing&email_host='+$('#email_host').val()+'&email_port='+$('#email_port').val()+'&email_addr='+$('#email_addr').val()+'&email_id='+$('#email_id').val()+'&email_pass='+$('#email_pass').val()+'&email_test='+$('#email_test').val(),
			error:function(){
					alert('<?php echo $lang['test_email_send_fail'];?>');
				},
			success:function(html){
				alert(html.msg);
			},
			dataType:'json'
		});
	});
});
</script>