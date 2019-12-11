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
            // モーダルの背景がスクロールされないように固定する。
            bg_fixed(true, 'msg-modal');
        } else {
            $("div#msg-modal").fadeOut(250);
            $("#msg-modal #close-window").hide();

            // モーダルを固定するために追加したクラスを削除する。
            bg_fixed(false);
        }
    }

    /*
    *概要：背景を固定化するための関数
    *引数:
    *bg_fixed_flg
    *背景を固定するか解除するかを表すフラグ
    *op_elem
    *スクロールの固定をしない操作対象の要素
    */
    function bg_fixed(bg_fixed_flg, op_elem = null){
        if(bg_fixed_flg){


            // overflow:hiddenにするとスクロールバーが非表示になり、画面が少しずれるため、
            // overflow:hiddenを付加する時の横幅を取得して、その幅をwidthのサイズとする。
            $('body').css({'width': $(window).width()+'px'});

            // モーダルの背景がスクロールされないように固定する。
            $('body').addClass('bg-fixed');

            // ios端末では以下の処理も追加しないとスクロールが固定されない。
            var ua = navigator.userAgent;
            if(ua.indexOf('iPhone') > 0 || ua.indexOf('iPad') > 0) {
                var elem = $('#'+op_elem);
                elem.addEventListener('touchmove', function(e) {
                    var scroll = elem.scrollTop;
                    var range = elem.scrollHeight - elem.offsetHeight - 1;
                    if (scroll < 1) {
                        e.preventDefault();
                        elem.scrollTop = 1;
                    } else if(scroll > range) {
                        e.preventDefault();
                        elem.scrollTop = range;
                    }
                });
            }
        }else{
            // スクロールバーが表示されるので、width100%に戻す。
            $('body').css({'width': '100%'});
            // スクロール固定を解除する。
            $('body').removeClass('bg-fixed');
        }
    }
});
