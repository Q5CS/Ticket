code = request("code");
console.log("code: ", code);

doAuth(code);

function doAuth(code) {
    $.ajax({
        type: "POST",
        url: "/user/auth",
        data: {
            "code": code
        },
        dataType: "json",
        success: function (res) {
            console.log(res);
            if(res.status < 0) {
                alert(JSON.stringify(res.msg));
                alert("认证失败，请重试或联系管理员！");
                window.location = '/main/authenticate';
                return;
            }
            //succ
            window.location = '/main/authenticate';
        }
    });
}

function request(paras) {
    var url = location.href;
    var paraString = url.substring(url.indexOf("?") + 1, url.length).split("&");
    var paraObj = {}
    for (i = 0; j = paraString[i]; i++) {
        paraObj[j.substring(0, j.indexOf("=")).toLowerCase()] = j.substring(j.indexOf("=") + 1, j.length);
    }
    var returnValue = paraObj[paras.toLowerCase()];
    if (typeof (returnValue) == "undefined") {
        return "";
    } else {
        return returnValue;
    }
}