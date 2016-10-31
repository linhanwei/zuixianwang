<?php defined('InSystem') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
      <div class="item-title">
          <h3>区域</h3>
          <ul class="tab-base">
              <li><a href="index.php?act=agent&op=area"><span>区域管理</span></a></li>
              <li><a href="JavaScript:void(0);" class="current"><span><?php if($_GET['aa_id']) echo '编辑区域';else echo '添加区域';?></span></a></li>
          </ul>
      </div>
  </div>
    <div class="fixed-empty"></div>
    <form id="add_form" method="post">
        <input type="hidden" name="form_submit" value="ok" />
        <input type="hidden" name="aa_id" id="aa_id" value="<?php echo $_GET['aa_id']?$_GET['aa_id'] : 0;?>" />
        <table class="table tb-type2 nobdb">
            <tbody>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="validation" for="aa_name">区域名称:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><input type="text" id="aa_name" maxlength="40" name="aa_name" class="txt" value="<?php echo $output['agent_area']['aa_name'];?>"></td>
                <td class="vatop tips"></td>
            </tr>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="validation" for="aa_limit">限制加盟:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><input type="text" id="aa_limit" maxlength="40" name="aa_limit" class="txt" value="<?php echo $output['agent_area']['aa_limit'] ? $output['agent_area']['aa_limit'] : 2;?>"></td>
                <td class="vatop tips">最少为两人</td>
            </tr>
            <tr>
                <td colspan="2"><table class="table tb-type2 nomargin">
                        <tbody> <tr>
                        <?php
                        $i = 1;
                        foreach((array)$output['area_list'] as $k => $v) { ?>

                                <td>
                                    <label style="width:100px"><?php echo (!empty($v)) ? $v : '&nbsp;'; ?></label>
                                    <input name="aa_area[]" type="checkbox" value="<?php echo $k;?>" <?php if($output['aa_area'] && in_array($k,$output['aa_area'])) echo 'checked';?>>
                                </td>

                        <?php if($i++ % 10 == 0) echo '</tr><tr>'; } ?></tr>
                        </tbody>
                    </table></td>
            </tr>
            </tbody>
            <tfoot>
            <tr class="tfoot">
                <td colspan="2"><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span><?php echo $lang['nc_submit'];?></span></a></td>
            </tr>
            </tfoot>
        </table>
    </form>
</div>
<script>
    function selectLimit(name){
        if($('#'+name).attr('checked')) {
            $('.'+name).attr('checked',true);
        }else {
            $('.'+name).attr('checked',false);
        }
    }
    $(document).ready(function(){
        //按钮先执行验证再提交表单
        $("#submitBtn").click(function(){
            if($("#add_form").valid()){
                $("#add_form").submit();
            }
        });

        $("#add_form").validate({
            errorPlacement: function(error, element){
                error.appendTo(element.parent().parent().prev().find('td:first'));
            },
            rules : {
                aa_name : {
                    required : true,
                    remote	: {
                        url :'index.php?act=agent&op=ajax&branch=check_aa_name',
                        type:'get',
                        data:{
                            aa_name : function(){
                                return $('#aa_name').val();
                            },
                            aa_id : function(){
                                return $('#aa_id').val();
                            }
                        }
                    }
                }
            },
            messages : {
                aa_name : {
                    required : '<?php echo $lang['nc_none_input'];?>',
                    remote	 : '区域名称已使用'
                }
            }
        });
    });
</script>