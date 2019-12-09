// モーダルダイアログ用のjsファイル
// 分けないと２重でクリックイベントが実行されてしまう為、その対策。
// off関数でイベントを削除してからイベントを追加してもイベントが２重になる。
// 理由は不明。
$(function(){

    /*** users/send_msg ***/
    sendMsg();

    /*** contacts/sendContact ***/
    sendContact();

    function sendMsg(){
        $('#close-window').click(function() {
            displayModal(false);
        });
        $('#send-msg-form').submit(function(){
            var formdata = new FormData($("#send-msg-form")[0]);
            $.ajax({
                type: "POST",
                url: "../users/sendMsgAjax",
                dataType: 'text',
                data: formdata,
                processData: false,
                contentType: false,
                success: function(json_msg){
                    msg = $.parseJSON(json_msg);
                    alert(msg);
                    displayModal(false);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    alert('通信に失敗しました。');
                    console.log("XMLHttpRequest : " + XMLHttpRequest.status);
                    console.log("textStatus     : " + textStatus);
                    console.log("errorThrown    : " + errorThrown.message);
                }
            });
        return false;
        });
    }

    function sendContact(){
        $('#close-window').click(function() {
            displayModal(false);
        });
        $('#send-contact-form').submit(function(){
            var formdata = new FormData($("#send-contact-form")[0]);
            $.ajax({
                type: "POST",
                url: "../contacts/sendContactAjax",
                dataType: 'text',
                data: formdata,
                processData: false,
                contentType: false,
                success: function(json_msg){
                    $('#contacts__send-contact .loading').hide();
                    msg = $.parseJSON(json_msg);
                    $('#ajax-message').text(msg)
                },
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    $('#contacts__send-contact .loading').hide();
                    alert('通信に失敗しました。');
                    console.log("XMLHttpRequest : " + XMLHttpRequest.status);
                    console.log("textStatus     : " + textStatus);
                    console.log("errorThrown    : " + errorThrown.message);
                }
            });
        $('#contacts__send-contact .loading').show();
        return false;
        });
    }

    //モーダルウィンドウを開く
    function displayModal(sign) {
        if (sign) {
            $("div#msg-modal").fadeIn(500);
            $("#msg-modal #close-window").show();
            // モーダルダイアログに不必要な要素を非表示にする。
            $("#msg-modal #header").remove();
            $("#msg-modal #footer").remove();
            $("#msg-modal .cake-sql-log").remove();
            // モーダルダイアログだとcssのクエリメディアが有効にならないので、content要素を直接width100%にする。
            $("#msg-modal #content").css({width: "100%", margin: '0'});
            if ($(window).width() < 500) {
                $("#msg-modal .container").css({width: "100%"});
            }

        } else {
            $("div#msg-modal").fadeOut(250);
            $("#msg-modal #close-window").hide();
        }
    }
});
