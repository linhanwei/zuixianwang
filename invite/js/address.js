$(function(){
    var ApiUrl = '../m'
    haoJs.ajax({
        type:'post',
        url:ApiUrl+'/index.php?act=address&op=area_list',
        dataType:'json',
        success:function(result){
            var data = result.datas;
            var prov_html = '';
            for(var i=0;i<data.area_list.length;i++){
                prov_html+='<option value="'+data.area_list[i].area_id+'">'+data.area_list[i].area_name+'</option>';
            }
            $("select[name=prov]").append(prov_html);
        }
    });

    $("select[name=prov]").change(function(){
        var prov_id = $(this).val();
        haoJs.ajax({
            type:'post',
            url:ApiUrl+'/index.php?act=address&op=area_list',
            data:{area_id:prov_id},
            dataType:'json',
            success:function(result){
                var data = result.datas;
                var city_html = '<option value="">请选择...</option>';
                for(var i=0;i<data.area_list.length;i++){
                    city_html+='<option value="'+data.area_list[i].area_id+'">'+data.area_list[i].area_name+'</option>';
                }
                $("select[name=city]").html(city_html);
                $("select[name=region]").html('<option value="">请选择...</option>');
            }
        });
    });

    $("select[name=city]").change(function(){
        var city_id = $(this).val();
        haoJs.ajax({
            type:'post',
            url:ApiUrl+'/index.php?act=address&op=area_list',
            data:{area_id:city_id},
            dataType:'json',
            success:function(result){
                var data = result.datas;
                var region_html = '<option value="">请选择...</option>';
                for(var i=0;i<data.area_list.length;i++){
                    region_html+='<option value="'+data.area_list[i].area_id+'">'+data.area_list[i].area_name+'</option>';
                }
                $("select[name=region]").html(region_html);
            }
        });
    });

});