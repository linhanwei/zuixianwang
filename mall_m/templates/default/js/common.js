
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

var client = 'wap';
var ua = navigator.userAgent.toLowerCase();
if (/iphone|ipad|ipod/.test(ua)) {
    client = 'ios';
} else if (/android/.test(ua)) {
    client = 'android';
}else if(/micromessenger/.test(ua)) {
    client = 'wechat';
}
if (client == 'ios') {
    var x5 =
    {
        commandQueue: [],//数组
        commandQueueFlushing: false,
        resources: {
            base: !0
        }
    };

    window.x5 = x5;

    x5.callbackId = 0;
    x5.callbacks = {};
    x5.callbackStatus =
    {
        NO_RESULT: 0,
        OK: 1,
        CLASS_NOT_FOUND_EXCEPTION: 2,
        ILLEGAL_ACCESS_EXCEPTION: 3,
        INSTANTIATION_EXCEPTION: 4,
        MALFORMED_URL_EXCEPTION: 5,
        IO_EXCEPTION: 6,
        INVALID_ACTION: 7,
        JSON_EXCEPTION: 8,
        ERROR: 9
    };

    x5.createBridge = function () {
        var bridge = document.createElement("iframe");
        bridge.setAttribute("style", "display:none;");
        bridge.setAttribute("height", "0px");
        bridge.setAttribute("width", "0px");
        bridge.setAttribute("frameborder", "0");
        document.documentElement.appendChild(bridge);
        return bridge;
    };

    x5.exec = function (service, action, options) {
        var command =
        {
            className: service,
            methodName: action,
            options: {}
        };

        for (var i = 0; i < options.length; ++i) {
            var arg = options[i];

            if (arg == undefined || arg == null) {
                continue;
            }
            else if (typeof(arg) == 'object') {
                command.options = arg;
            }
        }

        x5.commandQueue.push(JSON.stringify(command));

        if (x5.commandQueue.length == 1 && !x5.commandQueueFlushing) {
            if (!x5.bridge) {
                x5.bridge = x5.createBridge();
            }
            x5.bridge.src = "mszx:" + service + ":" + action;
        }

    };

    // 浏览器调用接口
    x5.getAndClearQueuedCommands = function () {
        var json = JSON.stringify(x5.commandQueue);
        x5.commandQueue = [];
        return json;
    };
    x5.exec("demoid", "executeJSCode_JSDict_", [{"1": "getUserKey", "2": {"success": "getUserKeyCallback"}}]);
}

//自动登录
if(client == 'android'){
    getUserKeyCallback(android_userInterface.getUserKey());
}
function getUserKeyCallback(data) {
    data = jQuery.parseJSON(data);
    addcookie('username', data.name);
    addcookie('key', data.key);
}