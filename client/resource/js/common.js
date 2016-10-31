var member = null;
var key = getcookie('key');
function get_query_string(name){
    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if (r!=null) return unescape(r[2]); return null;
}

function addcookie(key,value,expiredays){
    key = 'gdczfamily' + key;
    var exdate=new Date();
    exdate.setDate(exdate.getDate() + expiredays);
    document.cookie=key+ "=" + escape(value) + ((expiredays==null) ? "1" : ";expires="+exdate.toGMTString());
}

function getcookie(key){
    key = 'gdczfamily' + key;

    if (document.cookie.length>0) {//先查询cookie是否为空，为空就return ""
        c_start = document.cookie.indexOf(key + "=");//通过String对象的indexOf()来检查这个cookie是否存在，不存在就为 -1　　
        if (c_start != -1) {
            c_start = c_start + key.length + 1;//最后这个+1其实就是表示"="号啦，这样就获取到了cookie值的开始位置
            c_end = document.cookie.indexOf(";", c_start);//其实我刚看见indexOf()第二个参数的时候猛然有点晕，后来想起来表示指定的开始索引的位置...这句是为了得到值的结束位置。因为需要考虑是否是最后一项，所以通过";"号是否存在来判断
            if (c_end == -1) c_end = document.cookie.length
            return unescape(document.cookie.substring(c_start, c_end));  //通过substring()得到了值。想了解unescape()得先知道escape()是做什么的，都是很重要的基础，想了解的可以搜索下，在文章结尾处也会进行讲解cookie编码细节
        }
    }
    return "";
}

function checklogin(state){
    if(state == 0){
        location.href = ClientSiteUrl+'/index.php?act=login';
        return false;
    }else {
        return true;
    }
}

function logout(){
    $.ajax({
        type:'post',
        url:ApiUrl+"/index.php?act=logout",
        data:{mobile_phone:getcookie('member_name'),client:'pc','key':getcookie('key')},
        dataType:'json',
        beforeSend:function(){
            loading();
        },
        success:function(result){
            location.href = 'index.php?act=login';
        },
        error:function(){
            ajax_error();
        }
    });
}

function contains(arr, str) {
    var i = arr.length;
    while (i--) {
        if (arr[i] === str) {
            return true;
        }
    }
    return false;
}

function isphone(inputString)
{
    var partten = /^1[3,4,5,6,7,8,9]\d{9}$/;
    var fl=false;
    if(partten.test(inputString))
    {
        //alert('是手机号码');
        return true;
    }
    else
    {
        return false;
        //alert('不是手机号码');
    }
}
//验证身份证号方法
var check_idcard=function(idcard){
    var Errors=new Array("1","身份证号码位数不对!","身份证号码出生日期超出范围或含有非法字符!","身份证号码校验错误!","身份证地区非法!");
    var area={11:"北京",12:"天津",13:"河北",14:"山西",15:"内蒙古",21:"辽宁",22:"吉林",23:"黑龙江",31:"上海",32:"江苏",33:"浙江",34:"安徽",35:"福建",36:"江西",37:"山东",41:"河南",42:"湖北",43:"湖南",44:"广东",45:"广西",46:"海南",50:"重庆",51:"四川",52:"贵州",53:"云南",54:"西藏",61:"陕西",62:"甘肃",63:"青海",64:"宁夏",65:"xinjiang",71:"台湾",81:"香港",82:"澳门",91:"国外"}
    var idcard,Y,JYM;
    var S,M;
    var idcard_array = new Array();
    idcard_array = idcard.split("");
    if(area[parseInt(idcard.substr(0,2))]==null) return Errors[4];
    switch(idcard.length){
        case 15:
            if ((parseInt(idcard.substr(6,2))+1900) % 4 == 0 || ((parseInt(idcard.substr(6,2))+1900) % 100 == 0 && (parseInt(idcard.substr(6,2))+1900) % 4 == 0 )){
                ereg = /^[1-9][0-9]{5}[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|[1-2][0-9]))[0-9]{3}$/;//测试出生日期的合法性
            }
            else{
                ereg = /^[1-9][0-9]{5}[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|1[0-9]|2[0-8]))[0-9]{3}$/;//测试出生日期的合法性
            }
            if(ereg.test(idcard))
                return Errors[0];
            else
                return Errors[2];
            break;
        case 18:
            if( parseInt(idcard.substr(6,4)) % 4 == 0 || ( parseInt(idcard.substr(6,4)) % 100 == 0 && parseInt(idcard.substr(6,4))%4 == 0 )){
                ereg = /^[1-9][0-9]{5}19[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|[1-2][0-9]))[0-9]{3}[0-9Xx]$/;//闰年出生日期的合法性正则表达式
            }
            else{
                ereg = /^[1-9][0-9]{5}19[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|1[0-9]|2[0-8]))[0-9]{3}[0-9Xx]$/;//平年出生日期的合法性正则表达式
            }
            if(ereg.test(idcard)){
                S = (parseInt(idcard_array[0]) + parseInt(idcard_array[10])) * 7 + (parseInt(idcard_array[1]) + parseInt(idcard_array[11])) * 9 + (parseInt(idcard_array[2]) + parseInt(idcard_array[12])) * 10 + (parseInt(idcard_array[3]) + parseInt(idcard_array[13])) * 5 + (parseInt(idcard_array[4]) + parseInt(idcard_array[14])) * 8 + (parseInt(idcard_array[5]) + parseInt(idcard_array[15])) * 4 + (parseInt(idcard_array[6]) + parseInt(idcard_array[16])) * 2 + parseInt(idcard_array[7]) * 1 + parseInt(idcard_array[8]) * 6 + parseInt(idcard_array[9]) * 3 ;
                Y = S % 11;
                M = "F";
                JYM = "10X98765432";
                M = JYM.substr(Y,1);
                if(M == idcard_array[17])
                    return Errors[0];
                else
                    return Errors[3];
            }
            else
                return Errors[2];
            break;
        default:
            return Errors[1];
            break;
    }
}


function countDownSixty(ele, time, callback) {
    var _self = this;
    if(parseInt(time) == -1) {
        callback && callback();
        return;
    }
    if(pubEndflag == true) { //缁撴潫鏍囪瘑绗�
        pubEndflag = false;
        return;
    }
    ele.innerHTML = time;
    setTimeout(function() {countDownSixty(ele, --time, callback)}, 1000);
}

window.alert = function (message,target,icon){
    if(typeof(icon) == 'undefined'){
        icon = 5;
    }
    if(typeof(target)=='string'){
        layer.alert(message, {icon: icon},function(index){
            $('#' + target).focus();
            layer.close(index);
        });
    }else{
        layer.alert(message, {icon: icon},target);
    }

}

function loading(){
    setTimeout(function(){
        layer.load(1, {
            shade: [0.1,'#fff'] //0.1透明度的白色背景
        });
    },500);

}

function ajax_error(){
    close_loading();
    alert('请求出错，请检查网络是否连接');
}

function close_loading(){
    setTimeout(function(){
        layer.closeAll('loading');
    },501);

}

$('#forgetPass').click(function(){
    layer.open({
        title:'找回密码',
        type: 2,
        area: ['400px', '300px'],
        fix: false,
        maxmin: false,
        content: ClientSiteUrl + '/index.php?act=login&op=get_password&pass_type=' + $(this).attr('pass_type')
    });
});