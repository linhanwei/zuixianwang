<?php defined('InSystem') or exit('Access Invalid!');?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>现金充值</h3>
            <ul class="tab-base">
                <li><a href="JavaScript:void(0);" class="current"><span>现金充值</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form id="points_form" method="post" name="form1">
        <input type="hidden" name="form_submit" value="ok" />
        <table class="table tb-type2 nobdb">
            <tbody>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="validation">会员名称(手机号码):</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><input type="text" name="member_name" id="member_name" class="txt" onchange="javascript:checkmember();">
                    <input type="hidden" name="member_id" id="member_id" value='0'/></td>
                <td class="vatop tips"></td>
            </tr>
            <tr id="tr_memberinfo">
                <td colspan="2" style="font-weight:bold;" id="td_memberinfo"></td>
            </tr>
            <tr>
                <td colspan="2" class="required"><label class="validation">充值金额:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><input type="text" id="pdr_amount" name="pdr_amount" class="txt"></td>
                <td class="vatop tips"></td>
            </tr>
            <tr>
                <td colspan="2" class="required"><label class="validation">第三方支付平台交易号(现金为收据单号):</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><input type="text" id="pdr_trade_sn" name="pdr_trade_sn" class="txt"></td>
                <td class="vatop tips"></td>
            </tr>
            </tbody>
            <tfoot>
            <tr class="tfoot">
                <td colspan="2" ><a href="JavaScript:void(0);" class="btn" onclick="document.form1.submit()"><span><?php echo $lang['nc_submit'];?></span></a></td>
            </tr>
            </tfoot>
        </table>
    </form>
</div>
<script type="text/javascript">
    function checkmember(){
        var membername = $.trim($("#member_name").val());
        if(membername == ''){
            $("#member_id").val('0');
            alert(<?php echo $lang['admin_points_addmembername_error']; ?>);
            return false;
        }
        $.getJSON("index.php?act=predeposit&op=checkmember", {'name':membername}, function(data){
            if (data)
            {
                $("#tr_memberinfo").show();
                var msg= "<?php echo $lang['admin_points_member_tip']; ?> "+ data.name + ",当前账户余额:" + data.available_predeposit;
                $("#member_name").val(data.name);
                $("#member_id").val(data.id);
                $("#td_memberinfo").text(msg);
            }
            else
            {
                $("#member_name").val('');
                $("#member_id").val('0');
                alert("充值账户不存在!");
            }
        });
    }
    $(function(){
        $("#tr_memberinfo").hide();

        $('#points_form').validate({
//        errorPlacement: function(error, element){
//            $(element).next('.field_notice').hide();
//            $(element).after(error);
//        },
            rules : {
                member_name: {
                    required : true
                },
                member_id: {
                    required : true
                },
                pointsnum   : {
                    required : true,
                    min : 1
                },
                pdr_trade_sn : {
                    required : true
                }
            },
            messages : {
                member_name: {
                    required : '<?php echo $lang['admin_points_addmembername_error'];?>'
                },
                member_id : {
                    required : '<?php echo $lang['admin_points_member_error_again'];?>'
                },
                pointsnum  : {
                    required : '<?php echo $lang['admin_points_points_null_error']; ?>',
                    min : '<?php echo $lang['admin_points_points_min_error']; ?>'
                },
                pdr_trade_sn : {
                    required : '请输入第三方支付平台交易号(现金为收据单号)'
                }
            }
        });
    });
</script>