<?php defined('InSystem') or exit('Access Invalid!'); ?>

<?php if ($v['home12']['image']) { ?>
    <link href="./templates/default/css/activity.css" rel="stylesheet">
    <section class="activity-header">
        <a class="go-history" href="javascript:history.go(-1);"> <img
                src="./templates/default/images/go-history-ico.png"></a>
        <img class="title" src="<?php echo $v['home12']['image']; ?>">
        <a class="cate-btn"><img src="./templates/default/images/cate-ico.png"></a>
    </section>
    <section class="activity-tips">
        <span>大家都在逛</span>
    </section>
<?php } ?>
