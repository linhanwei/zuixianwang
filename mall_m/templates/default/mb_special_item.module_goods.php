<?php defined('InSystem') or exit('Access Invalid!'); ?>

<?php if($v['goods']['item'] && is_array($v['goods']['item'])) { ?>
    <section class="prolist-box">
        <?php foreach ((array) $v['goods']['item'] as $item) { ?>
            <a href="<?php echo $item['goods_id'] ? buildSpecialUrl('goods', $item['goods_id']) : 'javascript:void(0);'; ?>" class="prolist-item">
                <div class="prolist-item-inline">
                    <div class="img">
                        <img src="<?php echo $item['goods_image']; ?>">
                    </div>
                    <div class="info">
                        <h2><?php echo $item['goods_name']; ?></h2>
                        <p>￥<?php echo $item['goods_promotion_price']; ?></p>
                    </div>
                </div>
            </a>
        <?php } ?>
    </section>
<?php } ?>

