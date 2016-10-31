<?php defined('InSystem') or exit('Access Invalid!'); ?>
<section class="chanle-list">
    <div class="cate-p">
        <?php if($output['info']['special_image']){?>
            <img class="chanle-ico" src="<?php echo $output['info']['special_image'];?>">
        <?php } ?>
        <label><?php echo $output['info']['special_desc'];?></label>
    </div>
    <div class="cate-child-box" data-index="1">
        <div class="cate-child-v">
            <?php
            foreach ((array) $output['mb_list'] as $mk=>$mv) {
                if($_GET['sp_id'] != $mv['special_id']){
                    ?>
                    <a href="<?php echo BASE_SITE_URL; ?>/mall_m/index.php?act=mb_special&op=index&sp_id=<?php echo $mv['special_id'];?>"><?php echo $mv['special_desc'];?></a>
                <?php } } ?>
        </div>
    </div>
    <a class="more"><img src="./templates/default/images/ico-rigth.png"></a>
</section>