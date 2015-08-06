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
});
