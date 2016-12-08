$(function() {
	var e = getcookie("key");
	if (!e) {
		window.location.href = WapSiteUrl + "/tmpl/member/login.html";
		return
	}
	var a = GetQueryString("order_id");
	$.getJSON(ApiUrl + "/index.php?act=member_evaluate&op=index", {
		key: e,
		order_id: a
	}, function(r) {
		if (r.datas.error) {

			app_alert(r.datas.error);
			return false
		}
		var l = template.render("member-evaluation-script", r.datas);
		$("#member-evaluation-div").html(l);
		$('input[name="file"]').ajaxUploadImage({
			url: ApiUrl + "/index.php?act=sns_album&op=file_upload",
			data: {
				key: e
			},
			start: function(e) {
				e.parent().after('<div class="upload-loading"><i></i></div>');
				e.parent().siblings(".pic-thumb").remove()
			},
			success: function(e, a) {
				checklogin(a.login);
				if (a.datas.error) {
					e.parent().siblings(".upload-loading").remove();

					app_alert("图片尺寸过大！");
					return false
				}
				e.parent().after('<div class="pic-thumb"><img src="' + a.datas.file_url + '"/></div>');
				e.parent().siblings(".upload-loading").remove();
				e.parents("a").next().val(a.datas.file_name)
			}
		});
		$(".star-level").find("i").click(function() {
			var e = $(this).index();
			for (var a = 0; a < 5; a++) {
				var r = $(this).parent().find("i").eq(a);
				if (a <= e) {
					r.removeClass("star-level-hollow").addClass("star-level-solid")
				} else {
					r.removeClass("star-level-solid").addClass("star-level-hollow")
				}
			}
			$(this).parent().next().val(e + 1)
		});
		$(".btn-l").click(function() {
			var r = $("form").serializeArray();
			var l = {};
			l.key = e;
			l.order_id = a;
			for (var t = 0; t < r.length; t++) {
				l[r[t].name] = r[t].value;
				if(t == 1 && r[t].value == ''){
					app_toast('评价内容不能为空');
					return false
				}

			}
			$.ajax({
				type: "post",
				url: ApiUrl + "/index.php?act=member_evaluate&op=save",
				data: l,
				dataType: "json",
				async: false,
				success: function(e) {
					checklogin(e.login);
					if (e.datas.error) {
						app_alert(e.datas.error);
						return false
					}
					window.location.href = WapSiteUrl + "/tmpl/member/order_list.html"
				}
			})
		})
	})
});