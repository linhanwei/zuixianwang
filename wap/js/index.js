function completed(){
    bind_openwebview();
    $("img.lazy").lazyload({effect: "fadeIn",threshold:200});
    var swiper = new Swiper('.swiper-container', {
        //pagination: '.swiper-pagination',
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
    });

    //左右转动
    $(".tejia-box").width( (($(window).width()-30)/3 + 10) *$(".tejia-box").find(".item").length  );
    $(".tejia-box").find(".item").width(($(window).width()-30)/3);
    $("#index-tj-arrow-left").hide();
    $("#index-tj-arrow-left").on('click',function(){
        var tjl = $(".tejia-box").offset().left;
        var il  = $(".tejia-box").find(".item").length;
        var iw  = $(".tejia-box").find(".item").width();

        if(tjl < 0 )
            $(".tejia-box").animate({marginLeft:(iw+10+tjl)+'px'});

        if(tjl > -1*(iw+10)){
            $("#index-tj-arrow-left").hide();
        }

        if(tjl > -1*(iw+10)*(il-1)){
            $("#index-tj-arrow-right").show();
        }
    });
    $("#index-tj-arrow-right").on('click',function(){
        var tjl = $(".tejia-box").offset().left;
        var il  = $(".tejia-box").find(".item").length;
        var iw  = $(".tejia-box").find(".item").width();
        if(tjl > -1*(iw+10)*(il-3))
            $(".tejia-box").animate({marginLeft:(-1*(iw+10)+tjl)+'px'});

        if(tjl == 0){
            $("#index-tj-arrow-left").show();
        }

        if(tjl == -1*(iw+10)*(il-4)){
            $("#index-tj-arrow-right").hide();
        }
    });
}
$(document).ready(function () {

    var recommend_goods = getcookie('goods');
    var html = '',
        index_cache_key = 'index';
    var index_content = getCache(index_cache_key);

    if(index_content){
        $("#main-container").html(index_content);
        index_content = null;
        completed();
    }


    $.ajax({
        url: ApiUrl + "/index.php?act=index",
        type: 'get',
        data: {recommend_goods: recommend_goods},
        dataType: 'json',
        success: function (result) {
            var data = result.datas;

            $.each(data, function (k, v) {
                $.each(v, function (kk, vv) {
                    switch (kk) {
                        case 'adv_list':
                            $.each(vv.item, function (ak, av) {
                                vv.item[ak].url = buildUrl(av.type, av.data);
                            });
                            break;
                        case 'home1':
                            vv.url = buildUrl(vv.type, vv.data);
                            break;
                        case 'home2':
                        case 'home3':
                            $.each(vv.item, function (k3, v3) {
                                vv.item[k3].url = buildUrl(v3.type, v3.data);
                            });
                            break;
                        case 'home4':
                            vv.square_url = buildUrl(vv.square_type, vv.square_data);
                            vv.rectangle1_url = buildUrl(vv.rectangle1_type, vv.rectangle1_data);
                            vv.rectangle2_url = buildUrl(vv.rectangle2_type, vv.rectangle2_data);
                            break;
                        case 'home5':
                            $.each(vv.item, function (k5, v5) {
                                vv.item[k5].url = buildUrl(v5.type, v5.data);
                            });
                            break;
                        case 'home6'://标题
                            vv.url = buildUrl(vv.type, vv.data);
                            break;
                        case 'home7':
                            vv.rectangle1_url = buildUrl(vv.rectangle1_type, vv.rectangle1_data);
                            vv.rectangle2_url = buildUrl(vv.rectangle2_type, vv.rectangle2_data);
                            vv.rectangle3_url = buildUrl(vv.rectangle3_type, vv.rectangle3_data);
                            vv.rectangle4_url = buildUrl(vv.rectangle4_type, vv.rectangle4_data);
                            vv.rectangle5_url = buildUrl(vv.rectangle5_type, vv.rectangle5_data);
                            vv.rectangle6_url = buildUrl(vv.rectangle6_type, vv.rectangle6_data);
                            break;
                        case 'home8':
                            vv.rectangle1_url = buildUrl(vv.rectangle1_type, vv.rectangle1_data);
                            vv.rectangle2_url = buildUrl(vv.rectangle2_type, vv.rectangle2_data);
                            vv.rectangle3_url = buildUrl(vv.rectangle3_type, vv.rectangle3_data);
                            vv.rectangle4_url = buildUrl(vv.rectangle4_type, vv.rectangle4_data);
                            vv.rectangle5_url = buildUrl(vv.rectangle5_type, vv.rectangle5_data);
                            vv.rectangle6_url = buildUrl(vv.rectangle6_type, vv.rectangle6_data);
                            break;
                        case 'home9':
                            vv.rectangle1_url = buildUrl(vv.rectangle1_type, vv.rectangle1_data);
                            vv.rectangle2_url = buildUrl(vv.rectangle2_type, vv.rectangle2_data);
                            vv.rectangle3_url = buildUrl(vv.rectangle3_type, vv.rectangle3_data);
                            vv.rectangle4_url = buildUrl(vv.rectangle4_type, vv.rectangle4_data);

                            break;
                        case 'home10':
                            vv.rectangle1_url = buildUrl(vv.rectangle1_type, vv.rectangle1_data);
                            vv.rectangle2_url = buildUrl(vv.rectangle2_type, vv.rectangle2_data);
                            vv.rectangle3_url = buildUrl(vv.rectangle3_type, vv.rectangle3_data);
                            vv.rectangle4_url = buildUrl(vv.rectangle4_type, vv.rectangle4_data);
                            vv.rectangle5_url = buildUrl(vv.rectangle5_type, vv.rectangle5_data);
                            vv.rectangle6_url = buildUrl(vv.rectangle6_type, vv.rectangle6_data);
                            vv.rectangle7_url = buildUrl(vv.rectangle7_type, vv.rectangle7_data);
                            vv.rectangle8_url = buildUrl(vv.rectangle8_type, vv.rectangle8_data);
                            break;
                        case 'home11':
                            $.each(vv.item, function (k11, v11) {
                                vv.item[k11].url = buildUrl(v11.type, v11.data);
                            });
                            break;
                        case 'recommend_goods':
                            $.each(vv.item, function (gck, gcv) {
                                vv.item[gck].url = buildUrl('goods', gcv.goods_id);
                            });
                            break;

                    }
                    //console.log(kk, vv);
                    html += template.render(kk, vv);
                    return false;
                });
            });
            if(index_content != html){
                setCache(index_cache_key, html);
                $("#main-container").html(html);
                completed();
            }

            html = null;

        }
    });


});
