<?php defined('InSystem') or exit('Access Invalid!'); ?>

<?php if($v['adv_list']['item'] && is_array($v['adv_list']['item'])) { ?>
    <section class="banner-box">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php foreach ((array) $v['adv_list']['item'] as $item) { ?>
                    <div class="swiper-slide">
                        <a href="<?php echo $item['type'] ? buildSpecialUrl($item['type'], $item['data']) : 'javascript:void(0);'; ?>">
                            <img src="<?php echo $item['image']; ?>" />
                        </a>
                    </div>
                <?php } ?>
            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
            <!-- Add Arrows
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>-->
        </div>
    </section>
<?php } ?>
