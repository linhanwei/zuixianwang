<?php defined('InSystem') or exit('Access Invalid!');?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>区域</h3>
            <ul class="tab-base">
                <li><a href="JavaScript:void(0);" class="current"><span>管理</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form method="get" name="formSearch" id="formSearch">
        <input type="hidden" value="agent" name="act">
        <input type="hidden" value="feedback" name="op">
        <table class="tb-type1 noborder search">
            <tbody>
            <tr><th><label for="owner_and_name">提交者</label></th>
                <td><input type="text" value="<?php echo $output['member_name'];?>" name="member_name" id="member_name" class="txt"></td><td></td><th><label>状态</label></th>
                <td>
                    <select name="fb_state">
                        <option value=""><?php echo $lang['nc_please_choose'];?>...</option>
                        <option value="1" <?php if($_GET['fb_state'] == 1){?>selected<?php }?>>未处理</option>
                        <option value="2" <?php if($_GET['fb_state'] == 2){?>selected<?php }?>>已处理</option>
                    </select>
                </td>
                <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
                    <?php if($output['owner_and_name'] != '' or $output['feedback_name'] != '' or $output['grade_id'] != ''){?>
                        <a href="index.php?act=feedback&op=feedback" class="btns " title="<?php echo $lang['nc_cancel_search'];?>"><span><?php echo $lang['nc_cancel_search'];?></span></a>
                    <?php }?></td>
            </tr></tbody>
        </table>
    </form>
    <table class="table tb-type2" id="prompt">
        <tbody>
        <tr class="space odd">
            <th colspan="12"><div class="title">
                    <h5><?php echo $lang['nc_prompts'];?></h5>
                    <span class="arrow"></span></div></th>
        </tr>
        <tr>
            <td><ul>
                    <li>查看用户提交的加盟信息</li>
                </ul></td>
        </tr>
        </tbody>
    </table>
    <form method="post" id="feedback_form">
        <input type="hidden" name="form_submit" value="ok" />
        <table class="table tb-type2">
            <thead>
            <tr class="thead">
                <th>用户名</th>
                <th>留言时间</th>
                <th class="align-center">状态</th>
                <th class="align-center">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($output['feedback_list']) && is_array($output['feedback_list'])){ ?>
                <?php foreach($output['feedback_list'] as $k => $v){ ?>
                    <tr class="hover">
                        <td>
                            <?php echo $v['fb_member_name'];?>
                        </td>
                        <td><?php echo date('Y-m-d H:i:s',$v['fb_addtime']);?></td>
                        <td class="align-center w72"><?php echo $v['fb_state'] == '2'?'已处理':'未处理';?></td>
                        <td class="align-center w200">
                            <a href="index.php?act=agent&op=feedback_detail&fb_id=<?php echo $v['fb_id'];?>">查看</a>&nbsp;&nbsp;
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
