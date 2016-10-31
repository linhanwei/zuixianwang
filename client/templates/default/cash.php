
<body style=" margin:0px;width:100%">
<?php include_once('layout/header.php');?>

<div>
    <ol class="breadcrumb">
        <li><a href="index.php">首页</a></li>
        <li class="active">提现</li>
    </ol>
    <div class="panel  panel-default panel-center">
        <div class="panel-heading">提现</div>
        <div class="panel-body">
            <form id="cash_form" class="am-form transfer-form" action=""  method="post" target="_blank">
                <input type="hidden" name="form_submit" value="ok">
                <input type="hidden" name="act" value="predeposit">
                <input type="hidden" name="op" value="recharge_add">

                <input type="hidden" id="member_key" name="key" value="">
                <div class="form-group">
                    <p>可提现金额:<span style="color:red;"><?php echo $member_info['available_predeposit'];?>元</span></p>

                </div>


                <div class="form-group">
                    <label for="pdr_amount">金额</label>
                    <!--<input type="number" class="form-control" id="pdr_amount" name="pdr_amount" placeholder="请输入充值金额" style="IME-MODE: disabled;" onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')" maxlength="7" size="14">-->
                    <input type="number" class="form-control" id="pdr_amount" name="pdr_amount" placeholder="请输入提现金额" maxlength="7" size="14">
                </div>

                <div class="form-group">
                    <label for="pdr_amount">提现卡号</label>
                    <select class="form-control">
                        <?php
                        if($output['my_bank_list']){
                           foreach($output['my_bank_list'] as $bank){
                               echo "<option value='{$bank['id']}'>{$bank['bank_name']}({$bank['account_no']})</option>";
                           }
                        }else{
                            echo "<option value='0'>请先绑定银行卡</option>";
                        }?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="pdr_amount">提现备注</label>
                    <!--<input type="number" class="form-control" id="pdr_amount" name="pdr_amount" placeholder="请输入充值金额" style="IME-MODE: disabled;" onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')" maxlength="7" size="14">-->
                    <input type="number" class="form-control" id="pdc_remark" name="pdc_remark" placeholder="请输入提现备注" maxlength="50" size="50">
                </div>

                <div class="form-group">
                    <label for="pdr_amount">支付密码</label>
                    <!--<input type="number" class="form-control" id="pdr_amount" name="pdr_amount" placeholder="请输入充值金额" style="IME-MODE: disabled;" onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')" maxlength="7" size="14">-->
                    <input type="password" class="form-control" id="paywd" name="paywd" placeholder="" maxlength="14" size="14">
                </div>

                <div class="form-group">
                <button type="button" class="btn btn-danger" id="cash_btn">提现</button>
                    <button type="button" class="btn btn-warning" id="forgetPass" pass_type="getpaywd">忘记支付密码</button>
                </div>
                <div class="form-group">
                    <p>1.代第三方收取3%的手续费，单笔提现至少￥100以上</p>
                    <p>2.1-3个工作日到帐</p>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    var my_bank_list = <?php echo json_encode($output['my_bank_list']);?>;
</script>
<script type="text/javascript" src="<?php echo RESOURCE_CLIENT_URL;?>/js/cash.js"></script>
    
