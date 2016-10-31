<?php defined('InSystem') or exit('Access Invalid!');?>
<div class="waitLoading" id="loadingId"></div>
<form method="post" action="#" id="form1" class="form-inline">
    <div id="register" >
        <input type="hidden" name="hid_key" id="hid_key" />
        <?php Security::getToken();?>
        <input name="hash" type="hidden" value="<?php echo getUrlhash();?>" />
        <table style="padding:10px;width: 100%;" cellspacing="5" cellpadding="5">
            <tr>
                <td height="50" width="80" align="right">
                    手机号码：
                </td>
                <td>
                    <input name="txt_mobile" type="text" id="phoneNumId" class="form-control" style="width:98%;"/>
                </td>
            </tr>
            <tr>
                <td height="50" width="80" align="right">
                    验证码：
                </td>
                <td>

<table>
    <tr><td width="217">
            <input name="txt_validateCode" class="form-control" style="width: 200px;" type="text" id="verifNumId"  style="width:98%;"/>
        </td>
    <td>

        <button id="btn_getValidateCode"  type="button" class="btn btn-warning">获取验证码</button>
    </td></tr>
</table>


                </td>
            </tr>
            <tr>
                <td height="50" width="80" align="right">
                    推荐人：
                </td>
                <td>
                    <input name="txt_invitationCode" type="text" id="txt_invitationCode" class="form-control" placeholder="填写推荐人用户名，没有可不填" style="width:98%;"/>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center" style="padding-top:20px;">
                    注册即表示同意 <a href="javascript:void(0);" id="protocol">《车族宝用户协议》</a>
                </td>

            </tr>
            <tr>
                <td align="center" colspan="2" style="padding-top:20px;">
                    <button type="button" class="btn btn-danger" id="btn_register" disabled>确定注册</button>
                </td>
            </tr>
        </table>
    </div>
</form>



<script type="text/javascript" src="<?php echo RESOURCE_CLIENT_URL;?>/js/register.js"></script>