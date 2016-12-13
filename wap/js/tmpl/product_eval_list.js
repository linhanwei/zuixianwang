var goods_id = getQueryString("goods_id");
$(function () {
    var o = new scrollLoad;
    o.loadInit({
        url: ApiUrl + "/index.php?act=goods&op=goods_evaluate",
        getparam: {goods_id: goods_id},
        tmplid: "product_ecaluation_script",
        containerobj: $("#product_evaluation_html"),
        iIntervalId: true,
        callback: function () {
            callback()
        }
    });

    $(".tag-nav").find("a").click(function () {
        var i = $(this).attr("data-state");
        o.loadInit({
            url: ApiUrl + "/index.php?act=goods&op=goods_evaluate",
            getparam: {goods_id: goods_id, type: i},
            tmplid: "product_ecaluation_script",
            containerobj: $("#product_evaluation_html"),
            iIntervalId: true,
            callback: function () {
                callback()
            }
        });
        $(this).parent().addClass("selected").siblings().removeClass("selected")
    })
});
function callback() {
    //浏览评论图片
    $('.eval-con .goods_geval a').click(function(){

        var eval_info = {};
        var img_url_list = [];
        var eval_cont = '';
        var select_num = 0;
        var select_img = '';
        var img_length = $(this).parent().find('img').length;
        eval_info.eval_cont = eval_cont;
        eval_info.select_num = select_num;
        eval_info.select_img = select_img;
        eval_info.img_list = img_url_list;

        if(img_length > 0){
            eval_cont = $(this).parent().parent().siblings('.eval-con').find('dt').text();
            for(var i=0;i<img_length;i++){
                var img_url = $(this).parent().children('a').eq(i).find('img').attr('src');
                img_url = img_url.replace("_240", "");
                img_url_list.push(img_url);
            }

            select_num = $(this).index();
            select_img = $(this).find('img').attr('src');

            eval_info.eval_cont = eval_cont;
            eval_info.select_num = select_num;
            eval_info.select_img = select_img;
            eval_info.img_list = img_url_list;

        }

        if(is_app()){
            app_interface.showEvaluateInfo(select_num,img_url_list,eval_cont);
        }
        console.log(eval_info);
    });
    /*$(".goods_geval a").click(function () {
        var o = $(this).parents(".goods_geval");
        o.find(".bigimg-layout").removeClass("hide");
        var i = o.find(".pic-box");
        o.find(".close").click(function () {
            o.find(".bigimg-layout").addClass("hide")
        });
        if (i.find("li").length < 2) {
            return
        }

        Swipe(i[0], {
            speed: 400,
            auto: 3e3,
            continuous: false,
            disableScroll: false,
            stopPropagation: false,
            callback: function (o, i) {
                $(i).parents(".bigimg-layout").find("div").last().find("li").eq(o).addClass("cur").siblings().removeClass("cur")
            },
            transitionEnd: function (o, i) {
            }
        })
    })*/
}