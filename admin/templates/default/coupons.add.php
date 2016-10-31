<?php defined('InSystem') or exit('Access Invalid!');?>
<style>
.switch-tab-title {font-size:14px; margin-right:15px; }
</style>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>电子消费券</h3>
      <ul class="tab-base">
          <li><a href="<?php echo urlAdmin('coupons', 'index'); ?>"><span>列表</span></a></li>
        <li><a href="<?php echo urlAdmin('coupons', 'use'); ?>"><span>使用</span></a></li>
        <li><a href="javascript:void(0);" class="current"><span>新增</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>

  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title"><h5><?php echo $lang['nc_prompts'];?></h5><span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td>
          <ul>
            <li>平台发布电子券有3种方式：</li>
            <li>1. 输入总数，以及可选输入的卡号前缀，由系统自动生成指定总数、前缀的电子券（系统自动生成部分长度为32）；</li>
            <li>2. 上传文本文件导入电子消费券号，文件中每行为一个卡号。</li>
            <li>3. 在文本框中手动输入多个电子券号，每行为一个卡号；</li>
            <li>电子券为50位之内的字母数字组合；可以设置本批次添加卡号的批次标识，方便检索。</li>
            <li>如新增的电子券号与已有的号冲突，则系统自动忽略它们。</li>
          </ul>
        </td>
      </tr>
    </tbody>
  </table>

  <form method="post" enctype="multipart/form-data" name="form_add" id="form_add">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
        <tr>
          <td colspan="2" class="required"><label class="validation">请选择发布方式:</label></td>
        </tr>
      </tbody>

      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required">
            <label class="switch-tab-title">
              <input type="radio" name="type" value="0" checked="checked" class="tabswitch" />
              输入总数，自动生成
            </label>
            <label class="switch-tab-title">
              <input type="radio" name="type" value="1" class="tabswitch" />
              上传文件，导入
            </label>
            <label class="switch-tab-title">
              <input type="radio" name="type" value="2" class="tabswitch" />
              手动输入，每行一号
            </label>
          </td>
        </tr>

        <tr class="noborder tabswitch-target">
          <td class="vatop rowform">
            总数：
            <input type="text" class="txt" name="total" style="width:40px;" />
            前缀：
            <input type="text" class="txt" name="prefix" style="width:130px;" />
          </td>
          <td class="vatop tips">请输入总数，总数为1~9999之间的整数；可以输入随机生成电子券的统一前缀，16字之内字母数字的组合</td>
        </tr>

        <tr class="noborder tabswitch-target" style="display:none;">
          <td class="vatop rowform">
            <span class="type-file-box">
              <input type="text" name="textfile" id="textfile" class="type-file-text" />
              <input type="button" name="button" id="button" value="" class="type-file-button" />
              <input type="file" name="_textfile" class="type-file-file" size="30" hidefocus="true" onchange="$('#textfile').val(this.value);" />
            </span>
          </td>
          <td class="vatop tips">请上传电子券文件，文件为纯文本格式，每行一张电子券；号码为字母数字组合，限制50字之内；不合法电子券将被自动过滤</td>
        </tr>

        <tr class="noborder tabswitch-target" style="display:none;">
          <td class="vatop rowform">
            <textarea name="manual" style="width:300px;height:150px;"></textarea>
          </td>
          <td class="vatop tips">请输入电子券，每行一个号；为字母数字组合，限制50字之内；不合法将被自动过滤</td>
        </tr>

      </tbody>

      <tbody>
      <tr>
          <td colspan="2" ><label>有效日期:</label></td>
      </tr>
      <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="to_date" name="to_date" class="txt date" value="<?php echo $_GET['query_start_date'];?>" ></td>
          <td class="vatop tips">请输入有效日期</td>
      </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation">面额(元):</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input class="txt" type="text" name="denomination" style="width:150px;" /></td>
          <td class="vatop tips">请输入面额，面额不可超过1000</td>
        </tr>

        <tr>
          <td colspan="2" class="required"><label>批次标识:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input class="txt" type="text" name="batchflag" /></td>
          <td class="vatop tips">可以输入20字之内“批次标识”，用于标识和区分不同批次添加的电子券，便于检索</td>
        </tr>

      </tbody>

      <tfoot>
        <tr class="tfoot">
          <td colspan="2" ><a href="javascript:void(0);" class="btn" id="submitBtn"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />

<script type="text/javascript">
$(function(){

    $('#to_date').datepicker({dateFormat: 'yy-mm-dd'});

$('.tabswitch').click(function() {
    var i = parseInt(this.value);
    $('.tabswitch-target').hide().eq(i).show();
});

$("#submitBtn").click(function(){
    $("#form_add").submit();
});

jQuery.validator.addMethod("r0total", function(value, element) {
    var v = parseInt(value);
    return $(":radio[name='type']:checked").val() != '0' || (value == v && v >= 1 && v <= 9999);
}, "总数必须是1~9999之间的整数");

jQuery.validator.addMethod("r0prefix", function(value, element) {
    return $(":radio[name='type']:checked").val() != '0' || this.optional(element) || /^[0-9a-zA-Z]{0,16}$/.test(value);
}, "前缀必须是16字之内字母数字的组合");

jQuery.validator.addMethod("r1textfile", function(value, element) {
    return $(":radio[name='type']:checked").val() != '1' || value;
}, "请选择纯文本格式文件");

jQuery.validator.addMethod("r2manual", function(value, element) {
    return $(":radio[name='type']:checked").val() != '2' || value;
}, "请输入电子券");

$("#form_add").validate({
    errorPlacement: function(error, element){
        error.appendTo(element.parents('tbody').find('tr:first td:first'));
    },
    rules : {
        denomination : {
            required : true,
            min: 0.01,
            max: 1000
        },
        batchflag : {
            maxlength: 20
        },
        total : {
            r0total : true
        },
        prefix : {
            r0prefix : true
        },
        textfile : {
            r1textfile : true
        },
        manual : {
            r2manual : true
        }
    },
    messages : {
        denomination : {
            required : '请填写面额',
            min : '面额不能小于0.01',
            max: '面额不能大于1000'
        },
        batchflag : {
            maxlength: '请输入20字之内的批次标识'
        }
    }
});
});
</script>
