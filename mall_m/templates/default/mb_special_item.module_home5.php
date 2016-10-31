<?php defined('InSystem') or exit('Access Invalid!'); ?>

<?php if($v['home5']['item'] && is_array($v['home5']['item'])) { ?>
    <section class="home-chanle-box">

        <div class="home-chanle-list">
            <?php foreach ((array) $v['home5']['item'] as $item) { ?>
                <a href="<?php echo $item['type'] ? buildSpecialUrl($item['type'], $item['data']) : 'javascript:void(0);'; ?>">
                    <img src="<?php echo $item['image']; ?>" />
                </a>
            <?php } ?>
        </div>

    </section>
<?php } ?>