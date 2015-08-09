/**
 * 小插件
 * @author lxl
 */
var Plug={
    /**
     * 获取Token
     */
    'getToken' : function() {
        $.ajax(
            {
                type: "GET",
                url : "/v1/plug/get-token",
                data: {
                    'mobile'   : $("#mobile").val()
                },
                async: false,
                dataType: "json",
                beforeSend: function () {
                    $(".token").css('display','none');
                },
                success: function (result) {
                    if (result.code==='ok') {
                        $(".token").show();
                        /** 发送成功 **/
                        $(".token").html(result['msg']);
                    } else {
                        alert('获取失败，请重试！');
                    }
                }
            });
    },
    /**
     * 获取Code
     */
    'getCode' : function() {
        $.ajax(
            {
                type: "GET",
                url : "/v1/plug/get-code",
                data: {
                    'mobile'   : $("#mobile").val()
                },
                async: false,
                dataType: "json",
                beforeSend: function () {
                    $(".table-code").css('display','none');
                },
                success: function (result) {
                    if (result.code==='ok') {
                        $(".table-code").show();
                        /** 发送成功 **/
                        $(".mobile").html(result['data']['mobile']);
                        $(".code").html(result['data']['code']);
                        $(".type").html(result['data']['type']);
                        $(".create_time").html(result['data']['create_time']);
                        $(".expires_in").html(result['data']['expires_in']);
                    } else {
                        alert('获取失败，请重试！');
                    }
                }
            });
    },
    /**
     * Md5
     */
    'MD5_str' : function() {
        $.ajax(
            {
                type: "GET",
                url : "/v1/plug/get-md5",
                data: {
                    'str'   : $("#md5_before").val()
                },
                async: false,
                dataType: "json",
                beforeSend: function () {

                },
                success: function (result) {
                    if (result.code==='ok') {
                        $("#md5_after").val(result['msg']);
                    } else {
                        alert('生成失败，请重试！');
                    }
                }
            });
    },
    /**
     * 获取错误信息
     */
    'getErrorMsg' : function() {
        $.ajax(
            {
                type: "GET",
                url : "/v1/plug/get-error-msg",
                data: {
                    'code'   : $("#error_code").val()
                },
                async: false,
                dataType: "json",
                beforeSend: function () {
                    $(".error-msg").css('display','none');
                },
                success: function (result) {
                    if (result.code==='ok') {
                        $(".error-msg").show();
                        $(".error-msg").html(result['msg']);
                    } else {
                        alert('查询失败，请重试！');
                    }
                }
            });
    },
    /**
     * 解绑
     */
    'removeBindUser' : function() {
        $.ajax(
            {
                type: "GET",
                url : "/v1/plug/remove-bind-user",
                data: {
                    'mobile'   : $("#bind_user").val()
                },
                async: false,
                dataType: "json",
                beforeSend: function () {
                    $(".remove-bind-user-msg").css('display','none');
                },
                success: function (result) {
                    if (result.code==='ok') {
                        $(".remove-bind-user-msg").show();
                        $(".remove-bind-user-msg").html(result['msg']);
                    } else {
                        alert('解绑失败。');
                    }
                }
            });
    }
};
$(function(){
    $(".btn-get-token").click(function(){
        var mobile = $("#mobile").val();
        if (mobile) {
            Plug.getToken();
        } else {
            alert('请输入手机号');
        }

    });
    $(".btn-get-code").click(function(){
        var mobile = $("#mobile").val();
        if (mobile) {
            Plug.getCode();
        } else {
            alert('请输入手机号');
        }
    });
    $(".btn-md5").click(function(){
        var md5_before = $("#md5_before").val();
        if (md5_before) {
            Plug.MD5_str();
        } else {
            alert('请输入MD5字符串');
        }
    });
    $(".btn-get-error-msg").click(function(){
        var error_code = $("#error_code").val();
        if (error_code) {
            Plug.getErrorMsg();
        } else {
            alert('请输入错误代码');
        }
    });
    $(".btn-remove-bind-user").click(function(){
        var bind_user = $("#bind_user").val();
        if (bind_user) {
            Plug.removeBindUser();
        } else {
            alert('请输入解绑号码');
        }
    });
});
