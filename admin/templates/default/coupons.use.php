<?php defined('InSystem') or exit('Access Invalid!');?>
<style>
.switch-tab-title {font-size:14px; margin-right:15px; }
</style>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>电子消费券</h3>
      <ul class="tab-base">
        <li><a href="<?php echo urlAdmin('coupons', 'index'); ?>"><span>列表</span></a></li>
          <li><a href="javascript:void(0);" class="current"><span>使用</span></a></li>
        <li><a href="<?php echo urlAdmin('coupons', 'add'); ?>"><span>新增</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>

  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title"><h5><?php echo $lang['nc_prompts'];?></h5><span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td>
          <ul>
            <li>请输入对应的电子券进行使用</li>
          </ul>
        </td>
      </tr>
    </tbody>
  </table>

  <form method="post" enctype="multipart/form-data" name="form_use" id="form_use">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
      <tr>
          <td colspan="2" class="required"><label>电子券号码:</label></td>
      </tr>
      <tr class="noborder">
          <td class="vatop rowform"><input class="txt" type="text" id="sn" name="sn" /></td>
          <td class="vatop tips">请输入会员电子券</td>
      </tr>
      <tr>
          <td colspan="2" class="required"><label>会员（手机）:</label></td>
      </tr>
      <tr class="noborder">
          <td class="vatop rowform"><input class="txt" type="text" id="member_name" name="member_name" /></td>
          <td class="vatop tips">请输入领取会员手机号,必须先注册</td>
      </tr>

      </tbody>

      <tfoot>
        <tr class="tfoot">
          <td colspan="2" ><a href="javascript:void(0);" class="btn" id="submitBtn"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript">
$(function(){

$("#submitBtn").click(function(){
    $("#form_use").submit();
});

$("#form_use").validate({
    errorPlacement: function(error, element){
        error.appendTo(element.parent().parent().prev().find('td:first'));
    },
    rules : {
        sn : {
            required : true,
            remote   : {
                url :'index.php?act=coupons&op=ajax&branch=check_coupons',
                type:'get',
                data:{
                    sn : function(){
                        return $('#sn').val();
                    }
                }
            }
        },
        member_name : {
            required : true,
            remote : {
                url :'index.php?act=coupons&op=ajax&branch=check_member_name',
                type:'get',
                data:{
                    member_name : function(){
                        return $('#member_name').val();
                    }
                }
            }
        }
    },
    messages : {
        sn : {
            required : '请输入电子券',
            remote : '电子券不存在或已经使用'
        },
        member_name : {
            required: '请输入会员手机号码',
            remote : '手机号还没有注册，请选注册'
        }
    }
});
});
</script>
