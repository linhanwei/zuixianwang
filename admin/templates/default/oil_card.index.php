<?php defined('InSystem') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>油卡管理</h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_manage']?></span></a></li>
          <li><a href="index.php?act=oil&op=card_add" ><span>绑定油卡</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch" id="formSearch">
    <input type="hidden" value="oil" name="act">
    <input type="hidden" value="card_list" name="op">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <td><select name="search_field_name" >
                  <option <?php if($output['search_field_name'] == 'member_name'){ ?>selected='selected'<?php } ?> value="member_name">会员手机</option>
                  <option <?php if($output['search_field_name'] == 'mobile'){ ?>selected='selected'<?php } ?> value="mobile">油卡手机</option>
                  <option <?php if($output['search_field_name'] == 'card_number'){ ?>selected='selected'<?php } ?> value="card_number">油卡号</option>
              </select></td>
          <td><input type="text" value="<?php echo $output['search_field_value'];?>" name="search_field_value" class="txt"></td>


            <td>
                <select id="paystate_search" name="paystate_search">
                    <option value="">支付状态</option>
                    <option value="0" <?php if($_GET['paystate_search'] == '0' ) { ?>selected="selected"<?php } ?>>未支付</option>
                    <option value="1" <?php if($_GET['paystate_search'] == '1' ) { ?>selected="selected"<?php } ?>>已支付</option>
                </select></td><td><select name="search_state" >
                    <option value=''>油卡状态</option>
                    <option value='1' <?php if($_GET['search_state'] == '1' ) { ?>selected="selected"<?php } ?>>待处理</option>
                    <option value='2' <?php if($_GET['search_state'] == '2' ) { ?>selected="selected"<?php } ?>>购买成功</option>
                    <option value='3' <?php if($_GET['search_state'] == '3' ) { ?>selected="selected"<?php } ?>>驳回</option>
                </select></td>
          <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
            <?php if($output['search_field_value'] != '' or $output['search_sort'] != ''){?>
            <a href="index.php?act=oil&op=card_list" class="btns "><span><?php echo $lang['nc_cancel_search']?></span></a>
            <?php }?></td>
        </tr>
      </tbody>
    </table>
  </form>
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title">
            <h5><?php echo $lang['nc_prompts'];?></h5>
            <span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td><ul>
            <li>管理会员油卡信息</li>
                <li>申请中可以进行驳回，驳回后金额退回会员帐户余额。</li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <form method="post" id="form_member">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2 nobdb">
      <thead>
        <tr class="thead">
            <th >会员</th>
            <th >类型</th>
          <th class="align-center">购买单号</th>
          <th class="align-center">创建时间</th>
          <th class="align-center">付款时间</th>
          <th class="align-center">支付状态</th>
            <th class="align-center">油卡手机</th>
            <th class="align-center">油卡号</th>
          <th class="align-center">油卡状态</th>
          <th class="align-center"><?php echo $lang['nc_handle']; ?></th>
        </tr>
      <tbody>
        <?php if(!empty($output['oil_card_list']) && is_array($output['oil_card_list'])){ ?>
        <?php foreach($output['oil_card_list'] as $k => $v){ ?>
        <tr class="hover member"><td><p class="name"><strong><?php echo $v['oc_member_name']; ?></strong>
                    <?php if($v['member']['member_truename']){?>
                        (<?php echo '姓名:' .  $v['member']['member_truename']; ?>)<?php }?></p>
            </td>
            <td><?php
                switch($v['oc_type']){
                    case 1:
                        echo '中石化';
                        break;
                    case 2:
                        echo '中石油BP';
                        break;
                    case 3:
                        echo '中石油广东专用';
                        break;
                    default:
                        echo '中石化';
                        break;
                }
                ?></td>
            <td class="align-center"><?php echo $v['oc_sn']; ?></td>

          <td class="align-center"><?php echo $v['oc_add_time']; ?></td>
          <td class="w150 align-center"><p><?php echo $v['oc_payment_time']; ?></p></td>
          <td class="align-center"><?php echo $v['oc_payment_state'] ? '已支付' : '未支付'; ?></td>
          <td class="align-center"><?php echo $v['oc_mobile'];?></td>
          <td class="align-center"><?php echo $v['oc_card_number'];?></td>
        <td ><?php if($v['oc_state']==1){
                echo '申请中';
            }elseif($v['oc_state']==2) {
                echo '审核通过';
            }else{
                echo '驳回<br/>原因：' . $v['remark'];
            }?></td>
          <td class="align-center">
              <a href="index.php?act=oil&op=card_edit&oc_id=<?php echo $v['oc_id']; ?>"><?php echo $v['oc_state'] == 2 || $v['oc_state'] == 3 ? '查看':$lang['nc_edit'];?></a>

            <?php if($v['oc_state'] == 1){?>
              <a class="btn" href="javascript:if (p=prompt('请输入驳回申请原因?')){window.location.href='index.php?act=oil&op=cancel&oc_id=<?php echo $v['oc_id']; ?>&remark='+p;}else{}"><span>驳回申请</span></a>
        <?php }?>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr class="no_data">
          <td colspan="11"><?php echo $lang['nc_no_record']?></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot class="tfoot">
        <?php if(!empty($output['oil_card_list']) && is_array($output['oil_card_list'])){ ?>
        <tr>
        <td class="w24"></td>
          <td colspan="16">
            <div class="pagination"> <?php echo $output['page'];?> </div></td>
        </tr>
        <?php } ?>
      </tfoot>
    </table>
  </form>
</div>
<script>
$(function(){
    $('#ncsubmit').click(function(){
    	$('input[name="op"]').val('card_list');$('#formSearch').submit();
    });	
});
</script>
