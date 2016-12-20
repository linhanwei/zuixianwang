<?php defined('InSystem') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>投诉建议</h3>
      <ul class="tab-base">
        <?php  if($output['op'] == 'index') { ?>
          <li><a href="JavaScript:void(0);" class="current"><span>没有处理</span></a></li>
          <li><a href="index.php?act=complain_suggest&op=handld"><span>已经处理</span></a></li>
        <?php } else { ?>
          <li><a href="index.php?act=complain_suggest&op=index" ><span>没有处理</span></a></li>
          <li><a href="JavaScript:void(0);" class="current"><span>已经处理</span></a></li>
        <?php }  ?>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="search_form" method="get" name="formSearch">
    <input type="hidden" id="act" name="act" value="complain_suggest" />
    <input type="hidden" id="op" name="op" value="<?php echo $output['op'];?>" />
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <th><label for="input_complain_accuser">会员名字:</label></th>
          <td><input class="txt" type="text" name="member_name" id="input_complain_accuser" value="<?php echo $_GET['member_name'];?>"></td>
          <th><label for="input_complain_subject_content">联系电话:</label></th>
          <td colspan="2"><input class="txt2" type="text" name="mobile" id="input_complain_subject_content" value="<?php echo $_GET['mobile'];?>"></td>
          <td><a href="javascript:document.formSearch.submit();" class="btn-search " title="查询">&nbsp;</a></td>
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
            <li>本投诉建议只是用于管理后台处理!</li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <form method='post' id="list_form" action="">
    <table class="table tb-type2 nobdb">
      <thead>
        <tr class="thead">
          <th class="w12">&nbsp;</th>
          <th>投诉人</th>
          <th>联系电话</th>
          <th>联系邮箱</th>
          <th class="align-center">投诉时间</th>
          <th class="align-center">建议内容</th>
          <th class="w72 align-center">操作</th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
        <?php foreach($output['list'] as $v){ ?>
        <tr class="hover">
          <td>&nbsp;</td>
          <td><?php echo $v['cs_member_name'];?></td>
          <td><?php echo $v['cs_mobile'];?></td>
          <td><?php echo $v['cs_email'];?></td>
          <td class="nowarp align-center"><?php echo date('Y-m-d H:i:s',$v['cs_add_time']);?></td>
          <td><?php echo $v['cs_content'];?></td>
          <td class="align-center">
            <?php  if($output['op'] == 'index') { ?>
              <a href="JavaScript:void(0);" data_id="<?php echo $v['id'];?>" id="new_handle">处理完成</br></a>
            <?php }?>
            <a href="index.php?act=complain_suggest&op=cs_info&id=<?php echo $v['id'];?>">详情</a>
            <a href="JavaScript:void(0);" data_id="<?php echo $v['id'];?>" id="del_data">删除</a>
          </td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr class="no_data">
          <td colspan="15"><?php echo $lang['nc_no_record'];?></td>
        </tr>
        <?php } ?>
      </tbody>
      <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
      <tfoot>
        <tr>
          <td colspan="15"><div class="pagination"> <?php echo $output['show_page'];?> </div></td>
        </tr>
      </tfoot>
      <?php } ?>
    </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript">
$(document).ready(function(){
    //处理完成
    $('#new_handle').click(function(){
        var cs_id = $(this).attr('data_id');
        $.ajax({
          type:'post',
          url:"index.php?act=complain_suggest&op=ajax",
          data:{handle:'handle_complete',cs_id:cs_id},
          dataType:'json',
          success:function(result){
            if(result){
              alert('处理完成');
              location.href = location.href;
            }

          }
        });
    })

    //删除
    $('#del_data').click(function(){
      var cs_id = $(this).attr('data_id');

      if(!confirm('确定需要删除吗?')){
        return false;
      }
      $.ajax({
        type:'post',
        url:"index.php?act=complain_suggest&op=ajax",
        data:{handle:'del',cs_id:cs_id},
        dataType:'json',
        success:function(result){
          console.log(result,14511444);
          if(result){
            alert('删除成功');
            location.href = location.href;
          }

        }
      });
    })

	//表格移动变色
	$("tbody .line").hover(
    function()
    {
        $(this).addClass("complain_highlight");
    },
    function()
    {
        $(this).removeClass("complain_highlight");
    });
    $('#time_from').datepicker({dateFormat: 'yy-mm-dd',onSelect:function(dateText,inst){
        var year2 = dateText.split('-') ;
        $('#time_to').datepicker( "option", "minDate", new Date(parseInt(year2[0]),parseInt(year2[1])-1,parseInt(year2[2])) );
    }});
    $('#time_to').datepicker({onSelect:function(dateText,inst){
        var year1 = dateText.split('-') ;
        $('#time_from').datepicker( "option", "maxDate", new Date(parseInt(year1[0]),parseInt(year1[1])-1,parseInt(year1[2])) );
    }});

});
</script> 
