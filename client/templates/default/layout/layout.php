<?php defined('InSystem') or exit('Access Invalid!');?>
<!doctype html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>车族宝</title>
    <script>
    COOKIE_PRE = '<?php echo COOKIE_PRE;?>';
    _CHARSET = '<?php echo strtolower(CHARSET);?>';
    var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';
    </script>
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/bootstrap/js/jquery.min.js" charset="utf-8"></script>
    <!-- 新 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">

     <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js"></script>
    <script type="text/javascript" src="<?php echo RESOURCE_CLIENT_URL;?>/js/config.js"></script>
    <script type="text/javascript" src="<?php echo BASE_SITE_URL;?>/invite/js/insurevalidate.js"></script>
</head>
<?php
require_once($tpl_file);
?>
<script>
    $('#protocol').click(function(){
        parent.layer.open({
            title:'协议查看',
            type: 2,
            area: ['90%', '90%'],
            fix: false,
            maxmin: false,
            content: ClientSiteUrl + '/index.php?act=register&op=protocol'
        });
    });
</script>
<script type="text/javascript" src="<?php echo RESOURCE_CLIENT_URL;?>/js/common.js"></script>
    </body>
</html>
