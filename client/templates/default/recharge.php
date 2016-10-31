
<body style=" margin:0px;width:100%">
<?php include_once('layout/header.php');?>

<div>
    <ol class="breadcrumb">
        <li><a href="index.php">首页</a></li>
        <li class="active">充值</li>
    </ol>
    <div class="panel  panel-default panel-center">
        <div class="panel-heading">充值</div>
        <div class="panel-body">
            <form id="recharge_form" class="am-form transfer-form" action=""  method="post" target="_blank">
                <input type="hidden" name="form_submit" value="ok">
                <input type="hidden" name="act" value="predeposit">
                <input type="hidden" name="op" value="recharge_add">

                <input type="hidden" id="member_key" name="key" value="">

                <div class="form-group">
                    <label for="pdr_amount">金额</label>
                    <input type="number" class="form-control" id="pdr_amount" name="pdr_amount" placeholder="请输入充值金额" style="IME-MODE: disabled;" onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')" maxlength="7" size="7">
                    </div>
                <div class="form-group">
                    <label for="payment_code">支付方式</label>
                    <select id="payment_code" name="payment_code" class="form-control">
                        <option value="alipay">支付宝</option>
                    </select>
                </div>

                <button type="button" class="btn btn-danger" id="rechargebtn">确定充值</button>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo RESOURCE_CLIENT_URL;?>/js/recharge.js"></script>
    
