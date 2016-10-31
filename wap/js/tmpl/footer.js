$(function() {
    var key = getcookie('key');
    //var memberHtml = '<a class="btn mr5" href="'+WapSiteUrl+'/tmpl/member/member.html?act=member"">登录</a><a class="btn mr5" href="'+SiteUrl+'/shop/api.php?act=toqq&mobile=mobile">QQ登陆</a><a class="btn mr5" href="'+WapSiteUrl+'/tmpl/member/register.html">注册</a>';
    var memberHtml = '';
    var act = GetQueryString("act");
    if ((act && act == "member") || key != '') {
        //memberHtml = '<a class="btn mr5" href="'+WapSiteUrl+'/tmpl/member/member.html?act=member"">个人中心</a><a class="btn mr5" id="logoutbtn" href="javascript:void(0);">注销</a>';
    }
    var tmpl = '<div class="footer">' + '<div class="footer-top">' + '<div class="footer-tleft">' + memberHtml + '</div>' + '<a href="javascript:void(0);"class="gotop">' + '<p>返回顶部</p><span class="gotop-icon"></span>' + '</a>' + '</div>' + '</div>';
    tmpl = '';
    var tmpl2 = '<div id="bottom">' + '<div style=" height:40px;">' + '<div id="nav-tab" style="bottom:-40px;">' + '<div id="nav-tab-btn"><i class="fa fa-chevron-down"></i></div>' + '<div class="clearfix tab-line nav">' + '<div class="tab-line-item" style="width:25%;" ><a href="' + WapSiteUrl + '"><i class="fa fa-home"></i><br>首页</a></div>' + '<div class="tab-line-item tab-categroy" style="width:25%;" ><a href="' + WapSiteUrl + '/tmpl/product_first_categroy.html"><i class="fa fa-th-list"></i><br>分类</a></div>'
        +'<!--<div class="tab-line-item" style="width:22%;line-height:40px;padding-top:5px;" ><i style="font-size:30px;" class="fa fa-chevron-circle-down"></i><br></div>-->'
        + '<div class="tab-line-item" style="width:25%;position: relative;" ><a href="' + WapSiteUrl + '/tmpl/cart_list.html"><i class="fa fa-shopping-cart"></i><br>购物车</a></div>' + '<div class="tab-line-item" style="width:25%;" ><a href="' + WapSiteUrl + '/tmpl/member/member.html?act=member"><i class="fa fa-user"></i><br>个人中心</a></div>' + '</div>' + '</div>' + '</div>' + '<div style="z-index: 10000; border-radius: 3px; position: fixed; background: none repeat scroll 0% 0% rgb(255, 255, 255); display: none;" id="myAlert" class="modal hide fade">' + '<div style="text-align: center;padding: 15px 0 0;" class="title"></div>' + '<div style="min-height: 40px;padding: 15px;" class="modal-body"></div>' + '<div style="padding:3px;height: 35px;line-height: 35px;" class="alert-footer">' + '<a style="padding-top: 4px;border-top: 1px solid #ddd;display: block;float: left;width: 50%;text-align: center;border-right: 1px solid #ddd;margin-right: -1px;" class="confirm" href="javascript:;">Save changes</a><a aria-hidden="true" data-dismiss="modal" class="cancel" style="padding-top: 4px;border-top: 1px solid #ddd;display: block;float: left;width: 50%;text-align: center;" href="javascript:;">关闭</a></div>' + '</div>' + '<div style="display:none;" class="tips"><i class="fa fa-info-circle fa-lg"></i><span style="margin-left:5px" class="tips_text"></span></div>' + '<div class="bgbg" id="bgbg" style="display: none;"></div>' + '</div>' + '</div>';
    
    var tmpl2 = '<div class="footer-top-line"></div><nav class="footer-nav"><ul>'+
                '<li><a href="' + WapSiteUrl + '"><img src="'+SiteUrl+'/data/upload/m/common/nav-ico-1.png"><p>首页</p></a></li>'+
                '<li><a href="' + SiteUrl + '/mall_m/index.php?act=goods_class&op=index"><img src="'+SiteUrl+'/data/upload/m/common/nav-ico-2.png"><p>分类</p></a></li>'+
                '<li><a href="' + SiteUrl + '/mall_m/index.php?act=goods&op=list"><img src="'+SiteUrl+'/data/upload/m/common/nav-ico-3.png"><p>搜索</p></a></li>'+
                '<li><a href="' + SiteUrl + '/mall_m/index.php?act=member_cart&op=index"><img src="'+SiteUrl+'/data/upload/m/common/nav-ico-4.png"><p>购物车</p></a></li>'+
                '<li><a href="' + SiteUrl + '/mall_m/index.php?act=member_index&op=index"><img src="'+SiteUrl+'/data/upload/m/common/nav-ico-5.png"><p>用户中心</p></a></li>'+
                '</ul></nav>';
    var render = template.compile(tmpl);
    var html = render();

    var url = location.href;
    // console.log(url);
    if(url.indexOf('zero/list.html') > 0){
        var tmpl2 = '<div id="bottom"><div style=" height:40px;">' + '<div id="nav-tab" style="bottom:-40px;">' + '<div id="nav-tab-btn"><i class="fa fa-chevron-down"></i></div>' + '<div class="clearfix tab-line nav">' + 
                    '<div class="tab-line-item" style="width:25%;" ><a href="' + WapSiteUrl + '"><i class="fa fa-home"></i><br>首页</a></div>' + 
                    '<div class="tab-line-item tab-categroy" style="width:25%;" ><a href="' + WapSiteUrl + '/tmpl/product_first_categroy.html"><i class="fa am-icon-shopping-bag"></i><br>我的0元淘</a></div>'+ 
                    // '<div class="tab-line-item" style="width:25%;position: relative;" ><a href="' + WapSiteUrl + '/tmpl/cart_list.html"><i class="fa fa-shopping-cart"></i><br>购物车</a></div>' + 
                    // '<div class="tab-line-item" style="width:25%;" ><a href="' + WapSiteUrl + '/tmpl/member/member.html?act=member"><i class="fa fa-user"></i><br>个人中心</a></div>' + 
                    '</div></div></div>' + 
                    '<div style="z-index: 10000; border-radius: 3px; position: fixed; background: none repeat scroll 0% 0% rgb(255, 255, 255); display: none;" id="myAlert" class="modal hide fade">' + 
                    '<div style="text-align: center;padding: 15px 0 0;" class="title"></div>' + 
                    '<div style="min-height: 40px;padding: 15px;" class="modal-body"></div>' + 
                    '<div style="padding:3px;height: 35px;line-height: 35px;" class="alert-footer">' + 
                    '<a style="padding-top: 4px;border-top: 1px solid #ddd;display: block;float: left;width: 50%;text-align: center;border-right: 1px solid #ddd;margin-right: -1px;" class="confirm" href="javascript:;">Save changes</a><a aria-hidden="true" data-dismiss="modal" class="cancel" style="padding-top: 4px;border-top: 1px solid #ddd;display: block;float: left;width: 50%;text-align: center;" href="javascript:;">关闭</a></div></div>' + 
                    '<div style="display:none;" class="tips"><i class="fa fa-info-circle fa-lg"></i><span style="margin-left:5px" class="tips_text"></span></div>' + 
                    '<div class="bgbg" id="bgbg" style="display: none;"></div></div></div>';
    
    }

    $("#footer").html(html + tmpl2);
    //回到顶部
    $(".gotop").click(function() {
        $(window).scrollTop(0);
    });
    var key = getcookie('key');
    $('#logoutbtn').click(function() {
        var username = getcookie('username');
        var key = getcookie('key');
        var client = 'wap';
        $.ajax({
            type: 'get',
            url: ApiUrl + '/index.php?act=logout',
            data: {
                username: username,
                key: key,
                client: client
            },
            success: function(result) {
                if (result) {
                    delCookie('username');
                    delCookie('key');
                    location.href = WapSiteUrl + '/tmpl/member/login.html';
                }
            }
        });
    });

    //修改a链接增加key值
    add_key();

    /*var headTitle = document.title;
    //当前页面
    if(headTitle == "首页"){
        $(".fa-home").parent().addClass("current");
    }else if(headTitle == "商品分类"){
        $(".fa-th-list").parent().addClass("current");
    }else if(headTitle == "购物车列表"){
        $(".fa-shopping-cart").parent().addClass("current");
    }else if(headTitle == "我的商城"){
        $(".fa-user").parent().addClass("current");
    }*/
});