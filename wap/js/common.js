//清除manifest缓存
if(GetQueryString('bug')){
    console.log('清除manifest缓存成功!');
    window.applicationCache.update();
}

//设置本地缓存
function setCache(k,v){
    if(window.localStorage) {
        try {
            localStorage.setItem(k, JSON.stringify(v));
        } catch (oException) {
            if (oException.name == 'QuotaExceededError') {
                console.log('超出本地存储限额！');
            }
        }
    }
}

//获取本地缓存
function getCache(k){
    if(window.localStorage) {
        var localStorageVal = localStorage.getItem(k);
        if(localStorageVal){
            return JSON.parse(localStorageVal);
        }
    }
}

//删除某个本地缓存值
function delCache(k){
    if(window.localStorage) {
        localStorage.removeItem(k);
    }
}

//删除所有的本地缓存值
function clearCache(){
    if(window.localStorage) {
        localStorage.clear();
    }
}

function GetQueryString(name){
    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if (r!=null) return decodeURI(r[2]); return null;
}

function addcookie(name,value,expireHours){
    var cookieString=name+"="+escape(value)+"; path=/";
    //判断是否设置过期时间
    if(expireHours>0){
        var date=new Date();
        date.setTime(date.getTime+expireHours*3600*1000);
        cookieString=cookieString+"; expire="+date.toGMTString();
    }
    document.cookie=cookieString;
}

function getcookie(name){
    var strcookie=document.cookie;
    var arrcookie=strcookie.split("; ");
    for(var i=0;i<arrcookie.length;i++){
        var arr=arrcookie[i].split("=");
        if(arr[0]==name)return arr[1];
    }
    return "";
}

function delCookie(name){//删除cookie
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval=getcookie(name);
    if(cval!=null) document.cookie= name + "="+cval+"; path=/;expires="+exp.toGMTString();
}

function checklogin(state){
    if(state == 0){
        location.href = WapSiteUrl+'/tmpl/member/login.html';
        return false;
    }else {
        return true;
    }
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

function buildUrl(type, data) {
    switch (type) {
        case 'keyword':
            return WapSiteUrl + '/tmpl/product_list.html?keyword=' + encodeURIComponent(data);
        case 'special':
            return SiteUrl + '/mall_m/index.php?act=mb_special&op=index&sp_id=' + data;
        //return WapSiteUrl + '/special.html?special_id=' + data;
        case 'goods':
            return SiteUrl + '/mall_m/index.php?act=goods&op=detail&goods_id=' + data;
        //return WapSiteUrl + '/tmpl/product_detail.html?goods_id=' + data;
        case 'url':
            return data;
    }
    return WapSiteUrl;
}

if(typeof($) != "undefined"){
    $.fn.ajaxUploadImage = function(e) {
    var t = {
        url: "",
        data: {},
        start: function() {},
        success: function() {}
    };
    var e = $.extend({},
        t, e);
    var a;
    function n() {
        if (a === null || a === undefined) {
            alert("请选择您要上传的文件！");
            return false
        }
        return true
    }
    return this.each(function() {
        $(this).on("change",
            function() {
                var t = $(this);
                e.start.call("start", t);
                a = t.prop("files")[0];
                if (!n) return false;
                try {
                    var r = new XMLHttpRequest;
                    r.open("post", e.url, true);
                    r.setRequestHeader("X-Requested-With", "XMLHttpRequest");
                    r.onreadystatechange = function() {
                        if (r.readyState == 4) {
                            returnDate = $.parseJSON(r.responseText);
                            e.success.call("success", t, returnDate)
                        }
                    };
                    var i = new FormData;
                    for (k in e.data) {
                        i.append(k, e.data[k])
                    }
                    i.append(t.attr("name"), a);
                    result = r.send(i)
                } catch(o) {
                    console.log(o);
                    alert(o)
                }
            })
    })
};
}