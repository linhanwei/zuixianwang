<?php defined('InSystem') or exit('Access Invalid!');?>
<div class="eject_con">
  <div id="warning"></div>
  <form method="post" id="order_state_form" onsubmit="ajaxpost('order_state_form', '', '', 'onerror');return false;" action="index.php?act=store_appointment&op=change_state&state=<?php echo $output['state']?>&order_id=<?php echo $output['order_id']; ?>">
    <input type="hidden" name="form_submit" value="ok" />
    <dl>
      <dt><?php echo '预约单号'.$lang['nc_colon'];?></dt>
      <dd><span class="num"><?php echo trim($_GET['order_id']); ?></span></dd>
    </dl>
    <?php 
    if($_GET['state'] == 'sn'){?>
    <dl>
      <dt><?php echo '商品订单号'.$lang['nc_colon'];?></dt>
      <dd>
         <input name="goods_order_sn" id="goods_order_sn" class="text w200" type="text" value="<?php echo $_GET['goods_order_sn'];?>"/>
      </dd>
    </dl>
    <?php }?>
    <dl>
      <dt><?php echo '备注'.$lang['nc_colon'];?></dt>
      <dd>
          <textarea name="remark" rows="2"  style="width:200px;"></textarea>
      </dd>
    </dl>
    <dl class="bottom">
      <dt>&nbsp;</dt>
      <dd>
        <input type="submit" class="submit" id="confirm_button" value="<?php echo $lang['nc_ok'];?>" />
      </dd>
    </dl>
  </form>
</div>
<?php 
    if($_GET['state'] == 'sn'){?>
<script type="text/javascript">
$(function(){
    $('#order_state_form').validate({
        errorLabelContainer: $('#warning'),
        invalidHandler: function(form, validator) {
               $('#warning').show();
        },
    	submitHandler:function(form){
    		ajaxpost('order_state_form', '', '', 'onerror') 
    	},
        rules : {
            sac_name : {
                required : true
            },
            sac_sort : {
                number   : true
            }
        },
        messages : {
        	goods_order_sn : {
                required : '<i class="icon-exclamation-sign"></i>商品订单号不能为空'

            }
        }
    });
});
</script>   <?php }?>