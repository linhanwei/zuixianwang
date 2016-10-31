;(function() {
    // 定义省市简称列表
    var cityShortName = '京|津|冀|晋|辽|吉|黑|沪|苏|浙|皖|闽|赣|鲁|豫|鄂|湘|粤|桂|琼|渝|川|蜀|黔|贵|滇|云|藏|陕|秦|甘|陇|青|宁|新|港|澳|台|内蒙古|';
    // 定义投保流程中的验证规则
    var InsureValidate = {
    		/*****
    		 * 1.验证中文字符
    		 * 2.验证车牌号
    		 * 3.验证身份证号码
    		 * 4.验证email
    		 * 5.验证手机号码
    		 * 6.校验车主姓名
    		 * 7.校验地址
    		 * 8.验证港澳回乡证或台胞证
    		 * 9.验证是否为证件号码
    		 */
        // 是否中文字符
        isChinese : function(str) {
            return /^[\u4e00-\u9fa5]+$/.test(str);
        },
        // 检查是否车牌号码
        isLicenseNo : function(str) {
            var result = true;

            if(!/^[\u4e00-\u9fa5][a-zA-Z]([a-zA-Z]|\d){5}$/.test(str)) {
                result = false;
            } else if(cityShortName.indexOf(str.substring(0, 1)) === -1) {
                result = false;
            }
            return result;
        },
        // 检查身份证号码
        checkIdCard : function(k) {
            var pa = {};
            k = k.toUpperCase();
            var m = [true, "您输入的身份证号码位数不对!", "您输入的身份证号码错误!", "您输入的身份证号码错误!", "您输入的身份证号码错误!", "您输入的身份证号码错误!"];
            if (!k) {
            	return m[2];
            }
            var c = {
                11 : "\u5317\u4eac",
                12 : "\u5929\u6d25",
                13 : "\u6cb3\u5317",
                14 : "\u5c71\u897f",
                15 : "\u5185\u8499\u53e4",
                21 : "\u8fbd\u5b81",
                22 : "\u5409\u6797",
                23 : "\u9ed1\u9f99\u6c5f",
                31 : "\u4e0a\u6d77",
                32 : "\u6c5f\u82cf",
                33 : "\u6d59\u6c5f",
                34 : "\u5b89\u5fbd",
                35 : "\u798f\u5efa",
                36 : "\u6c5f\u897f",
                37 : "\u5c71\u4e1c",
                41 : "\u6cb3\u5357",
                42 : "\u6e56\u5317",
                43 : "\u6e56\u5357",
                44 : "\u5e7f\u4e1c",
                45 : "\u5e7f\u897f",
                46 : "\u6d77\u5357",
                50 : "\u91cd\u5e86",
                51 : "\u56db\u5ddd",
                52 : "\u8d35\u5dde",
                53 : "\u4e91\u5357",
                54 : "\u897f\u85cf",
                61 : "\u9655\u897f",
                62 : "\u7518\u8083",
                63 : "\u9752\u6d77",
                64 : "\u5b81\u590f",
                65 : "\u65b0\u7586",
                71 : "\u53f0\u6e7e",
                81 : "\u9999\u6e2f",
                82 : "\u6fb3\u95e8",
                91 : "\u56fd\u5916"
            };
            var b, l;
            var e, j;
            var a = [];
            
            // 前后各四位为明文中间为掩码验证
            var checkMask15Reg = /^[1-9][0-9]{3}\*{7}[0-9]{4}$/,   
            	checkMask18Reg = /^[1-9][0-9]{3}\*{10}[0-9]{3}(\d|[Xx]){1}$/;
            a = k.split("");
            if(k === "") {
                return true;
            }
            /*
            if(parseInt(k.substr(0, 4)) == 1234){		//测试环境上，数据库为做信息保护，测试人员提供数据一律是1234开头的测试身份证号码
            	return true;
            }
            //放宽身份证城市的验证，因为有些测试数据不规范
            
            if(c[parseInt(k.substr(0, 2))] == null) {
                return m[4]; 
            }*/
            var checkMask = false; 
            switch(k.length) {
                case 15:
                    if((parseInt(k.substr(6, 2)) + 1900) % 4 === 0 || ((parseInt(k.substr(6, 2)) + 1900) % 100 === 0 && (parseInt(k.substr(6, 2)) + 1900) % 4 === 0)) {
                        ereg = /^[1-9][0-9]{5}[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|[1-2][0-9]))[0-9]{3}$/;
                    } else {
                        ereg = /^[1-9][0-9]{5}[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|1[0-9]|2[0-8]))[0-9]{3}$/;
                    }
                    checkMask = checkMask15Reg.test(k);
                    if(ereg.test(k) || checkMask) {
                    	if (checkMask) {
                    		return true; 
                    	}
                        var o = k.substr(6, 2);
                        var f = k.substr(8, 2);
                        var n = k.substr(10, 2);
                        if(pa && pa.oServerDate) {
                            var g = pa.oServerDate;
                        } else {
                            var h = new Date();
                            var g = new Date(h.getFullYear(), h.getMonth(), h.getDate());
                        }
                        var d = new Date(o, parseInt(f, 10) - 1, n);
                        if((Date.parse(d) - Date.parse(g)) >= 0) {
                            return m[5];
                        }
                        return m[0];
                    } else {
                        return m[2];
                    }
                    break;
                case 18:
                    if(parseInt(k.substr(6, 4)) % 4 === 0 || (parseInt(k.substr(6, 4)) % 100 === 0 && parseInt(k.substr(6, 4)) % 4 === 0)) {
                        ereg = /^[1-9][0-9]{5}(19|20)[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|[1-2][0-9]))[0-9]{3}[0-9Xx]$/;
                    } else {
                        ereg = /^[1-9][0-9]{5}(19|20)[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|1[0-9]|2[0-8]))[0-9]{3}[0-9Xx]$/;
                    }
                    checkMask = checkMask18Reg.test(k);
                    if(ereg.test(k) || checkMask) {
                    	if (checkMask) {
                    		return true; 
                    	}
                        e = (parseInt(a[0]) + parseInt(a[10])) * 7 + (parseInt(a[1]) + parseInt(a[11])) * 9 + (parseInt(a[2]) + parseInt(a[12])) * 10 + (parseInt(a[3]) + parseInt(a[13])) * 5 + (parseInt(a[4]) + parseInt(a[14])) * 8 + (parseInt(a[5]) + parseInt(a[15])) * 4 + (parseInt(a[6]) + parseInt(a[16])) * 2 + parseInt(a[7]) * 1 + parseInt(a[8]) * 6 + parseInt(a[9]) * 3;
                        b = e % 11;
                        j = "F";
                        l = "10X98765432";
                        j = l.substr(b, 1);
                        if(j == a[17]) {
                            var o = k.substr(6, 4);
                            var f = k.substr(10, 2);
                            var n = k.substr(12, 2);
                            if(pa && pa.oServerDate) {
                                var g = pa.oServerDate;
                            } else {
                                var h = new Date();
                                var g = new Date(h.getFullYear(), h.getMonth(), h.getDate());
                            }
                            var d = new Date(o, parseInt(f, 10) - 1, n);
                            if((Date.parse(d) - Date.parse(g)) >= 0) {
                                return m[5];
                            }
                            return m[0];
                        } else {
                            return m[3];
                        }
                    } else {
                        return m[2];
                    }
                    break;
                default:
                    return m[1];
            }
        },
        /**
         *验证email中是否带有空格
         */
        emailSpaceCheck : function(objValue) {
            //var emailegi = /^[\w\-\.]+@[a-zA-Z0-9]+(\-[a-zA-Z0-9]+)?(\.[a-zA-Z0-9]+(\-[a-zA-Z0-9]+)?)*\.[a-zA-Z]{2,4}$/;
            // 扩展掩码规则验证 第一位和@前一位为明文中间*号
            var emailegi = /^([\w-.]+|[\w-.]{1}\*+[\w-.]{1})@[a-zA-Z0-9]+(\-[a-zA-Z0-9]+)?(\.[a-zA-Z0-9]+(\-[a-zA-Z0-9]+)?)*\.[a-zA-Z]{2,4}$/;
            //email
            if(!emailegi.test(objValue)) {
                return false;
            }
            return true;
        },
        /**
         * modify by 20140611 CA 去掉手机号校验
         * 验证手机号码是否有效
         */
//        validateMobile : function(objValue) {
//            //var phonegi = /^((13|14|15|18)\d{9,10})?$/;
//            var phonegi = /^1[3458]\d{1}(\*{4}\d{4}|\d{8})$/;  // 支持 "139****8888"中间带*号匹配
//            var mes = [true, '您未填写手机号码，请填写', '手机号码不合规范,请您重新检查', '手机号码为11位数字,请您重新检查'];
//            if(objValue == " ") {
//                return mes[1];
//            }
//            if(objValue.length != 11) {
//                return mes[3];
//            }
//            if(!phonegi.test(objValue)) {
//                return mes[2];
//            }
//            return mes[0];
//        },
        validateMobile : function(objValue) {
            //var phonegi = /^((13|14|15|18)\d{9,10})?$/;
            var phonegi = /^1[34578]\d{1}(\*{4}\d{4}|\d{8})$/;  // 支持 "139****8888"中间带*号匹配
            var mes = [true, '您未填写手机号码，请填写', '手机号码不合规范,请您重新检查', '手机号码为11位数字,请您重新检查'];
            if(objValue == " ") {
                return mes[1];
            }
            if(objValue.length != 11) {
                return mes[3];
            }
            if(!phonegi.test(objValue)) {
                return mes[2];
            }
            return mes[0];
        },
        /*
         *校验车主姓名
         */
        validatetouName : function(objValue) {
            objValue = $.trim(objValue.toUpperCase());
            //改成大写
            objValue = objValue.replace(/\s+/g, ' ');

            /*<%--只能输入字母和中文--%>*/
            if(objValue == ' ') {
                return '姓名不能为空';
            }
            var totalLength = objValue.length;
            /*<%--中文--%>*/
            var cnArray = objValue.match(/[\u4e00-\u9fa5]/g);
            if(cnArray){
                totalLength += cnArray.length;
            }
            for(var i = 0; i < objValue.length; i++) {
                if(objValue.charCodeAt(i) == 183) {
                    if(i == 0 || i == objValue.length - 1){
                    	return "姓名格式不正确";
                    }
                    if(objValue.charCodeAt(i + 1) == 183){
                    	return "姓名格式不正确";
                    }
                }
            }

            var filterArr = ['公司', '有限', '集团', '股份', '大学'], filterLen = filterArr.length;
            while(filterLen--) {
                if(objValue.indexOf(filterArr[filterLen]) > -1) {
                    return "抱歉，网上不支持企业用车投保";
                }
            }
            var filterArr2 = ['不详', '不祥', '不知道', '未知'], filterLen2 = filterArr2.length;
            while(filterLen2--) {
                if(objValue.indexOf(filterArr2[filterLen2]) > -1) {
                    return "姓名格式不正确，请重新输入";
                }
            }
            if(totalLength > 30 || totalLength < 4) {
                return "姓名需为2-15个中文字符或4-30个英文字符,请您重新检查";
            }

            var namegi = /^(([\u4e00-\u9fa5a-zA-Z·]*[\u4e00-\u9fa5]+[\u4e00-\u9fa5a-zA-Z·]*)|([a-zA-Z\s]+))$/;
            if(!namegi.test(objValue)) {//字符逐个判断
                return "姓名需为2-15个中文字符或4-30个英文字符,请您重新检查";
            }

            return true;
        },
        /*
         *校验姓名
         */
        validateName : function(objValue) {
            objValue = $.trim(objValue.toUpperCase());
            //改成大写
            objValue = objValue.replace(/\s+/g, ' ');

            /*<%--只能输入字母和中文--%>*/
            if(objValue == ' '){
            	return '姓名不能为空';
            }
            var totalLength = objValue.length;
            /*<%--中文--%>*/
            var cnArray = objValue.match(/[\u4e00-\u9fa5]/g);
            if(cnArray) {
                totalLength += cnArray.length;
            }
            for(var i = 0; i < objValue.length; i++) {
                if(objValue.charCodeAt(i) == 183) {
                    if(i == 0 || i == objValue.length - 1) {
                        return "姓名格式不正确";
                    }
                    if(objValue.charCodeAt(i + 1) == 183) {
                        return "姓名格式不正确";
                    }
                }
            }

            var filterArr2 = ['不详', '不祥', '不知道', '未知'], filterLen2 = filterArr2.length;
            while(filterLen2--) {
                if(objValue.indexOf(filterArr2[filterLen2]) > -1) {
                    return "姓名格式不正确，请重新输入";
                }
            }
            if(totalLength > 30 || totalLength < 4) {
                return "姓名需为2-15个中文字符或4-30个英文字符,请您重新检查";
            }

            var namegi = /^[\u4e00-\u9fa5a-zA-Z·]*[\s]*[\u4e00-\u9fa5a-zA-Z·]*$/;
            if(!namegi.test(objValue)) {//字符逐个判断
                return "姓名需为2-15个中文字符或4-30个英文字符,请您重新检查";
            }

            return true;
        },
        /*
         * 校验地址
         */
        validateRecAddress : function(objValue) {
            var mes = [true, '地址不能为空', '地址不符合规范,请正确填写', '地址不能少于6个汉字或12个字母'];
            var addressegi = /^[a-zA-Z\u4e00-\u9fa50-9\-\#\,]*[a-zA-Z\u4e00-\u9fa5]+[a-zA-Z\u4e00-\u9fa50-9\-\#\,]*$/;
            //地址不能为全数字
            objValue = $.trim(objValue.toUpperCase());
            //改成大写
            // objValue = objValue.replace(/\s+/g, ''); //stz 前台验证不让后台出现错误提示以出现正则表达式的错误提示 '打算放四大 1234的'会出错
            if(objValue.replace(/\s+/g, '') == ' '){ //objValue stz
                return mes[1];
            }
            var totalLength = objValue.length;
            /*<%--中文--%>*/
            var cnArray = objValue.match(/[\u4e00-\u9fa5]/g);
            if(cnArray){
            	totalLength += cnArray.length;
            }
            if(totalLength < 12) {
                return mes[3];
            }
            if(!addressegi.test(objValue)) {
                return mes[2];
            }
            return mes[0];
        },
        /**
         * 验证港澳回乡证或台胞证
         */
        validateGangao : function(value) {
            value = value.toUpperCase();
            if(value.length < 8) {
                return false;
            } else if(!/^(\([a-zA-Z\d*]*[a-zA-Z\d]{4}\)|[a-zA-Z\d*]*[a-zA-Z\d]{4})$/.test(value)) {
            	// /^(\([a-zA-Z\d]*\)|[a-zA-Z\d])*$/
            	// /^(\([a-zA-Z\d*]*[a-zA-Z\d]{4}\)|[a-zA-Z\d*]*[a-zA-Z\d]{4})$/ 支持 前面*号掩码后四位明文
                return false;
            }
            return true;
        },
        /**
         * 验证是否为证件号码
         */
        validateIdNo : function(value) {
            return /^[a-zA-Z\d\s]*$/.test(value);
        },
     
        validateMail : function(mail) {
            if (/^([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i.test(mail)){
                return true; 
            }
            else{
            	return '请输入正确邮箱。'; 
            }
        }
         
    };

    window.InsureValidate = InsureValidate;

})();
