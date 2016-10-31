<?php defined('InSystem') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>油卡管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=oil&op=card_list" ><span><?php echo $lang['nc_manage']?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span>绑定油卡</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div> <table class="table tb-type2" id="prompt">
        <tbody>
        <tr class="space odd">
            <th colspan="12"><div class="title">
                    <h5><?php echo $lang['nc_prompts'];?></h5>
                    <span class="arrow"></span></div></th>
        </tr>
        <tr>
            <td><ul>
                    <li>仅用于现场或线下送油卡,会员必须先升级为VIP会员</li>
                </ul></td>
        </tr>
        </tbody>
    </table>
  <form id="oil_form" enctype="multipart/form-data" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
      <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="oc_type">油卡类型:</label></td>
      </tr>
      <tr class="noborder">
          <td class="vatop rowform">
              <select id="oc_type" name="oc_type">
                  <option value="1">中石化</option>
                  <option value="2">中石油(BP)</option>
                  <option value="3">中石油(广东)</option>
              </select>
          </td>
          <td class="vatop tips"></td>
      </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="member_name">会员手机号:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="" name="member_name" id="member_name" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="mobile">油卡绑定手机号:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="mobile" name="mobile" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
            <td colspan="2" class="required"><label class="validation" for="idcard_name">姓名:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform"><input type="text" id="idcard_name" name="idcard_name" class="txt"></td>
            <td class="vatop tips"></td>
        </tr>
        <tr>
            <td colspan="2" class="required"><label class="validation" for="mobile">身份证号:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform"><input type="text" id="idcard_number" name="idcard_number" class="txt"></td>
            <td class="vatop tips"></td>
        </tr>

        <tr>
            <td colspan="2"><label class="validation" for="invite_code">油卡号:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform"><input type="text" value="" id="card_number" name="card_number" class="txt"></td>
            <td class="vatop tips"></td>
        </tr>
        <tr>
            <td colspan="2"><label class="validation" for="invite_code">备注:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="" id="remark" name="remark" class="txt"></td>
          <td class="vatop tips">请输入为什么要手工添加油卡</td>
        </tr>
        <!--
        <tr>
          <td colspan="2" class="required"><label><?php echo $lang['member_edit_pic']?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
			<span class="type-file-show">
			<img class="show_image" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/preview.png">
			<div class="type-file-preview" style="display: none;"><img id="view_img"></div>
			</span>
            <span class="type-file-box">
              <input type='text' name='member_avatar' id='member_avatar' class='type-file-text' />
              <input type='button' name='button' id='button' value='' class='type-file-button' />
              <input name="_pic" type="file" class="type-file-file" id="_pic" size="30" hidefocus="true" />
            </span>
            </td>
          <td class="vatop tips"><?php echo $lang['member_edit_support']?>gif,jpg,jpeg,png</td>
        </tr>
        -->
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="15"><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span><?php echo $lang['nc_submit'];?></span></a></td>
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
<script type="text/javascript">
//裁剪图片后返回接收函数
function call_back(picname){
	$('#member_avatar').val(picname);
	$('#view_img').attr('src','<?php echo UPLOAD_SITE_URL.'/'.ATTACH_AVATAR;?>/'+picname);
}
$(function(){
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
						ajax_form('cutpic','<?php echo $lang['nc_cut'];?>','index.php?act=common&op=pic_cut&type=member&x=120&y=120&resize=1&ratio=1&url='+data.url,690);
					}else{
						alert(data.msg);
					}
					$('input[class="type-file-file"]').bind('change',uploadChange);
				},
				error: function (data, status, e)
				{
					alert('上传失败');
					$('input[class="type-file-file"]').bind('change',uploadChange);
				}
			}
		)
	};
	//按钮先执行验证再提交表单
	$("#submitBtn").click(function(){
    if($("#oil_form").valid()){
     $("#oil_form").submit();
	}
	});
    $('#oil_form').validate({
        errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },
        rules : {
			member_name: {
				required : true,
				minlength: 11,
				maxlength: 11,
				remote   : {
                    url :'index.php?act=oil&op=ajax&branch=check_user_name',
                    type:'get',
                    data:{
                        user_name : function(){
                            return $('#member_name').val();
                        }
                    }
                }
			},mobile: {
				required : true,
                maxlength: 11,
                minlength: 11,
                remote   : {
                    url :'index.php?act=oil&op=ajax&branch=check_mobile',
                    type:'get',
                    data:{
                        mobile : function(){
                            return $('#mobile').val();
                        }
                    }
                }
            },idcard_name: {
                required : true
            },idcard_number: {
                required : true,
                maxlength: 18,
                minlength: 15,
                remote   : {
                    url :'index.php?act=oil&op=ajax&branch=check_idcard',
                    type:'get',
                    data:{
                        idcard_number : function(){
                            return $('#idcard_number').val();
                        }
                    }
                }
            },
            card_number: {
                required : true,
                remote   : {
                    url :'index.php?act=oil&op=ajax&branch=check_card_number',
                    type:'get',
                    data:{
                        mobile : function(){
                            return $('#card_number').val();
                        }
                    }
                }
            },
            remark:{
                required : true
            }
        },
        messages : {
			member_name: {
				required : '会员手机号不能为空',
				maxlength: '请输入正确的手机号码',
				minlength: '请输入正确的手机号码',
				remote   : '会员不存在或已绑定油卡'
			},
            mobile : {
				required :  '油卡绑定手机号不能为空',
                maxlength: '请输入正确的手机号码',
                minlength: '请输入正确的手机号码',
                remote   : '一个手机号只能绑定一张油卡'
            },idcard_name : {
                required :  '请输入身份证姓名'
            },idcard_number : {
                required :  '请输入身份证号',
                maxlength: '请输入正确的身份证号',
                minlength: '请输入正确的身份证号',
                remote   : '请输入正确的身份证号'
            },
            card_number : {
                required: '请输入正确的油卡号码',
                remote:'一张油卡只能绑定一个帐号'
			},
            remark : '请输入备注'
        }
    });
});
</script>
