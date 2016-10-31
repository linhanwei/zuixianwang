<?php defined('InSystem') or exit('Access Invalid!');?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><?php echo $lang['nc_operation_set']?></h3>
            <?php echo $output['top_link'];?>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form method="post" name="settingForm" id="settingForm">
        <input type="hidden" name="form_submit" value="ok" />
        <table class="table tb-type2">
            <tbody>

            <tr>
                <td class="" colspan="2"><table class="table tb-type2 nomargin">
                        <thead>
                        <tr class="space">
                            <th colspan="16"><?php echo $lang['points_ruletip']; ?>:</th>
                        </tr>
                        <tr class="thead">
                            <th><?php echo $lang['points_item']; ?></th>
                            <th><?php echo $lang['points_number']; ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="hover">
                            <td class="w200"><?php echo $lang['points_number_reg']; ?></td>
                            <td><input id="points_reg" name="points_reg" value="<?php echo $output['list_setting']['points_reg'];?>" class="txt" type="text" style="width:60px;"></td>
                        </tr>
                        <tr class="hover">
                            <td><?php echo $lang['points_number_login'];?></td>
                            <td><input id="points_login" name="points_login" value="<?php echo $output['list_setting']['points_login'];?>" class="txt" type="text" style="width:60px;"></td>
                        </tr>

                        <tr class="hover">
                            <td>邀请注册</td>
                            <td><input id="points_comments" name="points_invite" value="<?php echo $output['list_setting']['points_invite'];?>" class="txt" type="text" style="width:60px;">邀请非会员注册时给邀请人的会员积分数</td>
                        </tr>
                        <tr class="hover" style="display: none;">
                            <td>返利比例</td>
                            <td><input id="points_rebate" name="points_rebate" value="<?php echo $output['list_setting']['points_rebate'];?>" class="txt" type="text" style="width:35px;">% &nbsp;&nbsp;&nbsp;被邀请会员转帐至商户时给邀请人返的会员积分数(例如设为5%，被邀请人购买100元商品，返给邀请人5会员积分)</td>
                        </tr>
                        <tr class="hover" style="display: none;">
                            <td>用户消费比例(商户:85%,平台:15%)</td>
                            <td><input id="points_sale" name="points_sale" value="<?php echo $output['list_setting']['points_sale'];?>" class="txt" type="text" style="width:35px;">% &nbsp;&nbsp;&nbsp;每单转帐扣除商户比例</td>
                        </tr>
                        <tr class="hover" style="display: none;">
                            <td>每天返还比例(万分比)</td>
                            <td><input id="points_remission" name="points_remission" value="<?php echo $output['list_setting']['points_remission'];?>" class="txt" type="text" style="width:35px;"> &nbsp;&nbsp;&nbsp;每天返还现金比例</td>
                        </tr>
                        <tr class="hover" style="display: none;">
                            <td>单次转帐限制</td>
                            <td><input id="transfer_limit" name="transfer_limit" value="<?php echo $output['list_setting']['transfer_limit'];?>" class="txt" type="text" style="width:35px;">% &nbsp;&nbsp;&nbsp;单次转帐限制金额</td>
                        </tr>
                        <tr class="hover" style="display: none;">
                            <td>提现手续费</td>
                            <td><input id="cash_poundage" name="cash_poundage" value="<?php echo $output['list_setting']['cash_poundage'];?>" class="txt" type="text" style="width:35px;">% &nbsp;&nbsp;&nbsp;提现手续费</td>
                        </tr>
                        </tbody>
                    </table>

                    <tfoot>
            <tr class="tfoot">
                <td colspan="2" ><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span><?php echo $lang['nc_submit'];?></span></a></td>
            </tr>
            </tfoot>
        </table>
    </form>
</div>
<script>

    $(function(){$("#submitBtn").click(function(){
        if($("#settingForm").valid()){
            $("#settingForm").submit();
        }
    });
    });
    //
    $(document).ready(function(){
        $("#settingForm").validate({
            errorPlacement: function(error, element){
                error.appendTo(element.parent().parent().prev().find('td:first'));
            },
            rules : {
            },
            messages : {
            }
        });
    });
</script>
