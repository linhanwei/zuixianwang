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
            var $this = $(this);
            if($this.data("events") == undefined && $this.attr('href') != undefined) {
                $this.bind('click',function(){
                    if ($this.attr('href') != '' && $this.attr('href') != 'javascript:history.go(-1);') {
                        if ($this.attr('href').indexOf('http') == 0) {
                            app_interface.openWebView($this.attr('href'), 1); return false;
                        } else if ($this.attr('href').indexOf('tmpl') >= 0) {
                            app_interface.openWebView(WapSiteUrl + '/' + $this.attr('href'), 1); return false;
                        }
                    } else if ($this.attr('href') == 'javascript:history.go(-1);') {
                        app_interface.closeWebView(2); return false;
                    }
                });
            }
        })

    }else{
        $(".footer").show();
    }
}

function is_app(){
    return typeof(app_interface) == 'object';
}
function bind_login(func){
    $('#open_login').click(function(){
        app_interface.openWebView(WapSiteUrl + '/tmpl/member/login.html?func='+func,8);
    });
}

function app_check_login(key){
    if(key == ''){
        if(is_app()){
            app_interface.openWebView(WapSiteUrl + '/tmpl/member/login.html',8);return;
        }else{
            window.location.href = WapSiteUrl + '/tmpl/member/login.html';
        }
    }
}
window.app_alert = function(s,t){
    if(is_app()){
        if(t == ''){
            t = '醉仙网';
        }
        app_interface.showAlert(t,s);
    }else{
        alert(s);
    }
}
window.app_toast = function(s){
    if(is_app()){
        app_interface.showToast(s,1);
    }else{
        alert(s);
    }

}

window.app_confirm = function(s,t,confirm_word,confirm_callback,cancel_word,cancel__callback){
    if(is_app()){
        if(t == ''){
            t = '醉仙网';
        }
        app_interface.showAlert(t,s,confirm_word,confirm_callback,cancel_word,cancel__callback);
    }else{
        if(confirm(s)){
            eval(confirm_callback);
        }else{
            eval(cancel__callback);
        }
    }
}

window.app_cart_count = function(count){
    if(is_app()){
        app_interface.updateCartListAmount(count);
    }
}

window.loading = function(is_del){
    if(is_del){
        $('div').remove('#loading');
    }else{
        $('body').append('<div id="loading"></div>');
    }
}

bind_openwebview();

