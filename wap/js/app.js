
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

//获取登录key值
function getLoginKey(){
    var loginKey = getcookie('key');
    if(!loginKey) loginKey = null;
    return loginKey;
}

