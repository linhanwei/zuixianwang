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

//修改a链接增加key值
function add_key(){
    $('a').each(function(k,v){
        var key = getcookie('key'),
            a_href = $(v).attr('href'),
            new_href = '';

        if(a_href && a_href.indexOf("javascript") < 0){
            var key_index = a_href.indexOf("?");
            if(key_index >= 0){
                if(a_href.indexOf("=") >= 0){
                    new_href = a_href+'&key='+key;
                }else{
                    new_href = a_href+'key='+key;
                }
            }else{
                new_href = a_href+'?key='+key;
            }

            $(v).attr('href',new_href);
            //console.log(key,key_index,k,a_href,111);
        }
    });
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


/**
 * 声明获取图片的方法
 * @param {Object} picUrl 图片的网络地址
 * @param {Object} defaultPic 默认图片
 * @param {Object} element 图片源元素
 */
function fetchImage(picUrl, defaultPic, element) {
    if(typeof(plus)=='undefined'){
        element.setAttribute("src", picUrl);
    }else{
        //将图片网络地址进行md5摘要。
        var filename = hex_md5(picUrl);
        element.setAttribute("src", defaultPic);
        //尝试加载本地图片
        plus.io.resolveLocalFileSystemURL("_downloads/" + filename, function(entry) {
            // 加载本地图片成功
            entry.file( function(file){
                if(file.size==0){
                    //console.log("2.1图片为空显示默认");
                    element.setAttribute("src", defaultPic);
                }else{
                    var path = plus.io.convertLocalFileSystemURL("_downloads/" + filename);
                    //console.log("2.1加载本地图片"+path);
                    element.setAttribute("src", path);
                }
            });
        }, function(e) {
            //加载本地图片失败，本地没有该图片，尝试从网络下载图片并保存本地，保存文件名为url摘要md5值
            var dtask = plus.downloader.createDownload(picUrl, {filename:filename}, function(d, status) {
                // 下载完成
                if (status == 200) {
                    if(d.downloadedSize==0){
                        //console.log("2.2图片为空显示默认");
                        element.setAttribute("src", defaultPic);
                    }else{
                        //console.log("2.2下载网络文件成功"+d.url);
                        element.setAttribute("src", d.url);
                    }
                }
            });
            dtask.start();
        });
    }


}

