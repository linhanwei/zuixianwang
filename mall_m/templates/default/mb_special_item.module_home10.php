<?php defined('InSystem') or exit('Access Invalid!'); ?>

<?php if($v['home10'] && is_array($v['home10'])) { ?>

    <section class="cuxiao">

        <div class="cybg-box cuxiao-item">

            <div class="cuxiao-list-box">

                <div class="item">

                    <a href="<?php echo $v['home10']['rectangle1_type'] ? buildSpecialUrl($v['home10']['rectangle1_type'], $v['home10']['rectangle1_data']) : 'javascript:void(0);'; ?>">

                        <img  src="<?php echo $v['home10']['rectangle1_image']; ?>">

                    </a>

                </div>

                <div class="item">

                    <a href="<?php echo $v['home10']['rectangle3_type'] ? buildSpecialUrl($v['home10']['rectangle3_type'], $v['home10']['rectangle3_data']) : 'javascript:void(0);'; ?>">

                        <img  src="<?php echo $v['home10']['rectangle3_image']; ?>">

                    </a>

                </div>

                <div class="item">

                    <a href="<?php echo $v['home10']['rectangle5_type'] ? buildSpecialUrl($v['home10']['rectangle5_type'], $v['home10']['rectangle5_data']) : 'javascript:void(0);'; ?>">

                        <img  src="<?php echo $v['home10']['rectangle5_image']; ?>">

                    </a>

                </div>

                <div class="item">

                    <a href="<?php echo $v['home10']['rectangle7_type'] ? buildSpecialUrl($v['home10']['rectangle7_type'], $v['home10']['rectangle7_data']) : 'javascript:void(0);'; ?>">

                        <img  src="<?php echo $v['home10']['rectangle7_image']; ?>">

                    </a>

                </div>

                <div class="item">

                    <a href="<?php echo $v['home10']['rectangle2_type'] ? buildSpecialUrl($v['home10']['rectangle2_type'], $v['home10']['rectangle2_data']) : 'javascript:void(0);'; ?>">

                        <img  src="<?php echo $v['home10']['rectangle2_image']; ?>">

                    </a>

                </div>

                <div class="item">

                    <a href="<?php echo $v['home10']['rectangle4_type'] ? buildSpecialUrl($v['home10']['rectangle4_type'], $v['home10']['rectangle4_data']) : 'javascript:void(0);'; ?>">

                        <img  src="<?php echo $v['home10']['rectangle4_image']; ?>">

                    </a>
                </div>

                <div class="item">

                    <a href="<?php echo $v['home10']['rectangle6_type'] ? buildSpecialUrl($v['home10']['rectangle6_type'], $v['home10']['rectangle6_data']) : 'javascript:void(0);'; ?>">

                        <img  src="<?php echo $v['home10']['rectangle6_image']; ?>">

                    </a>

                </div>

                <div class="item">

                    <a href="<?php echo $v['home10']['rectangle8_type'] ? buildSpecialUrl($v['home10']['rectangle8_type'], $v['home10']['rectangle8_data']) : 'javascript:void(0);'; ?>">

                        <img  src="<?php echo $v['home10']['rectangle8_image']; ?>">

                    </a>

                </div>

            </div>

        </div>

    </section>

<?php } ?>