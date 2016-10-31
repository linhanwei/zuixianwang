<!doctype html>

<html>

<head>

    <meta charset="utf-8">

    <meta http-equiv="x-ua-compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">

    <title>车主商城</title>

    <meta name="keywords" content=""/>

    <meta name="description" content=""/>

    <?php include template('common_js_css'); ?>

    <script src="./templates/default/js/swiper.jquery.min.js"></script>

    <script src="./templates/default/js/goods_detail.js"></script>
    <script>
        var goods_spec_value = [];
        <?php if($output['info']['spec_list_mobile']){ ?>
        <?php foreach($output['info']['spec_list_mobile'] as $gsk=>$gsv){ ?>
        goods_spec_value['<?php echo $gsk;?>'] = <?php echo $gsv;?>;
        <?php }?>
        <?php }?>
    </script>


</head>

<body>

<header class="detail-head">

    <a href="javascript:history.go(-1);" class="go-history"><img
            src="./templates/default/images/detail/detail-ico-1234.png"/></a>

    <a href="javascript:;" class="go-link"><img src="./templates/default/images/detail/detail-ico-1236.png"/></a>

    <a href="<?php echo BASE_SITE_URL; ?>/mall_m/index.php?act=member_cart&op=index" class="go-cart"><img
            src="./templates/default/images/detail/detail-ico-1235.png"/></a>

</header>

<section class="banner-box">

    <div class="swiper-container">

        <div class="swiper-wrapper">

            <?php foreach ($output['info']['goods_image_mobile'] as $image) { ?>

                <div class="swiper-slide">

                    <img src="<?php echo $image; ?>"/>

                </div>

            <?php } ?>

        </div>

        <!-- Add Pagination -->

        <div class="swiper-pagination"></div>

        <!-- Add Arrows

        <div class="swiper-button-next"></div>

        <div class="swiper-button-prev"></div>-->

    </div>

</section>


<section class="detail-info-box">

    <div class="title">

        <div class="title-txt">

            <?php echo $output['info']['goods_info']['goods_name']; ?>

        </div>

        <div class="share-box">

            <a>

                <img src="./templates/default/images/detail/share-ico.png">

                <p>分享</p>

            </a>

        </div>

    </div>

    <div class="price">

        <span><small>￥</small><?php echo $output['info']['goods_info']['goods_promotion_price']; ?></span>

    </div>

    <div class="detail-info-summay">

        <div class="item">

            <span>快递费:<?php echo $output['info']['goods_info']['goods_freight']; ?></span>

        </div>

        <div class="item">

            <span>月销:<?php echo $output['info']['goods_info']['goods_salenum']; ?>笔</span>

        </div>

        <!--<div class="item">

            <span>浙江杭州</span>

        </div>-->

    </div>

</section>


<div class="spec">

    <div class="select" id="spec-select"><span>选择规格</span>

        <a class="right-arrow"><img src="./templates/default/images/detail/ico-5646.png"></a>

    </div>

    <div class="spen-item">


    </div>

</div>

<div class="buy-box">

    <div class="shop-info">

        <a href="javascript:void(0);">

            <img src="./templates/default/images/detail/detail-ico-79.png">

            <p>客服</p>

        </a>

        <a href="javascript:void(0);"><img src="./templates/default/images/detail/detail-ico-146.png">

            <p>店铺</p>

        </a>

        <a class="goods_collect"><img src="./templates/default/images/detail/detail-ico-123.png">

            <p>收藏</p>

        </a>

    </div>

    <div class="buy-btn">

        <a class="add-cart add-to-cart">加入购物车</a>

        <a class="buy-quick buy-now">立即购买</a>

    </div>

</div>

<div class="detail-middle-tips">

    <span class="line"></span>

    <span class="txt">继续拖动，查看图文详情</span>

</div>

<div class="detail-content">

    <ul class="tab-nav">

        <li class="action"><a>详情页面</a></li>

        <li><a>产品参数</a></li>

        <!--         <li><a>店铺推荐</a></li>-->

    </ul>

    <div class="detail-content-box">

        <div class="content-one content-item action">

            <?php echo $output['info']['goods_info']['mobile_body']; ?>

        </div>

        <div class="content-two content-item">

            <ul class="spec_list">

                <?php if ($output['info']['goods_info']['goods_attr']) { ?>
                    <?php foreach ($output['info']['goods_info']['goods_attr'] as $attr_nv_list) { ?>
                            <li class="spec_item">
                                <?php foreach ($attr_nv_list as $attr_nk => $attr_nv) { ?>
                                    <?php if ($attr_nk == 'name') { ?>
                                        <span class="spec_name"><?php echo $attr_nv; ?>:</span>
                                    <?php }else{ ?>
                                        <span class="spec_value"><?php echo $attr_nv; ?></span>
                                    <?php } ?>
                                <?php } ?>
                            </li>
                    <?php } ?>
                <?php }else{ ?>

                    <li class="spec_item">没有参数</li>

                <?php } ?>

            </ul>

        </div>

        <div class="content-three content-item">


        </div>

    </div>

</div>

<!--规格选择部分-->

<div class="spec-box" id="spec-box">
    <div class="summary">
        <div class="img">
            <img id="goods_spec_img" src="<?php echo $output['info']['goods_info']['goods_image_url']; ?>">
        </div>
        <div class="main">
            <div class="price-container">
                <span class="price"
                      id="goods_spec_price">￥<?php echo $output['info']['goods_info']['goods_promotion_price']; ?></span>
            </div>
            <div class="stock-control">
                <span class="stock-container"><label class="label">库存：</label><small
                        id="stock"><?php echo $output['info']['goods_info']['goods_storage']; ?></small><small
                        class="bz">件
                    </small></span>
                <span class="limit-tips"></span>
            </div>
            <?php if ($output['info']['goods_info']['goods_spec']) { ?>
                <div class="sku-tips">
                    <label>已选择：</label>
            <span id="goods_spec_value_list">

                    <?php foreach ($output['info']['goods_info']['goods_spec'] as $spec_vkk => $spec_nvv) { ?>
                        <span><?php echo $spec_nvv; ?></span>
                    <?php } ?>

            </span>
                </div>
            <?php } ?>
        </div>
        <div class="close"></div>
    </div>
    <div class="body">
        <div class="address-select">
        </div>
        <div class="sku-control">
            <ul>
                <?php if ($output['info']['goods_info']['spec_name']) { ?>
                    <?php foreach ($output['info']['goods_info']['spec_name'] as $spec_nk => $spec_nv) { ?>
                        <?php if ($output['info']['goods_info']['spec_value'][$spec_nk]) { ?>
                            <li>
                                <h2><?php echo $spec_nv; ?></h2>
                                <div class="items">
                                    <?php foreach ($output['info']['goods_info']['spec_value'][$spec_nk] as $spec_vk => $spec_vv) { ?>
                                        <?php if (in_array($spec_vk, array_keys($output['info']['goods_info']['goods_spec']))) { ?>
                                            <label class="checked spec_items_value"
                                                   data-value="<?php echo $spec_vk; ?>"><?php echo $spec_vv; ?></label>
                                        <?php } else { ?>
                                            <label class="spec_items_value"
                                                   data-value="<?php echo $spec_vk; ?>"><?php echo $spec_vv; ?></label>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </li>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            </ul>
        </div>
        <div class="number-control">
            <h1>数量</h1>
            <div class="mui-number">
                <button type="button" class="dec">-</button>
                <input type="text" class="num" id="goods_spec_num" value="1" min="1" max="987" name="num">
                <button type="button" class="inc">+</button>
            </div>
        </div>
    </div>
    <div class="ok-btn">
        确定
    </div>
</div>
<div class="cover-bg">

</div>

<!--规格选择部分-->

<!--尾部-->

<?php include template('footer'); ?>

</body>

</html>

