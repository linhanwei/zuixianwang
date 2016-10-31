<?php defined('InSystem') or exit('Access Invalid!'); ?>

<?php if($v['home9'] && is_array($v['home9'])) { ?>
    
    <section class="lyt-content">

        <div class="lyt-main">

            <div class="left">
                <a href="<?php echo $v['home9']['rectangle1_type'] ? buildSpecialUrl($v['home9']['rectangle1_type'], $v['home9']['rectangle1_data']) : 'javascript:void(0);'; ?>">

                    <img  src="<?php echo $v['home9']['rectangle1_image']; ?>">

                </a>
            </div>

            <div class="right">

                <div class="top">

                    <a href="<?php echo $v['home9']['rectangle2_type'] ? buildSpecialUrl($v['home9']['rectangle2_type'], $v['home9']['rectangle2_data']) : 'javascript:void(0);'; ?>">

                        <img  src="<?php echo $v['home9']['rectangle2_image']; ?>">

                    </a>

                </div>

                <div class="bottom">

                    <div class="bottom-left">

                        <a href="<?php echo $v['home9']['rectangle3_type'] ? buildSpecialUrl($v['home9']['rectangle3_type'], $v['home9']['rectangle3_data']) : 'javascript:void(0);'; ?>">

                            <img  src="<?php echo $v['home9']['rectangle3_image']; ?>">

                        </a>

                    </div>

                    <div class="bottom-right">

                        <a href="<?php echo $v['home9']['rectangle4_type'] ? buildSpecialUrl($v['home9']['rectangle4_type'], $v['home9']['rectangle4_data']) : 'javascript:void(0);'; ?>">

                            <img  src="<?php echo $v['home9']['rectangle4_image']; ?>">

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </section>
<?php } ?>