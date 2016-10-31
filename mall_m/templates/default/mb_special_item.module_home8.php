<?php defined('InSystem') or exit('Access Invalid!'); ?>

<?php if($v['home8'] && is_array($v['home8'])) { ?>
    
    <section class="activity-box">

        <div class="activity-main">

            <div class="tshh">

                <div class="tshh-box">

                    <div class="tshh-item">

                        <div class="item-one item">

                            <div class="pro">
                                <a href="<?php echo $v['home8']['rectangle1_type'] ? buildSpecialUrl($v['home8']['rectangle1_type'], $v['home8']['rectangle1_data']) : 'javascript:void(0);'; ?>">

                                    <img class="pro" src="<?php echo $v['home8']['rectangle1_image']; ?>">

                                </a>
                            </div>

                        </div>

                        <div class="item-two item">

                            <div class="pro">

                                <a href="<?php echo $v['home8']['rectangle3_type'] ? buildSpecialUrl($v['home8']['rectangle3_type'], $v['home8']['rectangle3_data']) : 'javascript:void(0);'; ?>">

                                    <img class="pro" src="<?php echo $v['home8']['rectangle3_image']; ?>">

                                </a>

                            </div>

                        </div>

                        <div class="item-three item">

                            <div class="pro">

                                <a href="<?php echo $v['home8']['rectangle5_type'] ? buildSpecialUrl($v['home8']['rectangle5_type'], $v['home8']['rectangle5_data']) : 'javascript:void(0);'; ?>">

                                    <img class="pro" src="<?php echo $v['home8']['rectangle5_image']; ?>">

                                </a>

                            </div>

                        </div>

                    </div>

                    <div class="tshh-item">

                        <div class="item-one item">

                            <div class="pro">

                                <a href="<?php echo $v['home8']['rectangle2_type'] ? buildSpecialUrl($v['home8']['rectangle2_type'], $v['home8']['rectangle2_data']) : 'javascript:void(0);'; ?>">

                                    <img class="pro" src="<?php echo $v['home8']['rectangle2_image']; ?>">

                                </a>

                            </div>

                        </div>

                        <div class="item-two item">


                            <div class="pro">

                                <a href="<?php echo $v['home8']['rectangle4_type'] ? buildSpecialUrl($v['home8']['rectangle4_type'], $v['home8']['rectangle4_data']) : 'javascript:void(0);'; ?>">

                                    <img class="pro" src="<?php echo $v['home8']['rectangle4_image']; ?>">

                                </a>

                            </div>

                        </div>

                        <div class="item-three item">


                            <div class="pro">

                                <a href="<?php echo $v['home8']['rectangle6_type'] ? buildSpecialUrl($v['home8']['rectangle6_type'], $v['home8']['rectangle6_data']) : 'javascript:void(0);'; ?>">

                                    <img class="pro" src="<?php echo $v['home8']['rectangle6_image']; ?>">

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>
<?php } ?>