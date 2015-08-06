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
                url : "/plug/get-token",
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
                url : "/plug/get-code",
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
});
