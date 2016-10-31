<?php defined('InSystem') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
      <div class="item-title">
          <h3>商家</h3>
          <ul class="tab-base">
              <li><a href="index.php?act=store_lbs&op=store"><span>管理</span></a></li>
              <li><a href="JavaScript:void(0);" class="current"><span>新增服务商</span></a></li>
          </ul>
      </div>
  </div>
    <div class="fixed-empty"></div>
    <form id="add_form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="form_submit" value="ok" />
        <input type="hidden" name="store_id" id="store_id" value="<?php echo $_GET['store_id']?$_GET['store_id'] : 0;?>" />
        <table class="table tb-type2">
            <tbody>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="validation" for="store_name">服务商名称:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <input type="text" value="<?php echo $output['store']['store_name'];?>" name="store_name" id="store_name" class="txt">
                </td>
                <td class="vatop tips"></td>
            </tr>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="validation" for="store_title">副标题:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <input type="text" value="<?php echo $output['store']['store_title'];?>" name="store_title" id="store_title" class="txt">
                </td>
                <td class="vatop tips"></td>
            </tr>
            <tr>
                <td colspan="2" class="required"><label for="seller_center_logo">头像:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><span class="type-file-show"><img class="show_image" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/preview.png">
            <div class="type-file-preview"><img src="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_STORE.DS.'lbs'.DS.$output['store']['store_avatar']);?>"></div>
            </span><span class="type-file-box"><input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
            <input name="store_avatar" type="file" class="type-file-file" id="store_avatar" size="30" hidefocus="true" nc_type="change_store_avatar">
            </span></td>
                <td class="vatop tips"><span class="vatop rowform">服务商头像</span></td>
            </tr> <tr>
                <td colspan="2" class="required"><label class="member_areainfo">所在区域:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"  colspan="2">
        <span id="region" class="w400">
            <input type="hidden" value="<?php echo $output['store']['province_id'];?>" name="province_id" id="province_id">
            <input type="hidden" value="<?php echo $output['store']['city_id'];?>" name="city_id" id="city_id">
            <input type="hidden" value="<?php echo $output['store']['region_id'];?>" name="region_id" id="region_id" class="area_ids" />
            <input type="hidden" value="<?php echo $output['store']['area_info'];?>" name="area_info" id="area_info" class="area_names" />
            <?php if(!empty($output['store']['area_info'])){?>
                <span><?php echo $output['store']['area_info'];?></span>
                <input type="button" value="<?php echo $lang['nc_edit'];?>" style="background-color: #F5F5F5; width: 60px; height: 32px; border: solid 1px #E7E7E7; cursor: pointer" class="edit_region" />
                <select style="display:none;">
                </select>
            <?php }else{?>
                <select>
                </select>
            <?php }?>
            </span>
                </td>

            </tr>

            <tr class="noborder">
                <td colspan="2" class="required"><label class="validation" for="agent_name">详细地址:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <input type="text" value="<?php echo $output['store']['store_address'];?>" name="store_address" id="store_address" class="txt">
                </td>
                <td class="vatop tips"></td>
            </tr>

            <tr class="noborder">
                <td colspan="2" class="required"><label class="validation" for="agent_name">联系电话:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <input type="text" value="<?php echo $output['store']['store_phone'];?>" name="store_phone" id="store_phone" class="txt">
                </td>
                <td class="vatop tips"></td>
            </tr>
            <tr>
                <td colspan="2" class="required">
                    <label for="store_name">服务商介绍:</label>
                </td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <?php showEditor('store_content',$output['store']['store_content']);?></td>
                <td class="vatop tips"></td>
            </tr>
            <tr>
                <td colspan="2" class="required"><label for="seller_center_logo">Banner 1:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><span class="type-file-show"><img class="show_image" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/preview.png">
            <div class="type-file-preview"><img src="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_STORE.DS.'lbs'.DS.$output['store']['banner_1']);?>"></div>
            </span><span class="type-file-box"><input type='text' name='textfield' id='textfield2' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
            <input name="banner_1" type="file" class="type-file-file" id="banner_1" size="30" hidefocus="true" nc_type="change_banner_1">
            </span></td>
                <td class="vatop tips"><span class="vatop rowform">轮播图一</span></td>
            </tr>
            <tr>
                <td colspan="2" class="required"><label for="seller_center_logo">Banner 2:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><span class="type-file-show"><img class="show_image" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/preview.png">
            <div class="type-file-preview"><img src="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_STORE.DS.'lbs'.DS.$output['store']['banner_2']);?>"></div>
            </span><span class="type-file-box"><input type='text' name='textfield' id='textfield3' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
            <input name="banner_2" type="file" class="type-file-file" id="banner_2" size="30" hidefocus="true" nc_type="change_banner_2">
            </span></td>
                <td class="vatop tips"><span class="vatop rowform">轮播图二</span></td>
            </tr>
            <tr>
                <td colspan="2" class="required"><label for="seller_center_logo">Banner 3:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><span class="type-file-show"><img class="show_image" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/preview.png">
            <div class="type-file-preview"><img src="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_STORE.DS.'lbs'.DS.$output['store']['banner_3']);?>"></div>
            </span><span class="type-file-box"><input type='text' name='textfield' id='textfield4' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
            <input name="banner_3" type="file" class="type-file-file" id="banner_3" size="30" hidefocus="true" nc_type="change_banner_3">
            </span></td>
                <td class="vatop tips"><span class="vatop rowform">轮播图二</span></td>
            </tr>
            <tr>
                <td colspan="2" class="required"><label for="seller_center_logo">Banner 4:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><span class="type-file-show"><img class="show_image" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/preview.png">
            <div class="type-file-preview"><img src="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_STORE.DS.'lbs'.DS.$output['store']['banner_4']);?>"></div>
            </span><span class="type-file-box"><input type='text' name='textfield' id='textfield5' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
            <input name="banner_4" type="file" class="type-file-file" id="banner_4" size="30" hidefocus="true" nc_type="change_banner_4">
            </span></td>
                <td class="vatop tips"><span class="vatop rowform">轮播图二</span></td>
            </tr>
            <tr>
                <td colspan="2" class="required"><label for="seller_center_logo">Banner 5:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><span class="type-file-show"><img class="show_image" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/preview.png">
            <div class="type-file-preview"><img src="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_STORE.DS.'lbs'.DS.$output['store']['banner_5']);?>"></div>
            </span><span class="type-file-box"><input type='text' name='textfield' id='textfield6' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
            <input name="banner_5" type="file" class="type-file-file" id="banner_5" size="30" hidefocus="true" nc_type="change_banner_5">
            </span></td>
                <td class="vatop tips"><span class="vatop rowform">轮播图二</span></td>
            </tr>

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
    $("#store_avatar").change(function(){
        $("#textfield1").val($(this).val());
    });
    $("#banner_1").change(function(){
        $("#textfield2").val($(this).val());
    });
    $("#banner_2").change(function(){
        $("#textfield3").val($(this).val());
    });
    $("#banner_3").change(function(){
        $("#textfield4").val($(this).val());
    });
    $("#banner_4").change(function(){
        $("#textfield5").val($(this).val());
    });
    $("#banner_5").change(function(){
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

    regionInit("region");
    $(document).ready(function(){
        //按钮先执行验证再提交表单
        $("#submitBtn").click(function(){
            if($("#add_form").valid()){
                $("#add_form").submit();
            }
        });

        <?php if($_GET['agent_id'] == 0){?>
        $("#add_form").validate({
            errorPlacement: function(error, element){
                error.appendTo(element.parent().parent().prev().find('td:first'));
            },
            rules : {
                store_name : {
                    required : true,
                    remote	: {
                        url :'index.php?act=store_lbs&op=ckeck_store_name&store_id=<?php echo $_GET['store_id'];?>',
                        type:'get',
                        data:{
                            store_name : function(){
                                return $('#store_name').val();
                            }
                        }
                    }
                }
            },
            messages : {
                store_name : {
                    required : '请输入服务商名称',
                    remote	 : '该服务商已添加'
                }
            }
        });
        <?php }?>
    });
</script>