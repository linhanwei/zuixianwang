<?php defined('InSystem') or exit('Access Invalid!'); ?>

<?php if ($v['home11']['item'] && is_array($v['home11']['item'])) { ?>

    <section class="car-chanle">

        <div class="car-chanle-child">

            <div class="list">

                <?php foreach ((array)$v['home11']['item'] as $item) { ?>

                    <a href="<?php echo $item['type'] ? buildSpecialUrl($item['type'], $item['data']) : 'javascript:void(0);'; ?>" class="item">
                        <img src="<?php echo $item['image']; ?>">
                    </a>

                <?php } ?>

            </div>

        </div>

    </section>
<?php } ?>