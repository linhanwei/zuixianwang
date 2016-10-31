<?php defined('InSystem') or exit('Access Invalid!'); ?>
<style>
    input {
        width: 270px;
    }
</style>
<div class="page">
    <!-- 页面导航 -->
    <div class="fixed-bar">
        <div class="item-title">
            <h3><?php echo $output['item_title']; ?></h3>
            <ul class="tab-base">
                <?php foreach ($output['menu'] as $menu) {
                    if ($menu['menu_key'] == $output['menu_key']) { ?>
                        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $menu['menu_name']; ?></span></a>
                        </li>
                    <?php } else { ?>
                        <li>
                            <a href="<?php echo $menu['menu_url']; ?>"><span><?php echo $menu['menu_name']; ?></span></a>
                        </li>
                    <?php }
                } ?>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <!-- 帮助 -->
    <table class="table tb-type2" id="prompt">
        <tbody>
        <tr class="space odd">
            <th colspan="12" class="nobg">
                <div class="title nomargin">
                    <h5><?php echo $lang['nc_prompts']; ?></h5>
                    <span class="arrow"></span></div>
            </th>
        </tr>
        <tr>
            <td>
                <ul>
                    <li>上传安卓安装的apk文件</li>
                </ul>
            </td>
        </tr>
        </tbody>
    </table>
    <form id="doc_form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="form_submit" value="ok" />
        <table class="table tb-type2 nobdb">
            <tbody>
            <tr>
                <td colspan="2" class="required"><label class="validation">apk文件:</label></td>
            </tr>
            <tr class="noborder">
                <td colspan="3" >
                    <input type="file"  id="fileupload" name="fileupload" />
                </td>
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

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/dialog/dialog.js" id="dialog_js"
        charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.edit.js"></script>

<script src="<?php echo RESOURCE_SITE_URL; ?>/js/layer/layer.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#doc_form').validate({
            errorPlacement: function(error, element){
                error.appendTo(element.parent().parent().prev().find('td:first'));
            },
            rules : {


                // content : {
                //           required   : true
                //       }
            },
            messages : {


                // content : {
                //           required   : 'banner说明不能为空!'
                //       }
            }
        });

        $("#submitBtn").click(function(){
            if($("#doc_form").valid()){
                layer.alert('正在上传apk,请稍候...', {title: '信息提示'});

                $("#doc_form").submit();
            }
        });
    });
</script> 
