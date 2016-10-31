    	$(function() {
    	    var y_province_id = $('#province_id').val();
            var y_city_id = $('#city_id').val();
            var y_region_id = $('#region_id').val();
            
            get_address_info('',y_province_id,"select[name=prov]");

            if(y_province_id > 0){
                get_address_info(y_province_id,y_city_id,"select[name=city]");
            }

            if(y_city_id > 0){
                get_address_info(y_city_id,y_region_id,"select[name=region]");
            }

    	    $("select[name=prov]").change(function() {
    	        var prov_id = $(this).val();
    	        
                get_address_info(prov_id,y_city_id,"select[name=city]");
                $("select[name=region]").html('<option value="">请选择...</option>');

                
    	    });

    	    $("select[name=city]").change(function() {
    	        var city_id = $(this).val();
    	       
                get_address_info(city_id,y_region_id,"select[name=region]");

    	    });

            $("select[name=region]").change(function() {
            
                var prov_index = $('select[name=prov]')[0].selectedIndex;
                var city_index = $('select[name=city]')[0].selectedIndex;
                var region_index = $('select[name=region]')[0].selectedIndex;
                var area_info = $('select[name=prov]')[0].options[prov_index].innerHTML + '-' + $('select[name=city]')[0].options[city_index].innerHTML + '-' + $('select[name=region]')[0].options[region_index].innerHTML;
                $('#area_info').val(area_info);
                console.log(area_info);
            });

/*
            $.sValid.init({
                rules:{
                    true_name:"required",
                    mob_phone:"required",
                    prov_select:"required",
                    city_select:"required",
                    region_select:"required",
                    address:"required"
                },
                messages:{
                    true_name:"姓名必填！",
                    mob_phone:"手机号必填！",
                    prov_select:"省份必填！",
                    city_select:"城市必填！",
                    region_select:"区县必填！",
                    address:"街道必填！"
                },
                callback:function (eId,eMsg,eRules){
                    if(eId.length >0){
                        var errorHtml = "";
                        $.map(eMsg,function (idx,item){
                            errorHtml += "<p>"+idx+"</p>";
                        });
                        $(".error-tips").html(errorHtml).show();
                    }else{
                         $(".error-tips").html("").hide();
                    }
                }  
            });*/
    	  
    	});

        //获取地址信息
        function get_address_info(add_id,y_add_id,ele) {
            $.ajax({
                type: 'post',
                url: ApiUrl + '/index.php?act=member_address&op=area_list',
                data: {
                    area_id: add_id
                },
                dataType: 'json',
                success: function(result) {
               
                    var data = result.datas;
                    var region_html = '<option value="">请选择...</option>';
                    for (var i = 0; i < data.area_list.length; i++) {
                        if (y_add_id == data.area_list[i].area_id) {
                            region_html += '<option selected value="' + data.area_list[i].area_id + '">' + data.area_list[i].area_name + '</option>';
                        }else{
                            region_html += '<option value="' + data.area_list[i].area_id + '">' + data.area_list[i].area_name + '</option>';
                        }
                    }
                    $(ele).html(region_html);
                }
            }); 
        }