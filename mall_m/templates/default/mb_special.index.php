<?php defined('InSystem') or exit('Access Invalid!'); ?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
    <title>车主商城</title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <?php include template('common_js_css'); ?>
    <script src="./templates/default/js/swiper.jquery.min.js"></script>
</head>
<body>
<!--头部-->
<?php
    $special_type = $output['info']['special_type'];
    switch($special_type){
        case 1:
            include template('search_header');
            break;
        case 2:
            include template('recommend_header');
            break;
    }

?>

<!--专题设置-->
<?php
    if($output['mb_item_list'] && is_array($output['mb_item_list'])){
        foreach ((array) $output['mb_item_list'] as $k=>$v) {
            include template('mb_special_item.module_'.$v['mb_type']);
            if($k == 0 && $special_type == 1){
                include template('mb_special.chanle-list');
            }
        }
    }else{
        if($special_type == 1){
            include template('mb_special.chanle-list');
        }
        echo '<div style="width: 100%;height: 100px;line-height:100px;text-align: center;font-size: 16px;">暂时没有相关活动信息,敬请期待!</div>';
    }
?>

<!--尾部-->
<?php include template('footer'); ?>
</body>
</html>
