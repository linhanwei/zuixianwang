<?php defined('InSystem') or exit('Access Invalid!');?>
<form method="post" action="#" id="form1">
    <div>
        <input type="hidden" name="referurl" id="referurl" />
        <input type="hidden" name="t" id="t" value="<?php echo $output['t'];?>"/>
        <input type="hidden" name="form_submit" value="ok" />
        <input type="hidden" name="token" id="token" value="<?php echo $output['token'];?>"/>
        <img src="<?php echo CLIENT_TEMPLATES_URL;?>/images/loginbk.jpg" alt=""  style="width:100%;height:100%;display:block;position:absolute;top:0px;left:0px;z-index:-999;" height="100%"  id="__baseID___image2">
        <img src="<?php echo CLIENT_TEMPLATES_URL;?>/images/logobk.png" alt=""  style="display:block;position:absolute;left:50px;top:50px;"   id="__baseID___image1">

        <div  style="  height:310px; width:370px;margin-top:150px;margin-right:100px;float:right;background-color:#5d6b88;position:static;padding:10px 10px 10px 10px;-moz-border-radius: 15px;      /* Gecko browsers */
    -webkit-border-radius: 15px;   /* Webkit browsers */
    border-radius:15px;            /* W3C syntax */">

            <span style="font-size:x-large;color:#FFFFFF;font-family:宋体;width:100%;text-align:center;display:block;font-weight:bold;margin-top:10px;margin-bottom:15px;">登录车族宝</span>
            <div>
                <img src="<?php echo CLIENT_TEMPLATES_URL;?>/images/l1.png" alt="" height="50px" style="width:50px; float:left;"   >
                <input name="txt_userName" type="text" id="txt_userName" placeholder="请输入账号或手机号" style="border-style:none none none none;height:50px;width:77%;padding:10px 10px 10px 10px;" />

            </div>
            <div style="margin-top:20px;">
                <img src="<?php echo CLIENT_TEMPLATES_URL;?>/images/l2.png" alt="" height="50px" style="width:50px; float:left;"   >
                <input name="txt_password" type="password" id="txt_password" placeholder="请输入登录密码" style="border-style:none none none none;height:50px;width:77%;padding:10px 10px 10px 10px;" />
                <a id="forgetPass" style="display:block; margin-left:260px; margin-top:10px; color:White;text-decoration: none; font-size:small;" href="javascript:void(0)" pass_type="getpasswd">忘记密码</a>

            </div>
            <div style="margin-top:30px;text-align:center;">
                <button type="button" id="btn_login" name="btn_login" class="btn btn-danger" style="width:100px;margin-right: 10px;">登录</button>
                <button type="button" id="btn_register" name="btn_register" class="btn btn-danger" style="width:100px;">注册</button>
            </div>

        </div>
    </div>
</form>


<script type="text/javascript" src="<?php echo RESOURCE_CLIENT_URL;?>/js/login.js"></script>