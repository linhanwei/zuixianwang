/**
 * Created by benluo on 16/11/16.
 */

var client = 'wap';
var ua = navigator.userAgent.toLowerCase();
if (/iphone|ipad|ipod/.test(ua)) {
    client = 'ios';
} else if (/android/.test(ua)) {
    client = 'android';
}else if(/micromessenger/.test(ua)) {
    client = 'wechat';
}

function bind_openwebview(){
    if(typeof(app_interface)=='object'){
        $('a').each(function(){
            $(this).click(function(){
                var $this = $(this);
                if($this.attr('href') != '' && $this.attr('href') != 'javascript:history.go(-1);'){
                    app_interface.openWebView(WapSiteUrl + '/' + $this.attr('href'),1);
                }else if($this.attr('href') == 'javascript:history.go(-1);'){
                    app_interface.closeWebView(2);
                }
                return false;
            });
        })
    }
}
$(function() {
    //显示底部导航
    if(typeof(app_interface)=='undefined'){
        $(".footer").hide();
    }
    bind_openwebview();
});
