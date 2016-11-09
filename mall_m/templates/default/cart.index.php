<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
    <title>醉仙酒商城</title>
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
    <?php include template('common_js_css'); ?>
    <script src="./templates/default/js/cart.js"></script>
</head>
<body>

<section class="cart-head">
    <span class="title">购物车</span>
    <a href="javascript:void(0);" class="info-btn"><img src="./templates/default/images/cart/ico-1464645.png"></a>
</section>

<?php if($output['cart_list']){ ?>
    <?php foreach($output['cart_list'] as $skey=>$store){ ?>
        <section class="cart-shop-item">
            <div class="cart-shop-item-head">
                <span class="select-btn" id="shop-select-all-1" onClick="shopAllSelect(<?php echo $store[0]['store_id']; ?>)"><img src="./templates/default/images/cart/ico-789446.png"></span>
                <span class="shop-btn"><a href="javascript:void(0)"><img src="./templates/default/images/cart/ico-4658794.png"><?php echo $store[0]['store_name']; ?><img src="./templates/default/images/cart/ico-4562255.png"></a></span>
                <span class="edit-btn" data-shopid="1" data-status="1"><a href="javascript:void(0)">编辑</a></span>
            </div>
            <!--<div class="cart-shop-item-tips">
                满199可减10元,可跨店使用 <a href="javascript:void(0);">去凑单></a>
            </div>-->
            <div class="cart-shop-pro-list" id="shop-list-<?php echo $store[0]['store_id']; ?>" data-shopid="<?php echo $store[0]['store_id']; ?>">

                <?php foreach($store as $gk=>$goods){ ?>
                    <?php if($gk == 0 || $gk != 'store_info'){ ?>
                        <div class="cart-shop-pro-item" id="pro-item-<?php echo $goods['cart_id'];?>" data-price="<?php echo $goods['goods_price'];?>" data-num="<?php echo $goods['goods_num'];?>" data-proid="<?php echo $goods['cart_id'];?>">
                            <div class="select" data-selected="0" onClick="cartSelectItem(<?php echo $goods['cart_id'];?>)">
                                <img src="./templates/default/images/cart/ico-789446.png">
                            </div>
                            <div class="image">
                                <a href="<?php echo BASE_SITE_URL;?>/mall_m/index.php?act=goods&op=detail&goods_id=<?php echo $goods['goods_id'];?>"><img src="<?php echo $goods['goods_image_url'];?>"></a>
                            </div>
                            <div class="info">
                                <div class="title"><?php echo $goods['goods_name'];?></div>
                                <div class="price">商品价格:￥<span id="price-show-<?php echo $goods['cart_id'];?>"><?php echo $goods['goods_price'];?></span></div>
                                <div class="num">商品数量:<span id="num-show-<?php echo $goods['cart_id'];?>"><?php echo $goods['goods_num'];?></span></div>
                            </div>
                            <div class="col">
                                <div class="left">
                                    <div class="num-col">
                                        <a class="minus" id="minus-<?php echo $goods['goods_num'];?>" onClick="cartMinus(<?php echo $goods['cart_id'];?>);">-</a>
                                        <input name="num" id="num-<?php echo $goods['cart_id'];?>" value="<?php echo $goods['goods_num'];?>">
                                        <a class="add" id="add-1" onClick="cartAdd(<?php echo $goods['cart_id'];image?>);">+</a>
                                    </div>
                                </div>
                                <div class="right">
                                    <a href="javascript:void(0)" onClick="cartItemDel(<?php echo $goods['cart_id'];?>);">删除</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </section>
    <?php } ?>
<?php }else{ ?>
    <div class="date_empty"><a href="<?php echo BASE_SITE_URL;?>/wap">去逛逛</a></div>
<?php } ?>

<section class="cart-statement-box">
    <a href="javascript:void(0);" class="cart-statement-select-btn" id="cart-statement-select-btn"><span><img src="./templates/default/images/cart/ico-789446.png">全选</span></a>
    <div class="cart-statement-info">
        <div class="cart-statement-total-info">
            <div class="total-box"><label>合计:</label><span class="total">￥<small id="total-show">0</small></span></div>
            <div class="freight" id="freight">不含运费</div>
        </div>
        <div class="cart-statement-btn" id="cart-statement-btn">
            结算(<span id="pro-num">0</span>)
        </div>
    </div>
</section>
<!--尾部-->
<?php include template('footer'); ?>
</body>
</html>
