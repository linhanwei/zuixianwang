$(function() {
    var key = getcookie('key');
    var memberHtml = '';
    var act = GetQueryString("act");
    var url = location.href;
    var index_action = '',
        cate_list_action = '',
        product_list_action = '',
        cart_list_action = '',
        member_action = '';

    if(url.indexOf('/wap/index.html') > 0){
        index_action='action';
    }
    if(url.indexOf('/tmpl/cate_list.html') > 0){
        cate_list_action='action';
    }
    if(url.indexOf('/tmpl/product_list.html') > 0){
        product_list_action='action';
    }
    if(url.indexOf('/tmpl/cart_list.html') > 0){
        cart_list_action='action';
    }
    if(url.indexOf('/member/member.html') > 0){
        member_action='action';
    }

    var html = '<a href="' + SiteUrl + '/wap/index.html" class="home '+index_action+'" ><span></span><p>首页</p></a>'+
                '<a href="' + SiteUrl + '/wap/tmpl/cate_list.html" class="cate '+cate_list_action+'" ><span></span><p>分类</p></a>'+
                '<a href="' + SiteUrl + '/wap/tmpl/product_list.html" class="middle '+product_list_action+'"><img src="' + SiteUrl + '/wap/images/ico/middle-ico.png"></a>'+
                '<a href="' + SiteUrl + '/wap/tmpl/cart_list.html" class="cart '+cart_list_action+'" ><span></span><p>购物车</p></a>'+
                '<a href="' + SiteUrl + '/wap/tmpl/member/member.html" class="member '+member_action+'" ><span></span><p>我的</p></a>';

    // console.log(url);  //url.indexOf('zero/list.html') > 0

    var footer_class_name = 'footer';
    $("."+footer_class_name).html(html);

    //显示底部导航
    if(client == 'wechat' || GetQueryString('bug')){
        $("."+footer_class_name).css('display','block');
    }

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

});