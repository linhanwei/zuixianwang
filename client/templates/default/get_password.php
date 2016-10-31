<?php defined('InSystem') or exit('Access Invalid!');?>
<form method="post" action="#" id="form1">
    <div>
        <input type="hidden" name="referurl" id="referurl" />
        <?php Security::getToken();?>
        <input type="hidden" name="form_submit" value="ok" />
        <input name="hash" type="hidden" value="<?php echo getUrlhash();?>" />

        <div id="findPassword">

            <table style="padding:10px;width: 100%;" cellspacing="5" cellpadding="5">
                <tr>
                    <td height="50" width="80" align="right">
                        手机号：
                    </td>
                    <td>
                        <input name="txt_mobile_findPass" type="text" id="mobile_phone" class="form-control"  style="width:98%;"/>
                    </td>
                </tr>
                <tr>
                    <td height="50" width="80" align="right">
                        密码类型：
                    </td>
                    <td>
                        <input type="radio" name="password_type" value="getpasswd"  <?php if($output['pass_type'] == 'getpasswd') echo 'checked="checked"';?> /><label for="rbtn_loginPass">&nbsp;登录密码</label>
                        <input type="radio" name="password_type" value="getpaywd" <?php if($output['pass_type'] == 'getpaywd') echo 'checked="checked"';?>/><label for="rbtn_payPass">&nbsp;支付密码</label>
                    </td>
                </tr>
                <tr>
                    <td height="50" width="80" align="right">
                        验证码：
                    </td>
                    <td>
                        <table>
                            <tr>
                                <td width="218"><input name="identifying_code" type="text" id="identifying_code" class="form-control" style="width:213px;" /></td>
                                <td>

                                    <button type="button" class="btn btn-warning" id="btn_getValidateCode_findPass">获取验证码</button>
                                    </td>
                            </tr>
                        </table>


                    </td>
                </tr>
                <tr >
                    <td colspan="2" align="center" style=" padding-top:25px;">

                        <button type="button" class="btn btn-danger" id="btn_findPass" disabled>发送密码到绑定手机号码</button>

                    </td>
                </tr>
            </table>
        </div>



    </div>
</form>


<script type="text/javascript" src="<?php echo RESOURCE_CLIENT_URL;?>/js/get_password.js"></script>