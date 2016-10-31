<?php defined('InSystem') or exit('Access Invalid!');?>
<style>
    .type-file-show{float:left;margin-left:10px;}
</style>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>合粉公益</h3>
      <ul class="tab-base">
        <li><a href="#"><span><?php echo $lang['nc_manage'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="fund_form" method="post" enctype="multipart/form-data" >
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="fund_id" value="<?php echo $output['fund']['fund_id'];?>" />
    <table class="table tb-type2 nobdb">
      <tbody>
        <tr>
          <td colspan="2" class="required"><label class="validation">公益标题: </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input style="width:50%;" type="text" value="<?php echo $output['fund']['fund_name'];?>" name="fund_name" id="fund_name"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
            <td colspan="2"><label >捐赠金额/人数: </label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform"><?php echo $output['fund']['fund_raise'];?>元 / <?php echo $output['fund']['fund_love'];?>人</td>
            <td class="vatop tips"></td>
        </tr>
        <tr>
            <td colspan="2"><label >接收机构: </label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform"><input style="width:50%;" type="text" value="<?php echo $output['fund']['fund_to'];?>" name="fund_to" id="fund_to" ></td>
            <td class="vatop tips"></td>
        </tr>
        <tr>
            <td colspan="2" class="required"><label for="fund_banner">Banner:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform"><span class="type-file-show"><img class="show_image" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/preview.png">
            <div class="type-file-preview"><img src="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_COMMON.DS.$output['fund']['fund_banner']);?>"></div>
            </span><span class="type-file-box"><input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
            <input name="fund_banner" type="file" class="type-file-file" id="fund_banner" size="30" hidefocus="true" nc_type="change_fund_banner">
            </span></td>
            <td class="vatop tips"><span class="vatop rowform">头部显示，最佳显示尺寸为640*240像素</span></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation">说明: </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><?php showEditor('fund_content',$output['fund']['fund_content']);?></td>
          <td class="vatop tips"></td>
        </tr>

      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="15" ><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script> 
<script>
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#fund_form").valid()){
     $("#fund_form").submit();
	}
	});
});
//
$(document).ready(function(){
	$('#fund_form').validate({
        errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },
        rules : {
            fund_name : {
                required   : true
            },
			fund_content : {
                required   : true
            }
        },
        messages : {
            fund_name : {
                required   : '标题不能为空'
            },
			fund_content : {
                required   : '说明不能为空'
            }
        }
    });
    $("#fund_banner").change(function(){
        $("#textfield1").val($(this).val());
    });

// 上传图片类型
    $('input[class="type-file-file"]').change(function(){
        var filepatd=$(this).val();
        var extStart=filepatd.lastIndexOf(".");
        var ext=filepatd.substring(extStart,filepatd.lengtd).toUpperCase();
        if(ext!=".PNG"&&ext!=".GIF"&&ext!=".JPG"&&ext!=".JPEG"){
            alert("请上传正确的图片格式");
            $(this).attr('value','');
            return false;
        }
    });
});
</script>