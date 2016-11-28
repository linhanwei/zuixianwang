
//搜索
$('.search-btn').click(function(){
    var cache_key = 'search_key_list';
    var search_list = getCache(cache_key);
    var keyword_val = $('#keyword').val();
    var keyword = encodeURIComponent(keyword_val);
    search_list = search_list ? search_list : [];

    if(search_list.length > 0){
        for (var f1 in search_list) {
            if (search_list[f1].indexOf(keyword_val) == -1) {
                search_list.push(keyword_val);
                setCache(cache_key,search_list);
            }
        }
    }else{
        search_list.push(keyword_val);
        setCache(cache_key,search_list);
    }
    var target = $(this).attr('target');
    location.href = WapSiteUrl+'/tmpl/' + target + '.html?keyword='+keyword;
});


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
        localStorageVal = JSON.parse(localStorageVal);

        if(localStorageVal == undefined || localStorageVal == null){
            localStorageVal = '';
        }
        return localStorageVal;
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
        }
    });
}

function GetQueryString(name){
    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if (r!=null) return decodeURI(r[2]); return '';
}
function getQueryString(name) {
    return  GetQueryString(name);
}

function addcookie(name,value,expireHours){
    setCache(name,value);
    return false;
}

function getcookie(name){
    var loginKey = getCache(name);
    if(loginKey == undefined){
        loginKey = '';
    }
    return loginKey;
}

function delCookie(name){//删除cookie
    return delCache(name);
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
            return WapSiteUrl + '/tmpl/search_list.html?keyword=' + encodeURIComponent(data);
        case 'cate':
            return WapSiteUrl + '/tmpl/search_list.html?gc_id=' + data;
        case 'special':
            return SiteUrl + '/mall_m/index.php?act=mb_special&op=index&sp_id=' + data;
            //return WapSiteUrl + '/special.html?special_id=' + data;
        case 'goods':
            return WapSiteUrl + '/tmpl/product_detail.html?goods_id=' + data;
            //return WapSiteUrl + '/tmpl/product_detail.html?goods_id=' + data;
        case 'url':
            return data;
    }
    return 'javascript:void(0);';
    return WapSiteUrl;
}
/**
 * 动态加载css文件
 * @param css_filename css文件路径
 */
function loadCss(css_filename) {
    var link = document.createElement('link');
    link.setAttribute('type', 'text/css');
    link.setAttribute('href', css_filename);
    link.setAttribute('href', css_filename);
    link.setAttribute('rel', 'stylesheet');
    css_id = document.getElementById('auto_css_id');
    if (css_id) {
        document.getElementsByTagName('head')[0].removeChild(css_id);
    }
    document.getElementsByTagName('head')[0].appendChild(link);
}
/**
 * 动态加载js文件
 * @param script_filename js文件路径
 */
function loadJs(script_filename) {
    var script = document.createElement('script');
    script.setAttribute('type', 'text/javascript');
    script.setAttribute('src', script_filename);
    script.setAttribute('id', 'auto_script_id');
    script_id = document.getElementById('auto_script_id');
    if (script_id) {
        document.getElementsByTagName('head')[0].removeChild(script_id);
    }
    document.getElementsByTagName('head')[0].appendChild(script);
}

