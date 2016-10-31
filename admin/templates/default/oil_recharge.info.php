<?php defined('InSystem') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>油卡充值</h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span>充值明细</span></a></li>
        <li><a href="index.php?act=oil&op=recharge"><span>充值列表</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <table class="table tb-type2 nobdb">
    <tbody>
      <tr class="noborder">
        <td colspan="2" class="required"><label>充值单号:</label></td>
      </tr>
      <tr class="noborder">
        <td class="vatop rowform"><?php echo $output['info']['ol_sn']; ?></td>
        <td class="vatop tips"></td>
      </tr>
      <tr>
        <td colspan="2" class="required"><label>会员手机:</label></td>
      </tr>
      <tr class="noborder">
        <td class="vatop rowform"><?php echo $output['info']['ol_member_name']; ?></td>
        <td class="vatop tips"></td>
      </tr>
      <tr>
          <td colspan="2" class="required"><label>油卡姓名:</label></td>
      </tr>
      <tr class="noborder">
          <td class="vatop rowform"><?php echo $output['info']['oil']['oc_idcard_name']; ?></td>
          <td class="vatop tips"></td>
      </tr>
      <tr>
          <td colspan="2" class="required"><label>油卡手机:</label></td>
      </tr>
      <tr class="noborder">
          <td class="vatop rowform"><?php echo $output['info']['oil']['oc_mobile']; ?></td>
          <td class="vatop tips"></td>
      </tr>
      <tr>
          <td colspan="2" class="required"><label>油卡号:</label></td>
      </tr>
      <tr class="noborder">
          <td class="vatop rowform"><strong><span style="color:#FF0000;"><?php echo $output['info']['oil']['oc_card_number']; ?></span></strong></td>
          <td class="vatop tips"></td>
      </tr>
      <?php if($output['info']['ol_trade_code']){?>
      <tr>
          <td colspan="2" class="required"><label>充值单号:</label></td>
      </tr>
      <tr class="noborder">
          <td class="vatop rowform"><strong><span style="color:#FF0000;"><?php echo $output['info']['ol_trade_code']; ?></span></strong></td>
          <td class="vatop tips"></td>
      </tr>
      <?php }?>
      <tr>
        <td colspan="2" class="required"><label>充值金额(<?php echo $lang['currency_zh'];?>):</label></td>
      </tr>
      <tr class="noborder">
        <td class="vatop rowform"><strong><span style="color:#FF0000;"><?php echo $output['info']['ol_amount']; ?></span></strong></td>
        <td class="vatop tips"></td>
      </tr>
      <tr>
        <td colspan="2" class="required"><label>添加时间:</label></td>
      </tr>
      <tr class="noborder">
        <td class="vatop rowform"><?php echo @date('Y-m-d H:i:s',$output['info']['ol_add_time']); ?></td>
        <td class="vatop tips"></td>
      </tr>
      <?php if (intval($output['info']['ol_payment_time'])) {?>
      <tr>
        <td colspan="2" class="required"><label>支付方式:</label></td>
      </tr>
      <tr class="noborder">
        <td class="vatop rowform"><?php echo $output['info']['ol_payment_name']; ?></td>
        <td class="vatop tips"></td>
      </tr>
      <tr>
        <td colspan="2" class="required"><label>支付时间:</label></td>
      </tr>
      <tr class="noborder">
        <td class="vatop rowform">
        <?php if (date('His',$output['info']['ol_payment_time']) == 0) {?>
        <?php echo date('Y-m-d',$output['info']['ol_payment_time']);?>
        <?php } else {?>
        <?php echo date('Y-m-d H:i:s',$output['info']['ol_payment_time']);?>
        <?php } ?>
        </td>
        <td class="vatop tips"></td>
      </tr>
      <tr>
        <td colspan="2" class="required"><label>第三方支付平台交易号:</label></td>
      </tr>
      <tr class="noborder">
        <td class="vatop rowform"><?php echo $output['info']['ol_trade_sn'];?></td>
        <td class="vatop tips"></td>
      </tr>
      <?php } ?>
      <!-- 显示管理员名称 -->
      <?php if (trim($output['info']['ol_admin']) != ''){ ?>
      <tr>
        <td colspan="2" class="required"><label>操作管理员:</label></td>
      </tr>
      <tr class="noborder">
        <td class="vatop rowform"><?php echo $output['info']['ol_admin']; ?></td>
        <td class="vatop tips"></td>
      </tr>
      <?php }?>
    </tbody>
      <?php if ($output['info']['ol_state'] == 1) {?>
        <tfoot id="submit-holder">
        <tr class="tfoot">
        <td colspan="2">
        <a class="btn" href="javascript:change_state(<?php echo $output['info']['ol_id']; ?>);"><span>更改充值状态</span></a>
        </td>
        </tr>
        </tfoot>
     <?php } ?>
  </table>
</div>

<script>
    function change_state(id){
        var url = 'index.php?act=oil&op=recharge_edit&id=' + id;
        var trade_code = '';
        if(trade_code = prompt('请输入油卡充值卡号，以备用户查询')){
            url += '&trade_code=' + trade_code;

            document.location.href = url;
        }
    }
</script>
