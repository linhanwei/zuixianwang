<?php defined('InSystem') or exit('Access Invalid!');?>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>区域</h3>
            <ul class="tab-base">
                <li><a href="JavaScript:void(0);" class="current"><span>留言详细</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
  <form id="feedback_form" method="POST">
      <input type="hidden" name="fb_id" value="<?php echo $_GET['fb_id'];?>" />
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
      <tr class="noborder">
          <td colspan="2" class="required"><label>用户名: </label></td>
      </tr>
      <tr class="noborder">
          <td class="vatop rowform">
              <?php echo $output['feedback']['fb_member_name'];?>
          </td>
          <td class="vatop tips"></td>
      </tr>
      <?php if($output['feedback']['fb_image']){?>
      <tr class="noborder">
          <td colspan="2" class="required"><label>图片: </label></td>
      </tr>
      <tr class="noborder">
          <td class="vatop rowform">
              <a target="_blank" href="<?php echo UPLOAD_SITE_URL . DS . ATTACH_PATH .DS.'agent'.DS.$output['feedback']['fb_image'];?>"> <img src="<?php echo UPLOAD_SITE_URL . DS . ATTACH_PATH.DS.'agent'.DS.$output['feedback']['fb_image'];?>" alt="" width="200" height="200"/> </a>
          </td>
          <td class="vatop tips"></td>
      </tr>
      <?php }?>
      <tr class="noborder">
          <td colspan="2" class="required"><label>内容: </label></td>
      </tr>
      <tr class="noborder">
          <td class="vatop rowform">
              <?php echo $output['feedback']['fb_content'];?>
          </td>
          <td class="vatop tips"></td>
      </tr>
      <tr class="noborder">
          <td colspan="2" class="required"><label>处理状态: </label></td>
      </tr>
      <tr class="noborder">
          <td class="vatop rowform">
                  <?php
                    if($output['feedback']['fb_state'] == 2){
                        echo '已处理';
                    }else{
                        echo '未处理';
                    }
                  ?>
          </td>
          <td class="vatop tips"></td>
      </tr><tr class="noborder">
          <td colspan="2" class="required"><label>处理记录: </label></td>
      </tr>
      <tr class="noborder">
          <td class="vatop rowform">
              <textarea name="fb_remark" rows="6" class="tarea"></textarea>
          </td>
          <td class="vatop tips">只是记录</td>
      </tr>

      </tbody>
<?php if($output['feedback']['fb_state']==1){?>
      <tfoot>
        <tr class="tfoot">
          <td colspan="15"><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span>处理</span></a></td>
        </tr>
      </tfoot>
        <?php }?>
    </table>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#feedback_form").valid()){
        $("#feedback_form").submit();
	}
	});
});
</script>
