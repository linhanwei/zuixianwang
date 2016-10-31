<?php defined('InSystem') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
      <div class="item-title">
          <h3>活动管理</h3>
          <ul class="tab-base">
              <li><a href="index.php?act=activity_project&op=<?php echo $_GET['project_type'];?>"><span>管理</span></a></li>
              <li><a href="JavaScript:void(0);" class="current"><span>新增/编辑活动</span></a></li>
          </ul>
      </div>
  </div>
    <div class="fixed-empty"></div>
    <form id="add_form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="form_submit" value="ok" />
        <input type="hidden" name="project_id" id="project_id" value="<?php echo $_GET['project_id']?$_GET['project_id'] : 0;?>" />
        <input type="hidden" name="project_type"  value="<?php echo $_GET['project_type'];?>" />
        <table class="table tb-type2">
            <tbody>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="validation" for="project_name">活动名称:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <input type="text" value="<?php echo $output['project']['project_name'];?>" name="project_name" id="project_name" class="txt">
                </td>
                <td class="vatop tips"></td>
            </tr>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="validation" for="project_desc">简介:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <textarea  name="project_desc" id="project_desc" rows="3"><?php echo $output['project']['project_desc'];?></textarea>
                </td>
                <td class="vatop tips"></td>
            </tr>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="validation" for="project_title">价格:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <input type="text" value="<?php echo $output['project']['project_price'];?>" name="project_price" id="project_price" class="txt">
                </td>
                <td class="vatop tips">填写价格需付款参加活动</td>
            </tr>
            <tr>
                <?php if($output['project']['project_type'] == 'fang'){ ?>
                    <td colspan="2" class="required"><label for="seller_center_logo">封面图(宽高比为:  2:1):</label></td>
                <?php }else{ ?>
                    <td colspan="2" class="required"><label for="seller_center_logo">封面图(宽高比为:  1:1):</label></td>
                <?php } ?>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><span class="type-file-show"><img class="show_image" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/preview.png">
            <div class="type-file-preview"><img src="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_ACTIVITY_PROJECT.DS.$output['project']['project_pic']);?>"></div>
            </span><span class="type-file-box"><input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
            <input name="project_pic" type="file" class="type-file-file" id="project_pic" size="30" hidefocus="true" nc_type="change_project_pic">
            </span></td>
                <td class="vatop tips"><span class="vatop rowform">活动列表封面图</span></td>
            </tr>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="validation" for="agent_name">地址:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <input type="text" value="<?php echo $output['project']['project_address'];?>" name="project_address" id="project_address" class="txt">
                </td>
                <td class="vatop tips"></td>
            </tr>

            <tr class="noborder">
                <td colspan="2" class="required"><label class="validation" for="agent_name">联系电话:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <input type="text" value="<?php echo $output['project']['project_phone'];?>" name="project_phone" id="project_phone" class="txt">
                </td>
                <td class="vatop tips"></td>
            </tr>
            <tr>
                <td colspan="2" class="required">
                    <label for="project_name">详细介绍:</label>
                </td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <?php showEditor('project_content',$output['project']['project_content']);?></td>
                <td class="vatop tips"></td>
            </tr>
            <tr>
                <td colspan="2" class="required"><label for="seller_center_logo">Banner 1:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><span class="type-file-show"><img class="show_image" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/preview.png">
            <div class="type-file-preview"><img src="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_ACTIVITY_PROJECT.DS.$output['project']['project_pic_1']);?>"></div>
            </span><span class="type-file-box"><input type='text' name='textfield' id='textfield2' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
            <input name="project_pic_1" type="file" class="type-file-file" id="project_pic_1" size="30" hidefocus="true" nc_type="change_project_pic_1">
            </span></td>
                <td class="vatop tips"><span class="vatop rowform">轮播图一</span></td>
            </tr>
            <tr>
                <td colspan="2" class="required"><label for="seller_center_logo">Banner 2:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><span class="type-file-show"><img class="show_image" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/preview.png">
            <div class="type-file-preview"><img src="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_ACTIVITY_PROJECT.DS.$output['project']['project_pic_2']);?>"></div>
            </span><span class="type-file-box"><input type='text' name='textfield' id='textfield3' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
            <input name="project_pic_2" type="file" class="type-file-file" id="project_pic_2" size="30" hidefocus="true" nc_type="change_project_pic_2">
            </span></td>
                <td class="vatop tips"><span class="vatop rowform">轮播图二</span></td>
            </tr>
            <tr>
                <td colspan="2" class="required"><label for="seller_center_logo">Banner 3:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><span class="type-file-show"><img class="show_image" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/preview.png">
            <div class="type-file-preview"><img src="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_ACTIVITY_PROJECT.DS.$output['project']['project_pic_3']);?>"></div>
            </span><span class="type-file-box"><input type='text' name='textfield' id='textfield4' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
            <input name="project_pic_3" type="file" class="type-file-file" id="project_pic_3" size="30" hidefocus="true" nc_type="change_project_pic_3">
            </span></td>
                <td class="vatop tips"><span class="vatop rowform">轮播图三</span></td>
            </tr>
            <tr>
                <td colspan="2" class="required"><label for="seller_center_logo">Banner 4:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><span class="type-file-show"><img class="show_image" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/preview.png">
            <div class="type-file-preview"><img src="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_ACTIVITY_PROJECT.DS.$output['project']['project_pic_4']);?>"></div>
            </span><span class="type-file-box"><input type='text' name='textfield' id='textfield5' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
            <input name="project_pic_4" type="file" class="type-file-file" id="project_pic_4" size="30" hidefocus="true" nc_type="change_project_pic_4">
            </span></td>
                <td class="vatop tips"><span class="vatop rowform">轮播图四</span></td>
            </tr>
            <tr>
                <td colspan="2" class="required"><label for="seller_center_logo">Banner 5:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><span class="type-file-show"><img class="show_image" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/preview.png">
            <div class="type-file-preview"><img src="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_ACTIVITY_PROJECT.DS.$output['project']['project_pic_5']);?>"></div>
            </span><span class="type-file-box"><input type='text' name='textfield' id='textfield6' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
            <input name="project_pic_5" type="file" class="type-file-file" id="project_pic_5" size="30" hidefocus="true" nc_type="change_project_pic_5">
            </span></td>
                <td class="vatop tips"><span class="vatop rowform">轮播图五</span></td>
            </tr>
            <?php if($_GET['project_id']>0){?>
            <tr>
                <td colspan="2" class="required"><label>状态:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform onoff"><label for="project_state1" class="cb-enable <?php if($output['project']['project_state'] == '1'){ ?>selected<?php } ?>" ><span>进行中</span></label>
                    <label for="project_state0" class="cb-disable <?php if($output['project']['project_state'] == '0'){ ?>selected<?php } ?>" ><span>已结束</span></label>
                    <input id="project_state0" name="project_state" <?php if($output['project']['project_state'] == '0'){ ?>checked="checked"<?php } ?>  value="0" type="radio">
                    <input id="project_state1" name="project_state" <?php if($output['project']['project_state'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio"></td>
                <td class="vatop tips"></td>
            </tr>
            <?php }?>
            </tbody>
            <tfoot>
            <tr class="tfoot">
                <td colspan="15"><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span><?php echo $lang['nc_submit'];?></span></a></td>
            </tr>
            </tfoot>
        </table>
    </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script>
<script>
    $("#project_pic").change(function(){
        $("#textfield1").val($(this).val());
    });
    $("#project_pic_1").change(function(){
        $("#textfield2").val($(this).val());
    });
    $("#project_pic_2").change(function(){
        $("#textfield3").val($(this).val());
    });
    $("#project_pic_3").change(function(){
        $("#textfield4").val($(this).val());
    });
    $("#project_pic_4").change(function(){
        $("#textfield5").val($(this).val());
    });
    $("#project_pic_5").change(function(){
        $("#textfield6").val($(this).val());
    });


    // 上传图片类型
    $('input[class="type-file-file"]').change(function(){
        var filepatd=$(this).val();
        var extStart=filepatd.lastIndexOf(".");
        var ext=filepatd.substring(extStart,filepatd.lengtd).toUpperCase();
        if(ext!=".PNG"&&ext!=".GIF"&&ext!=".JPG"&&ext!=".JPEG"){
            alert("");
            $(this).attr('value','');
            return false;
        }
    });

    $(document).ready(function(){
        //按钮先执行验证再提交表单
        $("#submitBtn").click(function(){
            if($("#add_form").valid()){
                $("#add_form").submit();
            }
        });

        <?php if($_GET['project_id'] == 0){?>
        $("#add_form").validate({
            errorPlacement: function(error, element){
                error.appendTo(element.parent().parent().prev().find('td:first'));
            },
            rules : {
                project_name : {
                    required : true,
                    remote	: {
                        url :'index.php?act=activity_project&op=check_project_name&project_id=<?php echo $_GET['project_id'];?>',
                        type:'get',
                        data:{
                            project_name : function(){
                                return $('#project_name').val();
                            }
                        }
                    }
                }
            },
            messages : {
                project_name : {
                    required : '请输入项目名称',
                    remote	 : '该活动已添加'
                }
            }
        });
        <?php }?>
    });
</script>