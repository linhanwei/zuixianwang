<?php defined('InSystem') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
<table class="ncsc-default-table">
  <tbody>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><span>
     <form method="post"  action="index.php?act=store_appointment_goods&op=url">
    <input type="hidden" name="form_submit" value="ok" />
    <dl>
      <?php echo '商品URL'.$lang['nc_colon'];?>
          <input name="store_appointment_url" class="text w600" type="text" value="<?php echo $output['store_appointment_url'];?>"/>

    </dl>
    <dl class="bottom">
      <dt>&nbsp;</dt>
      <dd>
        <input type="submit" class="submit" id="confirm_button" value="<?php echo $lang['nc_ok'];?>" />
      </dd>
    </dl>
  </form>
  </span></div></td>
    </tr>
  </tbody>
</table>


