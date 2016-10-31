<?php defined('InSystem') or exit('Access Invalid!');?>

<style type="text/css">
    html,
    body {
        -webkit-overflow-scrolling: touch;
        overflow: auto;
    }

    .am-accordion-default .am-accordion-title{
        padding:.3rem .8rem;
    }
    .am-list-news-default .am-list .am-list-item-desced{
        padding-top: .5rem;
        padding-bottom: .3rem;
    }
</style>
<div class="am-panel am-panel-default" style="margin: 5px;font-size:.6rem;">
    <div class="am-panel-hd"><?php echo $output['article']['article_title'];?></div>
    <div class="am-panel-bd"><?php echo $output['article']['article_content'];?></div>
</div>