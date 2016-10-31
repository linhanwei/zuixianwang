<?php defined('InSystem') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>油卡购买详细</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=oil&op=card_list" ><span><?php echo $lang['nc_manage']?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_edit'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
    <table class="table tb-type2" id="prompt">
        <tbody>
        <tr class="space odd">
            <th colspan="12"><div class="title">
                    <h5><?php echo $lang['nc_prompts'];?></h5>
                    <span class="arrow"></span></div></th>
        </tr>
        <tr>
            <td><ul>
                    <li>请确认所有信息正确无误</li>
                </ul></td>
        </tr>
        </tbody>
    </table>
  <form id="user_form" enctype="multipart/form-data" method="post">
    <input type="hidden" name="form_submit" value="ok" />
      <input type="hidden" name="oc_id" value="<?php echo $output['oil_array']['oc_id'];?>" />
      <input type="hidden" name="oc_type" value="<?php echo $output['oil_array']['oc_type'];?>" />
    <input type="hidden" name="oc_member_id" value="<?php echo $output['oil_array']['oc_member_id'];?>" />
    <input type="hidden" name="oc_member_name" value="<?php echo $output['oil_array']['oc_member_name'];?>" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label>会员:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><?php echo $output['oil_array']['oc_member_name'];?></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
            <td colspan="2" class="required"><label>卡类型:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform"><img src="templates/default/images/oil_<?php echo $output['oil_array']['oc_type'];?>.png" width="250" height="150"/></td>
            <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="oc_payment_name">支付方式:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><?php echo $output['oil_array']['oc_payment_name']?></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
            <td colspan="2" class="required"><label for="oc_payment_state">支付状态:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform"><?php echo $output['oil_array']['oc_payment_state'] == 1?'已支付':'未支付';?></td>
            <td class="vatop tips"></td>
        </tr>
        <tr>
            <td colspan="2" class="required"><label for="oc_trade_sn">第三方单号:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform"><?php echo $output['oil_array']['oc_trade_sn'];?></td>
            <td class="vatop tips">00000000为现金或现场派发</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="oc_payment_time">支付时间:</label></td>
        </tr>
        <tr class="noborder" >
          <td class="vatop rowform"><?php echo date('Y-m-d',$output['oil_array']['oc_payment_time']);?></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
            <td colspan="2"><label class="validation" for="oc_add_time">添加时间:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform"><?php echo date('Y-m-d',$output['oil_array']['oc_add_time']);?></td>
            <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="oc_state">油卡状态:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
              <?php
                switch($output['oil_array']['oc_state']){
                    case 1:
                        echo '申请中,待绑定';
                        break;
                    case 2:
                        echo '已绑定';
                        break;
                    case 3:
                        echo '驳回修改';
                        break;
                }
              ?>
          </td>
          <td class="vatop tips"></td>
        </tr>
        <?php if($output['oil_array']['oc_state'] == 1 || $output['oil_array']['oc_state'] == 2){?>
        <tr>
            <td colspan="2">------------油卡信息------------</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>姓名:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
                  <?php echo $output['oil_array']['oc_idcard_name'];?>
            </td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
            <td colspan="2" class="required"><label>手机:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform">
                <?php echo $output['oil_array']['oc_mobile'];?>
            </td>
            <td class="vatop tips"></td>
        </tr>
        <tr style="display: none;">
          <td colspan="2" class="required"><label>身份证号:</label></td>
        </tr>
        <tr class="noborder"style="display: none;">
          <td class="vatop rowform"> <?php echo $output['oil_array']['oc_idcard_number'];?></td>
          <td class="vatop tips"></td>
        </tr>
        <tr >
            <td colspan="2" class="required"><label>地址:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform"> <?php echo $output['oil_array']['oc_address'];?></td>
            <td class="vatop tips"></td>
        </tr>
        <?php if($output['oil_array']['oc_idcard_front'] && $output['oil_array']['oc_idcard_back']){?>
        <tr >
            <td colspan="2" class="required"><label>身份证正面:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
             <a href="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_IDCARD; ?>/<?php echo $output['oil_array']['oc_idcard_front'];?>" target="_blank"><img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_IDCARD; ?>/<?php echo $output['oil_array']['oc_idcard_front'];?>" width="100" height="100" id="view_img"></a>
          </td>
          <td class="vatop tips">点击查看大图</td>
        </tr> <tr >
            <td colspan="2" class="required"><label>身份证背面:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform">
                <a href="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_IDCARD; ?>/<?php echo $output['oil_array']['oc_idcard_back'];?>" target="_blank"><img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_IDCARD; ?>/<?php echo $output['oil_array']['oc_idcard_back'];?>" width="100" height="100" id="view_img"></a>
            </td>
            <td class="vatop tips">点击查看大图</td>
        </tr>
        <?php }?>

            <?php if($output['oil_array']['oc_driving_licence']){?>
                <tr >
                    <td colspan="2" class="required"><label>行驶证:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform">
                        <a href="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_IDCARD; ?>/<?php echo $output['oil_array']['oc_driving_licence'];?>" target="_blank"><img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_IDCARD; ?>/<?php echo $output['oil_array']['oc_driving_licence'];?>" width="100" height="100" id="view_img"></a>
                    </td>
                    <td class="vatop tips">点击查看大图</td>
                </tr>
            <?php }?>
        <tr >
            <td colspan="2" class="required"><label>绑定油卡:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform">
                <?php if($output['oil_array']['oc_card_number'] == ''){?>
                    <input type="text" id="card_number" name="card_number" class="txt">
                <?php }else{
                     echo $output['oil_array']['oc_card_number'];
                }?>
            </td>
            <td class="vatop tips"></td>
        </tr>
        <?php }?>
        <tr >
            <td colspan="2" class="required"><label>备注:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform">
                <?php if($output['oil_array']['remark'] == ''){?>
                    <input type="text" id="remark" name="remark" class="txt">
                <?php }else{
                    echo $output['oil_array']['remark'];
                }?>
            </td>
            <td class="vatop tips">请填写相关的信息</td>
        </tr>
      </tbody>

      <tfoot>
        <tr class="tfoot"><td colspan="15">
            <?php if($output['oil_array']['oc_card_number'] == '' || $output['oil_array']['remark'] == ''){?>
                <a href="JavaScript:void(0);" class="btn" id="submitBtn"><span><?php echo $lang['nc_submit'];?></span></a>
            <?php }?>
                <?php if($output['oil_array']['oc_state'] == 1){?>
                <a href="JavaScript:void(0);" class="btn" id="submitBack" style="display: none;"><span>驳回</span></a>
                <?php }?>
            </td>
        </tr>
      </tfoot>

    </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ajaxfileupload/ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.js"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.min.css" rel="stylesheet" type="text/css" id="cssfile2" />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script>

<script type="text/javascript">
//裁剪图片后返回接收函数
function call_back(picname){
	$('#member_avatar').val(picname);
	$('#view_img').attr('src','<?php echo UPLOAD_SITE_URL.'/'.ATTACH_AVATAR;?>/'+picname+'?'+Math.random());
}
$(function(){
    regionInit("region");
    regionAgentInit("region_agent");
	$('input[class="type-file-file"]').change(uploadChange);
	function uploadChange(){
		var filepatd=$(this).val();
		var extStart=filepatd.lastIndexOf(".");
		var ext=filepatd.substring(extStart,filepatd.lengtd).toUpperCase();		
		if(ext!=".PNG"&&ext!=".GIF"&&ext!=".JPG"&&ext!=".JPEG"){
			alert("file type error");
			$(this).attr('value','');
			return false;
		}
		if ($(this).val() == '') return false;
		ajaxFileUpload();
	}
	function ajaxFileUpload()
	{
		$.ajaxFileUpload
		(
			{
				url:'index.php?act=common&op=pic_upload&form_submit=ok&uploadpath=<?php echo ATTACH_AVATAR;?>',
				secureuri:false,
				fileElementId:'_pic',
				dataType: 'json',
				success: function (data, status)
				{
					if (data.status == 1){
						ajax_form('cutpic','<?php echo $lang['nc_cut'];?>','index.php?act=common&op=pic_cut&type=member&x=120&y=120&resize=1&ratio=1&filename=<?php echo UPLOAD_SITE_URL.'/'.ATTACH_AVATAR;?>/avatar_<?php echo $_GET['member_id'];?>.jpg&url='+data.url,690);
					}else{
						alert(data.msg);
					}
					$('input[class="type-file-file"]').bind('change',uploadChange);
				},
				error: function (data, status, e)
				{
					alert('上传失败');$('input[class="type-file-file"]').bind('change',uploadChange);
				}
			}
		)
	};
$("#submitBtn").click(function(){
    if($("#user_form").valid()){
     $("#user_form").submit();
	}
	});
    $('#user_form').validate({
        errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },
        rules : {
            member_passwd: {
                maxlength: 20,
                minlength: 6
            }
        },
        messages : {
            member_passwd : {
                maxlength: '<?php echo $lang['member_edit_password_tip']?>',
                minlength: '<?php echo $lang['member_edit_password_tip']?>'
            }
        }
    });
});
</script> 
