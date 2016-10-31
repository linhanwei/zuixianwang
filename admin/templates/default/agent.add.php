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
            <tr class="noborder">
                <td colspan="2" class="required"><label class="validation" for="agent_name">登录名称:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <?php if($_GET['agent_id']){
                        echo '<strong>' . $output['agent']['agent_name'] . '</strong>';
                    }else{?>
                    <input type="text" value="" name="agent_name" id="agent_name" class="txt">
                    <?php }?>
                </td>
                <td class="vatop tips"></td>
            </tr>
            <tr>
                <td colspan="2" class="required"><label class="validation" for="password">登录密码:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><input type="text" id="password" name="password" class="txt"></td>
                <td class="vatop tips">
                    <?php if($_GET['agent_id']) echo '不修改密码请漏空';?>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="required"><label class="validation" for="company_name">公司名称:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><input type="text" value="<?php echo $output['agent']['company_name'];?>" id="company_name" name="company_name" class="txt"></td>
                <td class="vatop tips"></td>
            </tr>
            <tr>
                <td colspan="2" class="required"><label class="agent_info">加盟区域:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"  colspan="2">
                    <span id="region_agent" class="w400">
                        <input type="hidden" value="<?php echo $output['agent']['province_id'];?>" name="province_id" id="province_id">
                        <input type="hidden" value="<?php echo $output['agent']['city_id'];?>" name="city_id" id="city_id">
                        <input type="hidden" value="<?php echo $output['agent']['area_id'];?>" name="area_id" id="area_id" class="area_agent_ids" />
                        <input type="hidden" value="<?php echo $output['agent']['area_info'];?>" name="area_info" id="area_info" class="area_agnet_names" />
                        <?php if(!empty($output['agent']['area_info'])){?>
                            <span><?php echo $output['agent']['area_info'];?></span>
                            <input type="button" value="<?php echo $lang['nc_edit'];?>" style="background-color: #F5F5F5; width: 60px; height: 32px; border: solid 1px #E7E7E7; cursor: pointer" class="edit_region_agent" />
                            <select style="display:none;">
                            </select>
                        <?php }else{?>
                            <select>
                            </select>
                        <?php }?>
                        </span>
                </td>

            </tr>
            <tr>
                <td colspan="2" class="required"><label class="validation" for="year_limit">加盟年限:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <select name="year_limit" id="year_limit">
                        <?php for($year=1;$year<=10;$year++){?>
                        <option <?php if($output['agent']['year_limit']==$year) echo 'selected';?>><?php echo $year;?></option>
                        <?php }?>
                    </select>
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