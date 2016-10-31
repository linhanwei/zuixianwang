<?php defined('InSystem') or exit('Access Invalid!'); ?>

<?php if ($v['home1']['image']) { ?>
    <section class="banner-box">
        <div class="title">
            <a href="<?php echo $v['home1']['type'] ? buildSpecialUrl($v['home1']['type'], $v['home1']['data']) : 'javascript:void(0);'; ?>">
                <img src="<?php echo $v['home1']['image']; ?>" />
            </a>
        </div>
    </section>
<?php } ?>