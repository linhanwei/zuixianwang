<?php defined('InSystem') or exit('Access Invalid!');?>

<style type="text/css">
    html,
    body {
        -webkit-overflow-scrolling: touch;
        overflow: auto;
        font-size: 0.7rem;
        background-color:#eeeeee;
    }
    .header{
        border-left:3px solid #ff7a00;
        padding:0 1em;
        margin-top:.4em;
        margin-bottom:.4em;
    }
    .am-thumbnails>li{padding:15px;}
    .am-thumbnails{margin-right:0rem;}
</style>
<div style="max-width: 640px;
        margin-bottom: 5.5rem;">
    <img src="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_COMMON.DS.$output['fund']['fund_banner']);?>" class="am-img-responsive" alt=""/>

    <ul class="am-avg-sm-2 am-thumbnails" style="text-align: center;background-color:#FFFFFF;">
        <li><span style="color:#ff7a00;"><?php echo $output['fund']['fund_raise'];?></span>
            <br><span style="color:#a3a3a3;">已筹(元)</span></li>
        <li><span style="color:#ff7a00;"><?php echo $output['fund']['fund_love'];?></span>
            <br><span style="color:#a3a3a3;">爱心(份)</span></li>
    </ul>
    <div style="padding:10px;">
        <div class="header">公益详情</div>
        <div style="background-color:#eeeeee;"">
        <div style="color: #6d6d6d;"><?php echo $output['fund']['fund_content'];?></div>
    </div>

    <?php if($output['fund']['fund_to']){?>
        <div class="header">善款接收机构</div>
        <div style="background-color:#eeeeee;">
            <div style="color: #6d6d6d;"><?php echo $output['fund']['fund_to'];?></div>
        </div>
    <?php }?>
</div>
</div>
