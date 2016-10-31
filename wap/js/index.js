$(function() {
    var recommend_goods = getcookie('goods');
    $.ajax({
        url: ApiUrl + "/index.php?act=index",
        type: 'get',
        data:{recommend_goods:recommend_goods},
        dataType: 'json',
        success: function(result) {
            var data = result.datas;
            var html = '';
            //console.log(data);
            $.each(data, function(k, v) {
                $.each(v, function(kk, vv) {
                    switch (kk) {
                        case 'adv_list':
                            $.each(vv.item, function(ak, av) {
                                vv.item[ak].url = buildUrl(av.type, av.data);
                            });
                            break;
                        case 'home1':
                            vv.url = buildUrl(vv.type, vv.data);
                            break;
                        case 'home2':
                        case 'home3':
                            $.each(vv.item, function(k3, v3) {
                                vv.item[k3].url = buildUrl(v3.type, v3.data);
                            });
                            break;
                        case 'home4':
                            vv.square_url = buildUrl(vv.square_type, vv.square_data);
                            vv.rectangle1_url = buildUrl(vv.rectangle1_type, vv.rectangle1_data);
                            vv.rectangle2_url = buildUrl(vv.rectangle2_type, vv.rectangle2_data);
                            break;
                        case 'home5':
                            $.each(vv.item, function(k5, v5) {
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
                            $.each(vv.item, function(k11, v11) {
                                vv.item[k11].url = buildUrl(v11.type, v11.data);
                            });
                            break;
                        case 'recommend_goods':
                            $.each(vv.item, function(gck, gcv) {
                                vv.item[gck].url = buildUrl('goods', gcv.goods_id);
                            });
                            break;

                    }
                    console.log(kk,vv);
                    html += template.render(kk, vv);
                    //console.log('html',kk);
                    return false;
                });
            });

            $("#main-container").html(html);

            //轮播图
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

            //修改a链接增加key值
            add_key();

        }
    });

    $('.search-btn').click(function(){
        var keyword = encodeURIComponent($('#keyword').val());
        location.href = SiteUrl+'/mall_m/index.php?act=goods&op=list&keyword='+keyword;
    });

});
