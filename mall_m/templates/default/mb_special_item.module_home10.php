<?php defined('InSystem') or exit('Access Invalid!'); ?>

<?php if($v['home10']['item'] && is_array($v['home10']['item'])) { ?>

    <section class="cuxiao">

        <div class="cybg-box cuxiao-item">

            <div class="cuxiao-list-box">
                <?php foreach ((array) $v['home10']['item'] as $item) { ?>
                    <div class="item">

                        <a href="<?php echo $item['type'] ? buildSpecialUrl($item['type'], $item['data']) : 'javascript:void(0);'; ?>">

                            <img  src="<?php echo $item['image']; ?>">

                        </a>

                    </div>
                <?php } ?>

            </div>

        </div>

    </section>

<?php } ?>