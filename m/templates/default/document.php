<?php defined('InSystem') or exit('Access Invalid!');?>

<style type="text/css">
    html,
    body {
        -webkit-overflow-scrolling: touch;
        overflow: auto;
    }
    .am-panel-bd img{
        max-width:100%;
        height:auto;
    }
</style>

<div class="am-panel am-panel-default" style="margin: 5px;font-size:.6rem;">
    <div class="am-panel-bd"><?php echo $output['document']['doc_content'];?></div>
</div>