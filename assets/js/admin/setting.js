laydate.render({
	elem: '#input-starttime',
	type: 'datetime',
	value: starttime
});
laydate.render({
	elem: '#input-starttime-stu',
	type: 'datetime',
	value: starttime_stu
});
laydate.render({
	elem: '#input-finaltime',
	type: 'datetime',
	value: finaltime
});
laydate.render({
	elem: '#input-finaltime-stu',
	type: 'datetime',
	value: finaltime_stu
});
var editor = new window.wangEditor('#editor');
editor.create();

function save() {
    noReg = $("#cbox-no-reg").is(":checked") ? 1 : 0;
	alltnum = $("#input-alltnum").val();
	starttime = $("#input-starttime").val();
	starttime_stu = $("#input-starttime-stu").val();
	finaltime = $("#input-finaltime").val();
	finaltime_stu = $("#input-finaltime-stu").val();
	pertnum = $("#input-pertnum").val();
	pertnum_stu = $("#input-pertnum-stu").val();
	notice = editor.txt.html();
	if (!alltnum || !starttime || !starttime_stu || !finaltime || !finaltime_stu || !pertnum || !pertnum_stu) {
		alert("不能留空！");
		return;
	}
	$.ajax({
		type: "POST",
		url: "/admin/api_update_setting",
		data: {
		    "noReg": noReg,
			"alltnum": alltnum,
			"starttime": starttime,
			"starttime_stu": starttime_stu,
			"finaltime": finaltime,
			"finaltime_stu": finaltime_stu,
			"pertnum": pertnum,
			"pertnum_stu": pertnum_stu,
			"notice": notice
		},
		dataType: "json",
		success: function (response) {
			alert(response.msg);
			location.reload();
		}
	});
}

$("#btn-save").click(function () {
	$(this).attr("disabled", "true");
	save();
});