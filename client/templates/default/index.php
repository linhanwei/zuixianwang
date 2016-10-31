
<form method="post" action="index.php" id="form1">
    <?php include_once('layout/header.php');?>

    <div>


        <div class="md1" style=" border:1px solid gray; height:270px;">
            <div style="width:599px; height:270px; float:left;  border-right: 1px solid #555; ">
                <div style="width:100%; height:100px; border-bottom:1px solid gray;">
                    <div style=" height:45px;">
                        <span class="sp1">账户余额：</span>
                    </div>
                    <div >
                        <div style=" float:left; width:220px;">
                            <span  class="sp2"><?php echo $member_info['available_predeposit'];?>元</span>
                        </div>
                        <div style="float:left; margin-left:200px;">

                            <a class="btn btn-primary" href="index.php?act=predeposit&op=recharge" role="button">充值</a>
                            <a class="btn btn-primary" href="index.php?act=predeposit&op=cash" role="button">提现</a>
                            <a class="btn btn-primary" href="index.php?act=predeposit&op=transfer" role="button">转帐</a>
                        </div>
                    </div>
                </div>
                <div style="width:100%; height:100px; border-bottom:1px solid gray;">
                    <div>
                        <span class="sp1">会员积分：</span>
                        <span class="sp2"><?php echo $member_info['member_points'];?></span>
                    </div>
                    <div>
                        <span class="sp1">奖励：</span>
                        <span class="sp2"><?php echo $member_info['member_points_inviter'];?></span>
                    </div>
                    <div>
                        <span class="sp1">前一天返现金额：</span>
                        <span class="sp2"><?php echo $member_info['yestoday_redeemable'];?> 元</span>
                    </div>

                </div>
                <div>
                    <div class="sp1">
                        <?php if($member_info['grade_id'] == '0'){?>
                        <input type="button" value="会员升级" class="fcolor round btn1 wd1"  />
                        <?php }?>
                        <?php if($member_info['is_store'] == '0'){?>
                        <input type="button" value="成为商户" class="fcolor round btn1 wd1" />
                        <?php }?>
                        <input type="button" value="我的推荐" class="fcolor round btn1 wd1" />
                        <input type="button" value="交易记录" class="fcolor round btn1 wd1" />
                        <input type="button" value="提现记录" class="fcolor round btn1 wd1" />
                    </div>
                </div>
            </div>
            <div style="width:599px; height:270px;float:left;">
                <table style=" margin-top:30px;">
                    <tr>
                        <td  class="td1">
                            <a>
                                <img alt="转账" src="<?php echo CLIENT_TEMPLATES_URL;?>/images/zzfk.png" class="imgwd" />
                                <span  class="bk">转账</span>

                            </a>
                        </td>
                        <td class="td1">
                            <a>
                                <img src="<?php echo CLIENT_TEMPLATES_URL;?>/images/tx.png" class="imgwd" />
                                <span class="bk">提现</span>

                            </a>
                        </td>
                        <td class="td1">
                            <a>
                                <img src="<?php echo CLIENT_TEMPLATES_URL;?>/images/ye.png" class="imgwd" />
                                <span class="bk">消息</span>

                            </a>
                        </td>
                    </tr>
                    <tr >
                        <td class="td1 td2" >
                            <a>
                                <img src="<?php echo CLIENT_TEMPLATES_URL;?>/images/zd.png" class="imgwd" />
                                <span class="bk">账单</span>

                            </a>
                        </td>
                        <td class="td1 td2">
                            <a>
                                <img src="<?php echo CLIENT_TEMPLATES_URL;?>/images/set.png" class="imgwd" />
                                <span class="bk">设置</span>

                            </a>
                        </td>
                        <td class="td1 td2">
                            <a href="index.php?act=predeposit&op=my_bank_list">
                                <img src="<?php echo CLIENT_TEMPLATES_URL;?>/images/bank.png" class="imgwd" />
                                <span class="bk">银行卡</span>

                            </a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div style="margin-top:10px;width: 99%;margin-left: 10px;maring-right:10px;">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#predeposit" aria-controls="home" role="tab" data-toggle="tab">余额</a></li>
                <li role="presentation"><a href="#points" aria-controls="profile" role="tab" data-toggle="tab">会员积分</a></li>
                <li role="presentation"><a href="#points_inviter" aria-controls="messages" role="tab" data-toggle="tab">奖励</a></li>
                </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="predeposit">
                    <table class="table table-hover">
                        <thead></thead>
                    </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="points">...</div>
                <div role="tabpanel" class="tab-pane" id="points_inviter">...</div>
            </div>

        </div>
    </div>
</form>
    
