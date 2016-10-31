<?php defined('InSystem') or exit('Access Invalid!');?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>区域</h3>
            <ul class="tab-base">
                <li><a href="JavaScript:void(0);" class="current"><span>区域管理</span></a></li>
                <li><a href="index.php?act=agent&op=area_add"><span>添加区域</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form method="post" id="feedback_form">
        <input type="hidden" name="form_submit" value="ok" />
        <table class="table tb-type2">
            <thead>
            <tr class="thead">
                <th>区域名称</th>
                <th class="align-center">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($output['area_list']) && is_array($output['area_list'])){ ?>
                <?php foreach($output['area_list'] as $k => $v){ ?>
                    <tr class="hover">
                        <td>
                            <?php echo $v['aa_name'];?>
                        </td>
                        <td class="align-center w200">
                            <?php if($v['aa_area'] != 'all'){?>
                            <a href="index.php?act=agent&op=area_edit&aa_id=<?php echo $v['aa_id'];?>">编辑</a>&nbsp;&nbsp;|&nbsp;&nbsp;
                            <a href="javascript:void(0)" onclick="if(confirm('确定删除区域?')){location.href='index.php?act=agent&op=area_del&aa_id=<?php echo $v['aa_id']; ?>'}">删除</a>
                            <?php }else{echo '总部管理所有地区';}?>
                        </tr>
                <?php } ?>
            <?php }else { ?>
                <tr class="no_data">
                    <td colspan="15"><?php echo $lang['nc_no_record'];?></td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot>
            <tr class="tfoot">
                <td></td>
                <td colspan="16">
                    <div class="pagination"><?php echo $output['page'];?></div></td>
            </tr>
            </tfoot>
        </table>
    </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script>
    $(function(){
        $('#ncsubmit').click(function(){
            $('input[name="op"]').val('feedback');$('#formSearch').submit();
        });
    });
</script>
