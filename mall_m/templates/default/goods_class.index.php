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

    <link href="./templates/default/css/category.css" rel="stylesheet">

    <script>



        $(function () {

            //滑动效果

            var mySwiper1 = new Swiper('#cate-box', {

                freeMode: true,

                slidesPerView: 'auto',

            });



            //异步请求分类

            $('#cate-box .swiper-slide').click(function(){

                $(this).addClass('swiper-slide-on').siblings().removeClass('swiper-slide-on');

                var gc_id = $(this).attr('gc_id');



//                var layer_index = layer.load(0, {shade:[0.3, '#000']});

                var layer_index = layer.load(0, {shade:false});



                $.ajax({

                    url: SiteUrl + "/mall_m/index.php?act=goods_class&op=index",

                    type: 'get',

                    data:{gc_id:gc_id,is_ajax:1},

                    dataType: 'json',

                    beforeSend:function(){



                    },

                    success: function(result) {

                        var data = result.datas.child_list,

                            html = '';

                        //console.log(gc_id,data);

                        if(data){

                            for(var i in data){

                                var child = data[i].child;

                                if(child.length > 0){

                                    html += '<div class="item-one">';

                                    html += '<h1><a>'+data[i].gc_name+'</a></h1>';

                                    html += '<div class="item-two">';

                                    for(var k in child){

                                        html += '<a href="'+SiteUrl+'/mall_m/index.php?act=goods&op=list&gc_id='+child[k].gc_id+'" class="item">';

                                        html += '<img class="lazy" src="'+SiteUrl+'/mall_m/templates/default/images/default_grey.png" data-original="'+child[k].image+'">';

                                        html += '<p>'+child[k].gc_name+'</p>';

                                        html += '</a>';

                                    }

                                    html += '</div>';

                                    html += '</div>';

                                }

                            }



                            $('.category-list-box').html(html);



                        }
                         $("img.lazy").lazyload({effect: "fadeIn",threshold:"400"});
                        layer.close(layer_index);

                    }

                });

            });

        })

    </script>

</head>



    <body>

        <!--头部-->

        <?php include template('search_header'); ?>



        <section id="cate-box" class="chanle-cate-box">

            <div class="swiper-wrapper">

                <div class="swiper-slide swiper-slide-on" gc_id="<?php echo $output['info']['gc_id'];?>"><a><?php echo $output['info']['gc_name'];?></a></div>

                <?php if($output['top_list']){?>

                    <?php foreach($output['top_list'] as $tv){?>

                        <div class="swiper-slide" gc_id="<?php echo $tv['gc_id']?>">

                            <a><?php echo $tv['gc_name']?></a>

                            <!--<a href="<?php /*BASE_SITE_URL;*/?>/mall_m/index.php?act=goods_class&op=index&gc_id=<?php /*echo $tv['gc_id']*/?>"><?php /*echo $tv['gc_name']*/?></a>

                        -->

                        </div>

                    <?php }?>

                <?php }?>

            </div>

        </section>

        <section class="category-list-box">

            <?php if($output['child_list']){?>

                <?php foreach($output['child_list'] as $cv){?>

                    <?php if($cv['child']){?>

                        <div class="item-one">

                        <h1><a><?php echo $cv['gc_name'];?></a></h1>

                        <div class="item-two">

                            <?php foreach($cv['child'] as $ncv){?>

                                <a href="<?php echo BASE_SITE_URL;?>/mall_m/index.php?act=goods&op=list&gc_id=<?php echo $ncv['gc_id'];?>" class="item">

                                    <img class="lazy" src="<?php echo BASE_SITE_URL;?>/mall_m/templates/default/images/default_grey.png" data-original="<?php echo $ncv['image'];?>">

                                    <p><?php echo $ncv['gc_name'];?></p>

                                </a>

                            <?php }?>

                        </div>

                    </div>

                    <?php }?>

                <?php }?>

            <?php }?>

        </section>

        <!--尾部-->

        <?php include template('footer'); ?>

    </body>

</html>

