;(function(win ,hj){

	var doc = win.document;

	/**
	 *	创建蒙层 
	 */

	//蒙层构造函数
	var Overlay = function() {
		this.shadow = null;
	}

	//扩展蒙层原型
	Overlay.prototype = {
		createShadow : function() {
			this.shadow = document.createElement('div');
			this.shadow.setAttribute('class', 'u-h-shadow');
			doc.body.appendChild(this.shadow);
		},
		show : function() {
			if(!this.shadow) {
				this.createShadow();
			}
			this.shadow.style.display = "block";
		},
		hide : function() {
			if(!this.shadow) {
				this.createShadow();	
			}
			this.shadow.style.display = 'none';
		}
	}
	//提供给alert弹出框使用
	win.layer = new Overlay();

	/**
	 *	创建弹出框
	 */

  //弹出层构造函数
	var Alert = function(options, callback){

		this.winPop = document.createElement('div');//弹出的对画框body
		this.confirmBtn = null;//底部按扭公共外层
		this.valContent = options.valContent;  //传进来的值
		this.valTitle = options.valTitle || ""; //传进来的title
		this.callback = callback || function() {}; //回调方法
		this.btnLeftVal = options.btnLeftVal || '确定'; //按钮文字
		this.tipsCls = options.tipsCls; //特殊样式处理
		this.closeCallback = options.closeCallback; // 右上角关闭按钮
		if(this.valContent && options.confirm == 'confirm'){ // 有取消按钮对话框
			layer.show();
			this.createConfirmWin();
		} else { //提示对话框
			layer.show();
			this.createAlertWin();			
		}

	};

	//扩展弹出层原型
	Alert.prototype = {

		ce: function(ele) {return doc.createElement(ele);},

		// 创建弹出框公共框架
		createCommonDiv: function(parames){

			var prefix = parames || ""; //对话框类型标识符
			var contentTop;
			var contentShow;
			this.winPop.setAttribute('class', prefix + ' u-h-alert');
			contentTop = this.ce('div');// 顶部
			this.closeItem = this.ce('a'); //右上角关闭按钮
			contentShow = this.ce('div');// 内容 外层
			this.contentSpn =  this.ce('span');// 里面放内容
			this.confirmBtn = this.ce('div');//底部按扭外层
			this.closeItem.setAttribute('class', 'h_alert_close');
			contentTop.setAttribute('class', 'h_alert_top');
			contentShow.setAttribute('class', 'h_alert_body');
			this.contentSpn.setAttribute('class', 'h_alert_body_content');
			this.confirmBtn.setAttribute('class', 'h_alert_bottom');
			contentShow.appendChild(this.contentSpn);
			this.winPop.appendChild(this.closeItem);
			this.winPop.appendChild(contentTop);
			this.winPop.appendChild(contentShow);
			this.winPop.appendChild(this.confirmBtn);
			this.contentSpn.innerHTML = this.valContent;//第一个参数
			contentTop.innerHTML = this.valTitle;//第二个参数
			doc.body.appendChild(this.winPop);
			this.centerWindow(this.winPop);

		},

		//弹出层居中
		centerWindow: function(ele) {
			ele.style.left = (win.innerWidth - ele.offsetWidth) / 2 + 'px';
			ele.style.top = (doc.documentElement.clientHeight - ele.offsetHeight) / 2 + 'px';
		},

		//创建弹出tips
		createAlertWin: function(){

			var _self = this;
			//创建对话框框架
			var cls = 'h_alert_tips'
			if(_self.tipsCls) { //设置新ui
				cls = 'h_alert_tips ' + _self.tipsCls;
			}
			this.createCommonDiv(cls);
			var smallBtn = _self.ce('div');//底部按扭内层
			smallBtn.setAttribute('class','h_alert_btn');
			smallBtn.innerHTML = _self.btnLeftVal;	
			this.confirmBtn.appendChild(smallBtn);
			if(_self.closeCallback&&_self.tipsCls) { //如果右上角有关闭按钮显示 样式自定义和回调函数
				if(haoJs.IS_ANDROID) {
					_self.closeItem.addEventListener("click", function(e){
						_self.hide();
						_self.closeCallback && _self.closeCallback();
		        e.preventDefault();					
					})
				} else {
					_self.closeItem.addEventListener("touchend", function(e){
						_self.hide();//隐藏对画框
						_self.closeCallback && _self.closeCallback();
		        e.preventDefault();					
					})
				}
			}
			if(haoJs.IS_ANDROID) {
				smallBtn.addEventListener("click", function(e){
					_self.hide();
					_self.callback && _self.callback();
	        e.preventDefault();					
				})
			} else {
				smallBtn.addEventListener("touchend", function(e){
					_self.hide();//隐藏对画框
					_self.callback && _self.callback();
	        e.preventDefault();					
				})
			}

		},		

		//创建confirm
		createConfirmWin: function(){

			var _self = this;
			this.createCommonDiv('h_alert_confirm');
			var smallConBtn = _self.ce('div');//确定
			var smallCanBtn = _self.ce('div');//取消
			smallConBtn.setAttribute('class','h_alert_btn_l');
			smallCanBtn.setAttribute('class','h_alert_btn_r');
			smallConBtn.innerHTML = '确定';
			smallCanBtn.innerHTML = '取消';
			this.confirmBtn.appendChild(smallConBtn);
			this.confirmBtn.appendChild(smallCanBtn);
			
			if(haoJs.IS_ANDROID) {
				//确定
				smallConBtn.addEventListener("click", function(e){
					_self.hide();
					_self.callback && _self.callback();
          e.preventDefault();					
				})
				//取消
				smallCanBtn.addEventListener("click", function(e){
					_self.hide();
          e.preventDefault();					
				})
			} else {
				//确定
				smallConBtn.addEventListener("touchend", function(e){
					_self.hide();
					_self.callback && _self.callback();
          e.preventDefault();
				})
				//取消
				smallCanBtn.addEventListener("touchend", function(e){
					_self.hide();
					_self.callback && _self.callback();
          e.preventDefault();
				})
			}

		},		

		///隐藏
		hide : function(){
			if(this.winPop) {
				this.winPop.parentNode.removeChild(this.winPop);
			}
			layer.hide();
		}
	};

	/**
	 *	创建吐司
	 */

	var Toast = function() {
		this.toast = null;
		this.toastVal = null;
		this.status = 0;
		this.times = 1200;
	}
	//扩展吐司原型
	Toast.prototype = {
		createToast : function() {
			this.toast = document.createElement('div');
			this.toast.setAttribute('class', 'u-h-toast');
			this.toastVal = document.createElement('div');
			this.toastVal.setAttribute('class', 'u_toast_val');
			this.toast.appendChild(this.toastVal);
			doc.body.appendChild(this.toast);
		},
		//弹出层居中
		centerWindow: function(ele) {
			ele.style.left = (win.innerWidth - ele.offsetWidth) / 2 + 'px';
			ele.style.top = (doc.documentElement.clientHeight - ele.offsetHeight) / 2 + 'px';
		},		
		show : function(val) {
			if(!arguments[0]) return;
			var showArg1 = arguments[1];
			var _this = this;
			if(!_this.toast) {
				_this.createToast();
			}
			if(_this.status == 1) { //一个阶段只能有一个toaster
				return;
			}
			_this.toastVal.innerHTML = val;
			_this.toast.setAttribute('class', 'u-h-toast u_toast_show');
			_this.centerWindow(this.toast); //Div block 才能获得高度
			_this.status = 1;
			window.setTimeout(function() {
				_this.hide(function() {
					showArg1&&showArg1();
				});
			}, _this.times);
			return true;
		},
		hide : function(callback) {
			var _this = this;
			if(!_this.toast) {
				_this.createToast();	
			}
			_this.toast.setAttribute('class', 'u-h-toast u_toast_hide');
			haoJs.webkitAnimationEnd({ele: _this.toast, time: _this.times/2}, function() {
				_this.status = 0;
				_this.toast.setAttribute('class', 'u-h-toast u_toast_none');
				callback&&callback();
			});
			//_this.toast.style.display = 'none';
		}
	}

	haoJs.Alert = Alert;
	haoJs.Toast = new Toast();
	
})(window, haoJs);
