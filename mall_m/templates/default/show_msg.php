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
    <link href="./templates/default/css/swiper.min.css" rel="stylesheet">
    <link href="./templates/default/css/app.css" rel="stylesheet">
    <script src="./templates/default/js/jquery.js"></script>
    <style>
        #notice_msg{width: 100%;height: 100px;line-height: 100px;text-align: center;font-size: 16px;font-weight: bold;color:firebrick;}
    </style>
</head>
<body>
<!--头部-->
<?php include template('header'); ?>

<!--提示消息-->
<section>
    <div id="notice_msg">
        <?php echo $output['msg']; ?>
    </div>
</section>

<!--尾部-->
<?php include template('footer'); ?>
</body>
</html>
