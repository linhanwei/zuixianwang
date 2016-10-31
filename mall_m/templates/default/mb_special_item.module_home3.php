<?php defined('InSystem') or exit('Access Invalid!'); ?>

<?php if ($v['home3']['item'] && is_array($v['home3']['item'])) { ?>
    <section class="daily-selection">
<!--        <div class="title"><img src="./templates/default/images/daily-selection.jpg"></div>-->
        <div class="daily-selection-box">
            <?php foreach ((array)$v['home3']['item'] as $item) { ?>
                <a href="<?php echo $item['type'] ? buildSpecialUrl($item['type'], $item['data']) : 'javascript:void(0);'; ?>" class="item">
                    <img src="<?php echo $item['image']; ?>">
                </a>
            <?php } ?>
        </div>
    </section>
<?php } ?>
