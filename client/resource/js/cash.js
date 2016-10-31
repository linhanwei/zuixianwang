$(function() {
    function check_form(){
        if(my_bank_list.length == 0){
            alert('请先绑定银行卡',function(index){
                document.location.href = 'index.php?act=predeposit&op=bank';
                layer.close(index);
            });
            return;
        }
    }

    check_form();

    $('#cash_btn').click(function () {
        check_form();
        var pdr_amount = $('#pdr_amount').val();
        var payment_code = $('#payment_code').val();

        if (pdr_amount == '' || pdr_amount == 0) {
            alert('请输入充值金额','pdr_amount');
            return false;
        } else {


        }
    });
});