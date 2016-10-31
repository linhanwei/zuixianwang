<?php defined('InSystem') or exit('Access Invalid!');?>

<div class="bg-dot"></div>
<div class="login-layout">
    <div class="top">
        <h5><?php echo $lang['login_index_title_01'];?><em></em></h5>
        <h2><?php echo $lang['login_index_title_02'];?></h2>
        <h6><?php echo $lang['login_index_title_03'];?></h6>
    </div>
    <div class="box">
        <form method="post" id="form_login">
            <?php Security::getToken();?>
            <input type="hidden" name="form_submit" value="ok" />
            <input type="hidden" name="SiteUrl" id="SiteUrl" value="<?php echo MALL_SITE_URL;?>" />
      <span>
      <label><?php echo $lang['login_index_username'];?></label>
      <input name="user_name" id="user_name" autocomplete="off" type="text" class="input-text" required>
      </span> <span>
      <label><?php echo $lang['login_index_password'];?></label>
      <input name="password" id="password" class="input-password" autocomplete="off" type="password" required pattern="[\S]{6}[\S]*"
             title="<?php echo $lang['login_index_password_pattern'];?>">
      </span> <span>
      <div class="code">
          <div class="arrow"></div>
          <div class="code-img"><img src="index.php?act=seccode&op=makecode&admin=1&hash=<?php echo getUrlhash();?>" name="codeimage" id="codeimage" border="0"/></div>
          <a href="JavaScript:void(0);" id="hide" class="close" title="<?php echo $lang['login_index_close_checkcode'];?>"><i></i></a><a href="JavaScript:void(0);" onclick="javascript:document.getElementById('codeimage').src='index.php?act=seccode&op=makecode&admin=1&hash=<?php echo getUrlhash();?>&t=' + Math.random();" class="change" title="<?php echo $lang['login_index_change_checkcode'];?>"><i></i></a> </div>
      <input name="captcha" type="text" required class="input-code" id="captcha" placeholder="<?php echo $lang['login_index_checkcode'];?>" pattern="[A-z0-9]{4}" title="<?php echo $lang['login_index_checkcode_pattern'];?>" autocomplete="off" value="" >
      </span> <span>
      <input name="hash" type="hidden" value="<?php echo getUrlhash();?>" />
      <input name="" class="input-button" type="submit" value="<?php echo $lang['login_index_button_login'];?>">
      </span>
        </form>
    </div>
</div>
<div class="bottom">
    <h5>Powered by 车族宝</h5>
</div>
