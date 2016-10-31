<?php $member_info = $output['member_info'];?>
<link href="<?php echo CLIENT_TEMPLATES_URL;?>/css/base.css" rel="stylesheet" type="text/css">
<div style="background-color:#C82226;height:165px;width:100%;  ">
    <img alt="logo" src="<?php echo CLIENT_TEMPLATES_URL;?>/images/logobk.png" style="margin-left:80px; margin-top:22px; float:left;" />
    <table style="margin-top:30px; float:left; margin-left:10px;">
        <tr>
            <td class="fcolor">

                <span id="lbl_memberName"><?php echo $member_info['member_truename'] ? $member_info['member_truename'] : '车族宝用户';?></span>
            </td>
        </tr>
        <tr>
            <td class="fcolor">
                手机号码：<span id="lbl_member_mobile"><?php echo $member_info['member_name'];?></span>
            </td>
        </tr>
        <tr>
            <td class="fcolor">

                <span id="lbl_gradeName"><?php echo $member_info['grade_name'];?></span>
            </td>
        </tr>
        <tr>
            <td class="fcolor">
                我的推荐人：<span id="lbl_inviter"><?php echo $member_info['inviter_member']['member_name'] ? $member_info['inviter_member']['member_name'] : '无推荐人';?></span>
            </td>
        </tr>
    </table>

    <div style="display:block;  float:right; margin-right:115px; margin-top:25px; " >
        <a style="color:White;text-decoration: none; font-size:small;" href="javascript:logout();"><span class="fcolor">【安全退出】</span></a>
    </div>
</div>
