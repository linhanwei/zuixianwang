<?php defined('InSystem') or exit('Access Invalid!');?>



<div class="page">

  <div class="fixed-bar">

    <div class="item-title">

      <h3><?php echo 'banner管理';?></h3>

      <ul class="tab-base">

        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_manage'];?></span></a></li>

        <li><a href="index.php?act=banner&op=add"><span><?php echo '新增';?></span></a></li>

      </ul>

    </div>

  </div>

  <div class="fixed-empty"></div>

  <form method="get" name="formSearch" id="formSearch">

    <input type="hidden" name="act" value="banner">
    <input type="hidden" name="op" value="index">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <th><label for="b_title">标题</label></th>
          <td><input type="text" class="txt" name="b_title" id="b_title" value="<?php echo $output['b_title']?>" type="text"></td>
          <td>
            <a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
          </td>
        </tr>

      </tbody>

    </table>

  </form>

  <table class="table tb-type2" id="prompt">

    <tbody>

      <tr class="space odd">

        <th colspan="12"><div class="title"><h5><?php echo $lang['nc_prompts'];?></h5><span class="arrow"></span></div></th>

      </tr>

      <tr>

        <td>

        <ul>

            <li><?php echo '用于banner管理';?></li>

          </ul></td>

      </tr>

    </tbody>

  </table>

  <form method='post' onsubmit="if(confirm('<?php echo $lang['nc_ensure_del'];?>')){return true;}else{return false;}" name="brandForm">

    <input type="hidden" name="form_submit" value="ok" />

    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th class="w48 align-center">bannerID</th>
          <th class="w270 align-center">标题</th>
          <th class="align-center">URL链接</th>
          <th class="align-center">图片</th>
          <th class="align-center">添加时间</th>
          <th class="w72 align-center"><?php echo $lang['nc_handle'];?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>

        <?php foreach($output['list'] as $k => $v){ ?>

        <tr class="hover edit">
          <td class="align-center"><?php echo $v['id']; ?></td>
          <td class="align-center"><?php echo $v['title']; ?></td>
          <td class="align-center"><?php echo $v['url']; ?></td>
          <td class="align-center"><img src="<?php echo getBannerImageUrl($v['image_name']); ?>" style="width: 100px;"></td>
          <td class="align-center"><?php echo date('Y-m-d H:i:s',$v['edit_time']); ?></td>
          <td class="align-center">
            <a href="index.php?act=banner&op=edit&id=<?php echo $v['id'];?>"><?php echo $lang['nc_edit'];?></a>&nbsp;|&nbsp;
            <a href="javascript:void(0)" onclick="if(confirm('<?php echo $lang['nc_ensure_del'];?>')){location.href='index.php?act=banner&op=del&id=<?php echo $v['id'];?>';}else{return false;}"><?php echo $lang['nc_del'];?></a>
          </td>
        </tr>

        <?php } ?>

        <?php }else { ?>

        <tr class="no_data">

          <td colspan="10"><?php echo $lang['nc_no_record'];?></td>

        </tr>

        <?php } ?>

      </tbody>

      <tfoot>

        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>

        <tr colspan="15" class="tfoot">

          <td colspan="16">

            <div class="pagination"> <?php echo $output['page'];?> </div></td>

        </tr>

        <?php } ?>

      </tfoot>

    </table>

  </form>

  <div class="clear"></div>

</div>

</div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>

<script>

$(function(){

    $('#ncexport').click(function(){

      $('input[name="op"]').val('export_step1');

      $('#formSearch').submit();

    });

    $('#ncsubmit').click(function(){

        $('#formSearch').submit();

    }); 

});

</script>