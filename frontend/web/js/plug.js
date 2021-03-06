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
    },
    /**
     * 删除用户
     */
    'removeUser' : function() {
        $.ajax(
            {
                type: "GET",
                url : "/v1/plug/remove-user",
                data: {
                    'mobile'   : $("#remove_user").val(),
                    'type'     : '1'
                },
                async: false,
                dataType: "json",
                beforeSend: function () {
                    $(".user-msg").css('display','none');
                },
                success: function (result) {
                    if (result.code==='ok') {
                        $(".user-msg").show();
                        $(".user-msg").html(result['msg']);
                    } else {
                        alert('删除失败。');
                    }
                }
            });
    },
    /**
     * 检测用户
     */
    'checkUser' : function() {
        $.ajax(
            {
                type: "GET",
                url : "/v1/plug/remove-user",
                data: {
                    'mobile'   : $("#remove_user").val(),
                    'type'     : '2'
                },
                async: false,
                dataType: "json",
                beforeSend: function () {
                    $(".user-msg").css('display','none');
                },
                success: function (result) {
                    if (result.code==='ok') {
                        $(".user-msg").show();
                        $(".user-msg").html(result['msg']);
                    } else {
                        alert('服务器繁忙。');
                    }
                }
            });
    },
    /**
     * 获取所有SSDB KEY
     */
    'getAllKey' : function() {
        $.ajax(
            {
                type: "GET",
                url : "/v1/plug/get-all-key",
                data: {},
                async: false,
                dataType: "json",
                beforeSend: function () {

                },
                success: function (result) {
                    if (result.code==='ok') {
                        var rs = result.data;
                        if (rs) {
                            var html = '';
                            for(var i = 0;i < rs.length; i++) {
                                html += '<div class="col-xs-6 col-sm-3">'+rs[i]+'</div>';
                            }
                            $('.all-keys').html(html);
                        } else {
                            alert('获取为空');
                        }
                    } else {
                        alert('获取失败。');
                    }
                }
            });
    },
    /**
     * 获取所有 KEY
     */
    'getKey' : function() {
        $.ajax(
            {
                type: "GET",
                url : "/v1/plug/get-key",
                data: {
                    'key'   : $("#ssdb_key").val(),
                },
                async: false,
                dataType: "json",
                beforeSend: function () {
                    $('.key-value').css('display', 'none');
                },
                success: function (result) {
                    if (result.code==='ok') {
                        var rs = result.data;
                        if (rs) {
                            $('.key-value').show();
                            $('.key-value').html(rs);
                        } else {
                            alert('获取为空');
                        }
                    } else {
                        alert('获取失败。');
                    }
                }
            });
    },
    /**
     * 删除KEY
     */
    'delKey' : function() {
        $.ajax(
            {
                type: "GET",
                url : "/v1/plug/del-key",
                data: {
                    'key'   : $("#ssdb_key").val(),
                },
                async: false,
                dataType: "json",
                beforeSend: function () {
                },
                success: function (result) {
                    if (result.code==='ok') {
                        alert('删除成功');
                        window.location.reload();
                    } else {
                        alert('删除失败');
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
    $(".btn-remove-user").click(function(){
        var remove_user = $("#remove_user").val();
        if (remove_user) {
            Plug.removeUser();
        } else {
            alert('请输入删除号码');
        }
    });
    $(".btn-check-user").click(function(){
        var remove_user = $("#remove_user").val();
        if (remove_user) {
            Plug.checkUser();
        } else {
            alert('请输入检测号码');
        }
    });
    $(".btn-get-all-key").click(function(){
        Plug.getAllKey();
    });
    $(".btn-get-key").click(function(){
        var key = $("#ssdb_key").val();
        if (key) {
            Plug.getKey();
        } else {
            alert('请输入SSDB KEY');
        }
    });
    $(".btn-del-key").click(function(){
        var key = $("#ssdb_key").val();
        if (key) {
            if (confirm("确定要删除key:"+key+"吗？")) {
                Plug.delKey();
            }
        } else {
            alert('请输入SSDB KEY');
        }
    });
});
