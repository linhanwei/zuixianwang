<style>
    .cs_img td{text-align: center;}
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>投诉建议</h3>
            <ul class="tab-base">
                <li><a href="index.php?act=complain_suggest&op=index" ><span>没有处理</span></a></li>
                <li><a href="index.php?act=complain_suggest&op=handld"><span>已经处理</span></a></li>
                <li><a href="JavaScript:void(0);" class="current"><span>详情</span></a></li>
            </ul>
        </div>
    </div>

<table class="table tb-type2 order mtw">
  <tbody>
    <tr>
      <th>投诉人信息</th>
    </tr>
    <tr class="noborder">
      <td>
          <ul>
              <li><strong>投诉人:</strong><?php echo $output['info']['cs_member_name'];?></li>
              <li><strong>投诉时间:</strong><?php echo date('Y-m-d H:i:s',$output['info']['cs_add_time']);?></li>
              <li><strong>联系电话:</strong><?php echo $output['info']['cs_mobile'];?></li>
              <li><strong>联系邮箱:</strong><?php echo $output['info']['cs_email'];?></li>
          </ul>
      </td>
    </tr>
    <tr>
      <th>建议内容</th>
    </tr>
    <tr class="noborder">
      <td><?php echo $output['info']['cs_content'];?></td>
    </tr>
    <tr>
        <th>图片</th>
    </tr>
    <tr class="noborder cs_img" style="clear: both;">
        <td>
            <?php if($output['info']['cs_pic1_url']){?><img src="<?php echo $output['info']['cs_pic1_url'];?>" alt=""><?php } ?>
            <?php if($output['info']['cs_pic2_url']){?><img src="<?php echo $output['info']['cs_pic2_url'];?>" alt=""><?php } ?>
            <?php if($output['info']['cs_pic3_url']){?><img src="<?php echo $output['info']['cs_pic3_url'];?>" alt=""><?php } ?>
        </td>
    </tr>
  </tbody>
</table>
</div>
