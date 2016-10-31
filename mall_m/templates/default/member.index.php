<!doctype html>

<html>

<head>

    <meta charset="utf-8">

    <meta http-equiv="x-ua-compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">

    <title>会员中心</title>

    <meta name="keywords" content=""/>

    <meta name="description" content=""/>

    <?php include template('common_js_css'); ?>



</head>

<body>



<header class="member-header">

    <a class="set-btn">设置</a>



    <a class="member-head-comment"><img src="./templates/default/images/member/member-head-ico.jpg"></a>

</header>



<section class="member-info">

    <div class="member-info-box-top">

        <div class="member-avater">

            <img src="<?php echo $output['member_info']['avator'];?>" id="avatar"/>

        </div>

        <div class="member-username">

            <span id="username"><?php echo $output['member_info']['user_name'];?></span>

        </div>

    </div>

    <div class="member-info-box-bottom">



    </div>

</section>



<section class="member-chanle-nav">

    <a href="<?php echo BASE_SITE_URL;?>/mall_m/index.php?act=mb_special&op=index&sp_id=1" class="member-chanle-item">

        <div class="img">

            <img src="./templates/default/images/member/chanle-ico.png">

        </div>

        <div class="info">

            <h1>汽车频道</h1>

            <p>新款汽车展卖会</p>

        </div>

    </a>

    <a href="<?php echo BASE_SITE_URL;?>/mall_m/index.php?act=mb_special&op=index&sp_id=4" class="member-chanle-item">

        <div class="img">

            <img src="./templates/default/images/member/shuma-ico.png">

        </div>

        <div class="info">

            <h1>数码频道</h1>

            <p>时尚数码展会</p>

        </div>

    </a>

</section>



<section class="member-order-box">

    <div class="member-order-head">

        <span>我的订单</span>

        <span class="show-all-order"><a href="<?php echo BASE_SITE_URL;?>/wap/tmpl/member/order_list.html">查看全部订单 ></a></span>

    </div>

    <div class="member-order-item-box">

        <a href="<?php echo BASE_SITE_URL;?>/wap/tmpl/member/order_list.html?state=10" class="member-order-item">

            <img src="./templates/default/images/member/pay-ico-1.png">

            <p>待付款</p>

        </a>

        <a href="<?php echo BASE_SITE_URL;?>/wap/tmpl/member/order_list.html?state=20" class="member-order-item">

            <img src="./templates/default/images/member/pay-ico-2.png">

            <p>待发货</p>

        </a>

        <a href="<?php echo BASE_SITE_URL;?>/wap/tmpl/member/order_list.html?state=30" class="member-order-item">

            <img src="./templates/default/images/member/pay-ico-3.png">

            <p>待收货</p>

        </a>

        <a href="<?php echo BASE_SITE_URL;?>/wap/tmpl/member/order_list.html?state=40" class="member-order-item">

            <img src="./templates/default/images/member/pay-ico-4.png">

            <p>待评价</p>

        </a>

        <a href="<?php echo BASE_SITE_URL;?>/wap/tmpl/member/order_list.html?refund=1" class="member-order-item">

            <img src="./templates/default/images/member/pay-ico-5.png">

            <p>退款/售后</p>

        </a>

    </div>

</section>



<section class="line-5">



</section>



<section class="member-tool-box">

    <div class="member-tool-head">

        <span>必备工具</span>

        <span class="more"><a><img src="./templates/default/images/member/fwefwef.png">车主权益有更新啦!~<img class="arrow"

                                                                                    src="./templates/default/images/member/fsfdsfewf.png"></a></span>

    </div>

    <div class="member-tool-box">

        <a href="<?php echo BASE_SITE_URL;?>/wap/tmpl/member/favorites.html" class="item">

            <img src="./templates/default/images/member/member-ico1.png">

            <p>收藏宝贝</p>

        </a>

        <a class="item">

            <img src="./templates/default/images/member/member-ico2.png">

            <p>收藏店铺</p>

        </a>

        <a class="item">

            <img src="./templates/default/images/member/member-ico3.png">

            <p>最新资讯</p>

        </a>

        <a class="item">

            <img src="./templates/default/images/member/member-ico4.png">

            <p>我的评价</p>

        </a>

        <a class="item">

            <img src="./templates/default/images/member/member-ico5.png">

            <p>卡券包</p>

        </a>

        <a class="item">

            <img src="./templates/default/images/member/member-ico6.png">

            <p>支付方式</p>

        </a>

        <a href="<?php echo BASE_SITE_URL;?>/wap/tmpl/member/address_list.html" class="item">

            <img src="./templates/default/images/member/member-ico7.png">

            <p>常用地址</p>

        </a>

        <a class="item">

            <img src="./templates/default/images/member/member-ico8.png">

            <p>在线客服</p>

        </a>

    </div>

</section>



<section class="member-sales-box">

    <div class="member-sales-head">

        <span>常用频道</span>

    </div>

    <section class="lyt-content">

        <div class="lyt-main">

            <div class="left">

                <a href="<?php echo BASE_SITE_URL;?>/wap/tmpl/zero/list.html?key="><img src="./templates/default/images/index/activity-img1.jpg"></a>

            </div>

            <div class="right">

                <div class="top">

                    <a href="<?php echo BASE_SITE_URL;?>/mall_m/index.php?act=mb_special&op=index&sp_id=6&key="><img src="./templates/default/images/index/activity-img2.jpg"></a>

                </div>

                <div class="bottom">

                    <div class="bottom-left">

                        <a href="<?php echo BASE_SITE_URL;?>/mall_m/index.php?act=mb_special&op=index&sp_id=7&key="><img src="./templates/default/images/index/activity-img3.jpg"></a>

                    </div>

                    <div class="bottom-right">

                        <a href="<?php echo BASE_SITE_URL;?>/mall_m/index.php?act=mb_special&op=index&sp_id=8&key="><img src="./templates/default/images/index/activity-img4.jpg"></a>

                    </div>

                </div>

            </div>

        </div>

    </section>

</section>





<!--尾部-->

<?php include template('footer'); ?>

</body>

</html>

