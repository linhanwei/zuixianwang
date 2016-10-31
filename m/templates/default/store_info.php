<?php defined('InSystem') or exit('Access Invalid!');?>

<script src="templates/default/js/app.js"></script>
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
    .am-thumbnails>li{padding:3px;}
    .am-thumbnails{margin-right:0rem;}
</style>
<div style="max-width: 640px;
        margin-bottom: 5.5rem;">
    <?php if($output['store_info']['banner_1']){?>
    <div class="am-slider am-slider-default" data-am-flexslider id="store-slider">
        <ul class="am-slides">
            <?php if($output['store_info']['banner_1']){?>
                <li><img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_STORE_JOININ.'/'.$output['store_info']['banner_1'];?>" /></li>
            <?php }?>
            <?php if($output['store_info']['banner_2']){?>
                <li><img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_STORE_JOININ.'/'.$output['store_info']['banner_2'];?>" /></li>
            <?php }?>
            <?php if($output['store_info']['banner_3']){?>
                <li><img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_STORE_JOININ.'/'.$output['store_info']['banner_3'];?>" /></li>
            <?php }?>
            <?php if($output['store_info']['banner_4']){?>
                <li><img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_STORE_JOININ.'/'.$output['store_info']['banner_4'];?>" /></li>
            <?php }?>
            <?php if($output['store_info']['banner_5']){?>
                <li><img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_STORE_JOININ.'/'.$output['store_info']['banner_5'];?>" /></li>
            <?php }?>
        </ul>
    </div>
    <?php }?>
    <ul class="am-avg-sm-1 am-thumbnails" style="text-align: center;background-color:#FFFFFF;">
        <li><?php echo $output['store_info']['store_name'];?></li>
        <li><span style="color:#a3a3a3;"><?php echo $output['store_info']['store_address'];?></span></li>
    </ul>
    <ul class="am-avg-sm-2 am-thumbnails" style="text-align: center;background-color:#FFFFFF;">
        <li><i class="icon iconfont" style="font-size: 48px;" onclick="navigation('<?php echo $output['store_info']['store_address'];?>')">&#xe60d;</i>
            </li>
        <li><i class="icon iconfont" style="font-size: 48px;" onclick="phoneCall(<?php echo $output['store_info']['store_phone'] ? $output['store_info']['store_phone']:$output['store_info']['member_name'];?>)">&#xe665;</i>
            </li>
    </ul>
    <div style="padding:10px;">
        <div class="header">商家介绍</div>
        <div style="background-color:#eeeeee;"">
        <div style="color: #6d6d6d;"><?php echo htmlspecialchars_decode($output['store_info']['store_content']);?></div>
    </div>

</div>
</div>
