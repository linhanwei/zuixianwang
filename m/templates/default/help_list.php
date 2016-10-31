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
    .am-accordion-default{
        margin:.2rem;
    }
</style>
<section data-am-widget="accordion" class="am-accordion am-accordion-default" data-am-accordion='{  }' style="font-size: 0.6rem;">
   <?php foreach($output['cate_list'] as $val){?>
    <dl class="am-accordion-item ">
        <dt class="am-accordion-title">
            <?php echo $val['ac_name'];?>
        </dt>
        <dd class="am-accordion-bd am-collapse">
                <div data-am-widget="list_news" class="am-list-news am-list-news-default" >
                    <div class="am-list-news-bd">
                        <ul class="am-list">
                            <?php foreach($val['article_list'] as $question){?>
                            <li class="am-g am-list-item-desced">
                                <a href="index.php?act=help&op=content&id=<?php echo $question['article_id'];?>" class="am-list-item-hd "><?php echo $question['article_title'];?></a>

                            </li>
                           <?php }?>
                        </ul>

                </div>
        </dd>
    </dl>
   <?php }?>
</section>
