<?php defined('InSystem') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>banner管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=banner&op=index"><span><?php echo $lang['nc_manage'];?></span></a></li>
        <li><a href="index.php?act=banner&op=add"><span><?php echo '新增';?></span></a></li>
        <li><a href="" class="current"><span>编辑</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="doc_form" method="post" enctype="multipart/form-data">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2 nobdb">
      <tbody>
        <tr>
          <td colspan="2" class="required"><label class="validation">标题: </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" size="100" value="<?php echo $output['info']['title'];?>" name="b_title" id="b_title" class="infoTableInput"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation">banner图片(宽高比为: 2:1,单张图片不能超过300K):</label></td>
        </tr>
        <tr class="noborder">
          <td colspan="3" >
             <?php if($output['info']['image_name']){ ?>
              <img src="<?php echo getBannerImageUrl($output['info']['image_name']); ?>" style="width: 100px;">
            <?php } ?>
            <input type="file"  id="fileupload" name="fileupload" />        
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation">URL链接: </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            <input type="text" size="100" value="<?php echo $output['info']['url'];?>" name="url" id="url" class="infoTableInput"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation">banner说明: </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><?php showEditor('content',$output['info']['content']);?></td>
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
    if($("#doc_form").valid()){
     $("#doc_form").submit();
  }
  });
});
//
$(document).ready(function(){
  $('#doc_form').validate({
        errorPlacement: function(error, element){
      error.appendTo(element.parent().parent().prev().find('td:first'));
        },
        rules : {
            b_title : {
                required   : true
            },
            url : {
                required   : true
            },
           
      // content : {
      //           required   : true
      //       }
        },
        messages : {
            b_title : {
                required   : '标题不能为空!'
            },
            url : {
                required   : 'URL链接不能为空!'
            },
           
      // content : {
      //           required   : 'banner说明不能为空!'
      //       }
        }
    });
    
});

function insert_editor(file_path){
  KE.appendHtml('content', '<img src="'+ file_path + '" alt="'+ file_path + '">');
}

</script>