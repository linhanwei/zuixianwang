<?php defined('InSystem') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
  <?php 
  	if($output['store_appointment'] == 0 || $output['store_appointment'] == 2){
  ?>
  <a href="index.php?act=store_appointment_audit&op=index&state=1" class="ncsc-btn ncsc-btn-green" onclick="if(confirm('确认申请预约功能')) return true; else return false;">
  <?php if($output['store_appointment'] == 2){
  		echo '重新申请';
  	}else{
  		echo '申请预约';
  	}?>

  </a></div>
<?php }?>

<table class="ncsc-default-table">
  <tbody>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><span>
      <?php switch($output['store_appointment']){
      	case 0:
      		echo '还没有申请预约功能，点击【申请预约即可进行预约】'	;
      		break;
      	case 1:
      		echo '申请审核中...';
      		break;
      	case 2:
      		echo '申请不通过，请联系客服后重新申请';
      		break;
      	default:
      		echo '申请通过，已可以使用预约功能';
      }?>
  </span></div></td>
    </tr>
  </tbody>
</table>


