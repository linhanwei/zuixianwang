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
    if(client != 'wechat'){
        $('a').each(function(){
            $(this).click(function(){
                var $this = $(this);
                if($this.attr('href') != undefined && $this.attr('href') != 'javascript:void(0);') {
                    if ($this.attr('href') != '' && $this.attr('href') != 'javascript:history.go(-1);') {
                        if ($this.attr('href').indexOf('http') == 0) {
                            app_interface.openWebView($this.attr('href'), 1); return false;
                        } else if ($this.attr('href').indexOf('tmpl') >= 0) {
                            app_interface.openWebView(WapSiteUrl + '/' + $this.attr('href'), 1); return false;
                        }

                    } else if ($this.attr('href') == 'javascript:history.go(-1);') {
                        app_interface.closeWebView(2); return false;
                    }

                }
            });
        })

    }else{
        $(".footer").show();
    }
}

function bind_login(){
    $('#open_login').click(function(){
        app_interface.openWebView(WapSiteUrl + '/tmpl/member/login.html',8);
    });
}