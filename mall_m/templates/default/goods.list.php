<!doctype html>

<html>

<head>

    <meta charset="utf-8">

    <meta http-equiv="x-ua-compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">

    <title>商品列表</title>

    <meta name="keywords" content=""/>

    <meta name="description" content=""/>

    <?php include template('common_js_css'); ?>

    <link href="./templates/default/css/goods_list.css" rel="stylesheet">

    <script src="./templates/default/js/goods_list.js"></script>

    <script>

        var page_count = <?php echo $output['page_count'];?>;

    </script>

</head>

<body>

    <!--头部-->

    <?php include template('search_header'); ?>



    <section class="content_header">

        <div class="product-filter">

            <a href="javascript:void(0);" class="clearfix keyorder current" key="4">
                <span class="pf-newpd-icon f-icon fleft line">综合排序</span>
                <!--<span class="pf-title">综合排序</span>-->
            </a>

            <a href="javascript:;" class="clearfix keyorder" key="1">
                <span class="pf-sales-icon f-icon fleft line">销量优先</span>
                <!--<span class="pf-title">销量优先</span>-->
            </a>
            
            <a href="javascript:;" class="clearfix keyorder" key="2">
                <span class="spec"></span>
            </a>
            
            <a href="javascript:;" class="clearfix keyorder" key="3">
              <!--<span class="pf-price-icon  desc f-icon fleft"></span>-->
              <span class="pf-title">价格</span>
              <span class="price-ico pf-price-icon f-icon fleft"></span>
            </a>
 <!--
            <a href="javascript:;" class="clearfix keyorder" key="2">

                <span class="pf-popularity-icon f-icon fleft"></span>

                <span class="pf-title">人气</span>

            </a>
-->

        </div>

    </section>

    <section class="prolist-box">

        <?php if($output['list']){ ?>

            <?php foreach ((array) $output['list'] as $item) { ?>

                <a href="<?php echo $item['goods_commonid'] ? buildSpecialUrl('goods', $item['goods_id']) : 'javascript:void(0);'; ?>" class="prolist-item">

                    <div class="prolist-item-inline">

                        <div class="img">

                            <img  class="lazy" src="<?php echo BASE_SITE_URL;?>/mall_m/templates/default/images/default_grey.png" data-original="<?php echo $item['goods_image_url']; ?>">

                        </div>

                        <div class="info">

                            <h2><?php echo $item['goods_name']; ?></h2>

                            <p>￥<?php echo $item['goods_price']; ?></p>

                        </div>

                    </div>

                </a>

            <?php } ?>

        <?php }else{ ?>

            <div style="height: 100px;width: 100%;line-height: 100px;text-align: center;font-size: 1.5rem;">暂时没有相关商品</div>

        <?php } ?>

    </section>



    <!--尾部-->

    <?php include template('footer'); ?>

</body>

</html>

