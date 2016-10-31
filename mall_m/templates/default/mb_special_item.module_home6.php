<?php defined('InSystem') or exit('Access Invalid!'); ?>

<?php if ($v['home6']['image']) { ?>
    <section class="banner-box">
        <div class="title">
            <a href="<?php echo $v['home6']['type'] ? buildSpecialUrl($v['home6']['type'], $v['home6']['data']) : 'javascript:void(0);'; ?>">
                <img src="<?php echo $v['home6']['image']; ?>" />
            </a>
        </div>
    </section>
<?php } ?>