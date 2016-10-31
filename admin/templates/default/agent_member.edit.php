<?php defined('InSystem') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
      <div class="item-title">
          <h3>代理</h3>
          <ul class="tab-base">
              <li><a href="index.php?act=agent&op=agent"><span>代理商管理</span></a></li>
              <li><a href="JavaScript:void(0);" class="current"><span><?php if($_GET['agent_id']) echo '编辑代理商';else echo '添加代理商';?></span></a></li>
          </ul>
      </div>
  </div>
    <div class="fixed-empty"></div>
    <form id="add_form" method="post">
        <input type="hidden" name="form_submit" value="ok" />
        <input type="hidden" name="agent_id" id="agent_id" value="<?php echo $_GET['agent_id']?$_GET['agent_id'] : 0;?>" />
        <table class="table tb-type2">
            <tbody>
            <tr>
                <td colspan="2" class="required"><label class="validation" for="company_name">公司名称:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><?php echo $output['agent']['company_name'];?></td>
                <td class="vatop tips"></td>
            </tr>
            <tr>
                <td colspan="2" class="required"><label class="agent_info">区域:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"  colspan="2">
                    <span id="region_agent" class="w400">
                            <span><?php echo $output['agent']['area_info'];?></span>
                        </span>
                </td>

            </tr>
            <tr>
                <td colspan="2" class="required"><label class="validation" for="company_name">关联新帐号:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><input type="text" value="" id="member_name" name="member_name" class="txt"></td>
                <td class="vatop tips"></td>
            </tr>
            <tr>
                <td colspan="2" class="required"><label class="validation" for="join_at">关联系统帐号(一个帐号只能关联一个区域):</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <table class="table tb-type2 nomargin">
                        <tbody> <tr>
                            <?php
                            $i = 1;
                            foreach((array)$output['agent_member_list'] as $k => $v) { ?>

                                <td>
                                    <label style="width:100px"><?php echo (!empty($v['member_name'])) ? $v['member_name'] : '&nbsp;'; ?></label>
                                    <a href="javascript:void(0)" onclick="if(confirm('确定删除该关联?')){location.href='index.php?act=agent&op=agent_member_del&id=<?php echo $v['id']; ?>'}">删</a>
                                </td>

                                <?php if($i++ % 10 == 0) echo '</tr><tr>'; } ?></tr>
                        </tbody>
                    </table>
                </td>
                <td class="vatop tips"></td>
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
    regionInit("region");
    regionAgentInit("region_agent");
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
            <?php if($_GET['agent_id'] == 0){?>
            if($("#add_form").valid()){
                $("#add_form").submit();
            }
            <?php }else{?>
            $("#add_form").submit();
            <?php }?>
        });

        <?php if($_GET['agent_id'] == 0){?>
        $("#add_form").validate({
            errorPlacement: function(error, element){
                error.appendTo(element.parent().parent().prev().find('td:first'));
            },
            rules : {
                agent_name : {
                    required : true,
                    remote	: {
                        url :'index.php?act=agent&op=ajax&branch=check_agent_name',
                        type:'get',
                        data:{
                            agent_name : function(){
                                return $('#agent_name').val();
                            }
                        }
                    }
                },
                password : {
                    required : true
                }
            },
            messages : {
                agent_name : {
                    required : '请输入登录帐号',
                    remote	 : '已是代理商'
                },
                password : {
                    required : '请输入至少6位的登录密码'
                }
            }
        });
        <?php }?>
    });
</script>