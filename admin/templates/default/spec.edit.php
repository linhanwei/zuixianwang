<?php defined('InSystem') or exit('Access Invalid!'); ?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><?php echo $lang['nc_spec_manage']; ?></h3>
            <ul class="tab-base">
                <li><a href="index.php?act=spec&op=spec"><span><?php echo $lang['nc_manage']; ?></span></a></li>
                <li><a href="index.php?act=spec&op=spec_add"><span><?php echo $lang['nc_new']; ?></span></a></li>
                <li><a class="current" href="JavaScript:void(0);"><span><?php echo $lang['nc_edit']; ?></span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form id="spec_form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="form_submit" value="ok"/>
        <input type="hidden" name="s_id" value="<?php echo $output['sp_list']['sp_id'] ?>"/>
        <table class="table tb-type2">
            <tbody>
            <tr class="noborder">
                <td class="required" colspan="2"><label class="validation"
                                                        for="s_name"><?php echo $lang['spec_index_spec_name'] . $lang['nc_colon']; ?></label>
                </td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><input type="text" class="txt" name="s_name" id="s_name"
                                                 value="<?php echo $output['sp_list']['sp_name']; ?>"/></td>
                <td class="vatop tips"><?php echo $lang['spec_index_spec_name_desc']; ?></td>
            </tr>
            <tr>
                <td class="required" colspan="2"><label class=""
                                                        for="s_sort"><?php echo $lang['spec_common_belong_class'] . $lang['nc_colon']; ?></label>
                </td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform" id="gcategory">
                    <input type="hidden" value="<?php echo $output['sp_list']['class_id']; ?>" class="mls_id"
                           name="class_id"/>
                    <input type="hidden" value="<?php echo $output['sp_list']['class_name']; ?>" class="mls_name"
                           name="class_name"/>
                    <span class="mr10"><?php echo $output['sp_list']['class_name']; ?></span>
                    <?php if (!empty($output['sp_list']['class_id'])) { ?>
                        <input class="edit_gcategory" type="button" value="<?php echo $lang['nc_edit']; ?>">
                    <?php } ?>
                    <select <?php if (!empty($output['sp_list']['class_id'])) { ?>style="display:none;"<?php } ?>
                            class="class-select">
                        <option value="0"><?php echo $lang['nc_please_choose']; ?>...</option>
                        <?php if (!empty($output['gc_list'])) { ?>
                            <?php foreach ($output['gc_list'] as $k => $v) { ?>
                                <?php if ($v['gc_parent_id'] == 0) { ?>
                                    <option value="<?php echo $v['gc_id']; ?>"><?php echo $v['gc_name']; ?></option>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </select></td>
                <td class="vatop tips"><?php echo $lang['spec_common_belong_class_tips']; ?></td>
            </tr>
            <tr>
                <td class="required" colspan="2"><label class="validation"
                                                        for="s_sort"><?php echo $lang['nc_sort'] . $lang['nc_colon']; ?></label>
                </td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><?php if ($output['sp_list']['sp_id'] != 1) { ?><input type="text" class="txt"
                                                                                                 name="s_sort"
                                                                                                 id="s_sort"
                                                                                                 value="<?php echo $output['sp_list']['sp_sort']; ?>" /><?php } else {
                        echo $output['sp_list']['sp_sort'];
                    } ?></td>
                <td class="vatop tips"><?php echo $lang['spec_index_spec_sort_desc']; ?></td>
            </tr>
            </tbody>
        </table>
        <table class="table tb-type2">
            <thead class="thead">
            <tr class="space">
                <th colspan="15">添加规格值:</th>
            </tr>
            <tr>
                <th><?php echo $lang['nc_del']; ?></th>
                <th><?php echo $lang['nc_sort']; ?></th>
                <th>规格值</th>
                <th></th>
            </tr>
            </thead>
            <tbody id="tr_model">
            <tr></tr>
            <?php if (is_array($output['sp_val_list']) && !empty($output['sp_val_list'])) { ?>
                <?php foreach ($output['sp_val_list'] as $aval) { ?>
                    <tr class="hover edit">
                        <input type="hidden" value="<?php echo $aval['sp_value_id']; ?>"
                               name="spec_value[<?php echo $aval['sp_value_id']; ?>][a_id]" nc_type='ajax_attr_id'/>
                        <td class="w48"><input type="checkbox" name="a_del[<?php echo $aval['sp_value_id']; ?>]"
                                               value="<?php echo $aval['sp_value_id']; ?>"/></td>
                        <td class="w48 sort"><input type="text" class="change_default_submit_value"
                                                    name="spec_value[<?php echo $aval['sp_value_id']; ?>][sort]"
                                                    value="<?php echo $aval['sp_value_sort']; ?>"/></td>
                        <td class="w25pre name"><input type="text" class="change_default_submit_value"
                                                       name="spec_value[<?php echo $aval['sp_value_id']; ?>][name]"
                                                       value="<?php echo $aval['sp_value_name']; ?>"/></td>

                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
            <tbody>
            <tr>
                <td colspan="15"><a id="add_type" class="btn-add marginleft" href="JavaScript:void(0);">
                        <span>添加一个规格值</span> </a></td>
            </tr>
            </tbody>
            <tfoot>
            <tr class="tfoot">
                <td colspan="15"><a id="submitBtn" class="btn" href="JavaScript:void(0);">
                        <span><?php echo $lang['nc_submit']; ?></span> </a></td>
            </tr>
            </tfoot>
        </table>
    </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/common_select.js" charset="utf-8"></script>
<script type="text/javascript">
    $(function () {
        //添加规格值
        var i = 0;
        var tr_model = '<tr class="hover edit">' +
            '<td class="w48"><a onclick="remove_tr($(this));" href="JavaScript:void(0);"><?php echo $lang['nc_del'];?></a></td>' +
            '<td class="w48 sort"><input type="text" class="change_default_submit_value" name="spec_value[key][sort]" value="0" /></td>' +
            '<td class="w25pre name"><input type="text" class="change_default_submit_value" name="spec_value[key][name]" value="" /></td>' +
            '</tr>';
        $("#add_type").click(function () {
            $('#tr_model > tr:last').after(tr_model.replace(/key/g, 's_' + i));
            $.getScript(RESOURCE_SITE_URL + "/js/admincp.js");
            i++;
        });

        // 编辑分类时清除分类信息
        $('.edit_gcategory').click(function () {
            $('input[name="class_id"]').val('');
            $('input[name="class_name"]').val('');
        });
        //表单验证
        $('#spec_form').validate({
            errorPlacement: function (error, element) {
                error.appendTo(element.parent().parent().prev().find('td:first'));
            },

            rules: {
                s_name: {
                    required: true,
                    maxlength: 10,
                    minlength: 1
                },
                s_sort: {
                    required: true,
                    digits: true
                }
            },
            messages: {
                s_name: {
                    required: '<?php echo $lang['spec_add_name_no_null'];?>',
                    maxlength: '<?php echo $lang['spec_add_name_max'];?>',
                    minlength: '<?php echo $lang['spec_add_name_max'];?>'
                },
                s_sort: {
                    required: '<?php echo $lang['spec_add_sort_no_null'];?>',
                    digits: '<?php echo $lang['spec_add_sort_no_digits'];?>'
                }
            }
        });

        //按钮先执行验证再提交表单
        $("#submitBtn").click(function () {
            $("#spec_form").submit();
        });
    });
    gcategoryInit('gcategory');

    //删除规格值
    function remove_tr(o) {
        o.parents('tr:first').remove();
    }

</script> 