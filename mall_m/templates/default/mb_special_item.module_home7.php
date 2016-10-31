<?php defined('InSystem') or exit('Access Invalid!'); ?>

<?php if($v['home7'] && is_array($v['home7'])) { ?>

    <section class="activity-box">

        <div class="activity-main">

            <div class="cybg">

                <div class="cybg-box">

                    <div class="cybg-top">

                        <div class="left">

                            <a href="<?php echo $v['home7']['rectangle1_type'] ? buildSpecialUrl($v['home7']['rectangle1_type'], $v['home7']['rectangle1_data']) : 'javascript:void(0);'; ?>">

                                <img class="pro" src="<?php echo $v['home7']['rectangle1_image']; ?>">

                            </a>

                        </div>

                        <div class="right">
                            <a href="<?php echo $v['home7']['rectangle4_type'] ? buildSpecialUrl($v['home7']['rectangle4_type'], $v['home7']['rectangle4_data']) : 'javascript:void(0);'; ?>">

                                <img class="pro" src="<?php echo $v['home7']['rectangle4_image']; ?>">

                            </a>
                        </div>

                    </div>

                    <div class="cybg-bottom">

                        <div class="item">

                            <div class="pro">
                                <a href="<?php echo $v['home7']['rectangle2_type'] ? buildSpecialUrl($v['home7']['rectangle2_type'], $v['home7']['rectangle2_data']) : 'javascript:void(0);'; ?>">

                                    <img class="pro" src="<?php echo $v['home7']['rectangle2_image']; ?>">

                                </a>

                            </div>

                        </div>

                        <div class="item">

                            <div class="pro">
                                <a href="<?php echo $v['home7']['rectangle3_type'] ? buildSpecialUrl($v['home7']['rectangle3_type'], $v['home7']['rectangle3_data']) : 'javascript:void(0);'; ?>">

                                    <img class="pro" src="<?php echo $v['home7']['rectangle3_image']; ?>">

                                </a>

                            </div>

                        </div>

                        <div class="item">

                            <div class="pro">

                                <a href="<?php echo $v['home7']['rectangle5_type'] ? buildSpecialUrl($v['home7']['rectangle5_type'], $v['home7']['rectangle5_data']) : 'javascript:void(0);'; ?>">

                                    <img class="pro" src="<?php echo $v['home7']['rectangle5_image']; ?>">

                                </a>

                            </div>

                        </div>

                        <div class="item">

                            <div class="pro">

                                <a href="<?php echo $v['home7']['rectangle6_type'] ? buildSpecialUrl($v['home7']['rectangle6_type'], $v['home7']['rectangle6_data']) : 'javascript:void(0);'; ?>">

                                    <img class="pro" src="<?php echo $v['home7']['rectangle6_image']; ?>">

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>
<?php } ?>