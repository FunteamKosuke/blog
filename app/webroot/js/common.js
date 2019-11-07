$(function(){
    /*** ヘッダー ***/
    // 検索フォームをクリックしたら表示する
    $('#search img').on('click',function(){
        $('.search_toggle').toggle();
    });

    /*** コントローラー固有のjsを以下に記述する ***/
    /*** users/add **/

    // 地方、都道府県、市区町村、町域選択ボックスに関する初期化
    initAddressSelect();

    // 地方選択ボックスを画面読み込み時に作成する。
    $region = new Array('選択してください', '北海道', '東北', '関東', '中部', '近畿', '中国', '四国', '九州');
    addSelectElem('region-select', $region);

    // 地方選択ボックスが選択された時に関連する都道府県の選択ボックスを作成する。
    $('#region-select').change(function(){
        if (!('選択してください' == $('#region-select option:selected').text())) {
            var pref = new Array();
            switch ($('#region-select option:selected').text()) {
                case '北海道':
                    pref.push('北海道');
                    break;
                case '東北':
                    pref.push('青森県', '岩手県', '秋田県', '宮城県', '山形県', '福島県');
                    break;
                case '関東':
                    pref.push('茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県');
                    break;
                case '中部':
                    pref.push('新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県', '岐阜県', '静岡県', '愛知県');
                    break;
                case '近畿':
                    pref.push('三重県', '滋賀県', '奈良県', '和歌山県', '京都府', '大阪府', '兵庫県');
                    break;
                case '中国':
                    pref.push('岡山県', '広島県', '鳥取県', '島根県', '山口県');
                    break;
                case '四国':
                    pref.push('香川県', '徳島県', '愛媛県', '高知県');
                    break;
                case '九州':
                    pref.push('福島県', '佐賀県', '長崎県', '大分県', '熊本県', '宮崎県', '鹿児島県', '沖縄県');
                    break;
                default:
                    pref.push('地方以外のデータ渡してんじゃXXXXXXXXXXXX!!!');
            }
            $('#pref-select').empty();
            pref.unshift('選択してください');
            addSelectElem('pref-select', pref);
            $('#pref-select').show();
            // 関係ないところは空にして非表示にする。
            // 一度町域まで表示させて、再度都道府県を選択した際に町域等が残っていたらおかしいのでその対策。
            $('#city-select').empty().hide();
            $('#town-select').empty().hide();
        }
    });

    // 選択された都道府県に関連する市区町村の選択ボックスを作成する。
    $('#pref-select').change(function(){
        if (!('選択してください' == $('#pref-select option:selected').text())) {
            $.ajax({
                type: "POST",
                url: "../addresses/getSelectElem",
                data: {
                    "distinct_column": 'city_kannzi',
                    "get_column": 'prefectures_kannzi',
                    "get_data": $('#pref-select option:selected').text()
                },
                success: function(json_search_result){
                    var search_result = $.parseJSON(json_search_result);
                    // 空にしてから追加しないと選択するたびにselect要素が追加されてしまう。
                    $('#city-select').empty();
                    search_result.unshift('選択してください');
                    addSelectElem('city-select', search_result);
                    $('#city-select').show();
                    // 関係ないところは空にして非表示にする。
                    // 一度町域まで表示させて、再度都道府県を選択した際に町域等が残っていたらおかしいのでその対策。
                    $('#town-select').empty().hide();
                },
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    alert('通信に失敗しました。');
                    console.log("XMLHttpRequest : " + XMLHttpRequest.status);
                    console.log("textStatus     : " + textStatus);
                    console.log("errorThrown    : " + errorThrown.message);
                }
            });
        }
    });

    // 選択された市区町村に関連する町域の選択ボックスを作成する。
    $('#city-select').change(function(){
        if (!('選択してください' == $('#city-select option:selected').text())) {
            $.ajax({
                type: "POST",
                url: "../addresses/getSelectElem",
                data: {
                    "distinct_column": 'town_area_kannzi',
                    "get_column": 'city_kannzi',
                    "get_data": $('#city-select option:selected').text()
                },
                success: function(json_search_result){
                    var search_result = $.parseJSON(json_search_result);
                    // 空にしてから追加しないと選択するたびにselect要素が追加されてしまう。
                    $('#town-select').empty();
                    search_result.unshift('選択してください');
                    addSelectElem('town-select', search_result);
                    $('#town-select').show();
                },
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    alert('通信に失敗しました。');
                    console.log("XMLHttpRequest : " + XMLHttpRequest.status);
                    console.log("textStatus     : " + textStatus);
                    console.log("errorThrown    : " + errorThrown.message);
                }
            });
        }
    });

    $('#town-select').change(function(){
        if (!('選択してください' == $('#town-select option:selected').text())) {
            // var region_name = $('#region-select option:selected').text();
            var pref_name = $('#pref-select option:selected').text();
            var city_name = $('#city-select option:selected').text();
            var town_name = $('#town-select option:selected').text();
            var select_address = pref_name + city_name + town_name;
            $('#select-address').val(select_address);
        }
    });

    // ［検索］ボタンクリックで郵便番号検索を実行
    $('#zipcode').keyup(function() {
        // 郵便番号入力欄に7桁の数字が入力されたら検索を開始する。
        if(0 == $(this).val().search(/\d{7}/)){
            $.ajax({
                type: "POST",
                url: "../addresses/search",
                data: {
                    "zipcode": $('#zipcode').val()
                    // "zipcode": '6991513'
                },
                success: function(json_search_result){
                    //データを受け取っていれば、住所欄に入力する。
                    var search_result = $.parseJSON(json_search_result);
                    if (search_result.length > 0) {
                        //住所選択欄を最初に空にする
                        $('#address_msg').empty().hide();
                        $('#address-select').empty().hide();

                        if (search_result.length == 1) { // 住所が一意な場合
                            var address = search_result[0]['Address']['prefectures_kannzi'] +
                                          search_result[0]['Address']['city_kannzi'] +
                                          search_result[0]['Address']['town_area_kannzi'];
                        $('#address').val(address);
                    } else {// 郵便番号に複数の住所が含まれている場合の対応
                            // 住所が複数ある場合はセレクトボックス を表示し、選択すると住所欄に入力されるようにする。
                            var select_str = '<option>選択してください</option>'
                            $.each(search_result, function(index, elem){
                                select_str += '<option>' + elem['Address']['prefectures_kannzi'] +
                                                           elem['Address']['city_kannzi'] +
                                                           elem['Address']['town_area_kannzi'] + '</option>'
                            });
                            var address_msg = '*郵便番号に複数の住所が含まれています。該当の住所を選択してください。';
                            $('#address').val('');
                            $('#address_msg').text(address_msg).show();
                            $('#address-select').append(select_str).show();
                        }
                    } else {
                        $('#address').val('該当の住所が存在しません。手動で入力してください。');
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    alert('通信に失敗しました。');
                    console.log("XMLHttpRequest : " + XMLHttpRequest.status);
                    console.log("textStatus     : " + textStatus);
                    console.log("errorThrown    : " + errorThrown.message);
                }
            });
        }
    });

    // 住所欄に入力される町域が選択された町域になるようにする
    $('#address-select').change(function() {
        if (!($('#address-select option:selected').text() == '選択してください')) {
            var address_str = $('#address-select option:selected').text();
            $('#address').val(address_str);
        }
    });

    /*** users/index ***/
    setModal();

    /*** users/send_msg ***/
    sendMsg();
    /*** posts/view ***/
    //現在表示している画像が何枚目かを表す
    var page = 0;

    //画像の最後が何枚目かを表す
    var lastPage =parseInt($(".slide .largeImg img").length-1);

    $('.image').click(function(e) {


        page = $('.image').index(this);

        //最初に全部のイメージを一旦非表示にします
        $(".slide .largeImg img").css("display","none");
        $(".slide .largeImg").css({'width' : $(window).width() * 0.6});
        //初期ページを表示
        $(".slide .largeImg img").eq(page).css({display: "block"});
        $(".slide .largeImg").eq(page).css({display: "block"});

        // スライドショーの戻るボタンと次へボタンを表示する
        $('#post__view .next').show();
        $('#post__view .prev').show();
        // 戻るボタンと次へボタンの位置を設定しやすくするための要素を表示する。
        $('#post__view .slide-operation')
        .css({
            'width' : $(window).width(),    // ウィンドウ幅
            'height': $(window).height()
        })
        .show();

        // ポップアップ画像の後ろに幕を張る
        $('#post__view .back-curtain')
        .css({
            'width' : $(window).width(),    // ウィンドウ幅
            'height': $(window).height()    // 同 高さ
        })
        .show();

        startTimer(); //時間で画像をスライドできるようにする。
    });

    $('.back-curtain, .largeImg, .slide-operation').click(function() {
        $('.largeImg').fadeOut('slow', function() {$('.back-curtain').hide();
                                                   $("#post__view .next").hide();
                                                   $("#post__view .prev").hide();
                                                    $("#post__view .slide-operation").hide();});
        stopTimer(); //画像を非表示にしたらタイマーイベントも停止させる。
    });

    //次の画像を表示する
    $("#post__view .next").click(function(e) {
    //タイマー停止＆スタート（クリックした時点から～秒とする為）
        stopTimer();
        startTimer();
          if(page === lastPage){
                         page = 0;
                         changePage();
               }else{
                         page ++;
                         changePage();
          };
          e.stopPropagation(); //親要素のクリックイベントが発生するのを防ぐ
    });

    //「一つ前の画像を表示する
    $("#post__view .prev").click(function(e) {
      //タイマー停止＆スタート（クリックした時点から～秒とする為）
      stopTimer();
      startTimer();
      if(page === 0){
                     page = lastPage;
                     changePage();
           }else{
                     page --;
                     changePage();
      };
      e.stopPropagation(); //親要素のクリックイベントが発生するのを防ぐ
    });

    $(window).on('resize', function(){
        $('#post__view .slide-operation')
        .css({
            'width' : $(window).width(),    // ウィンドウ幅
            'height': $(window).height()
        })
        $(".slide .largeImg").css({'width' : $(window).width() * 0.6});
    });



    //ページ切換用、自作関数作成
    function changePage(){
                             $(".slide .largeImg img").fadeOut(1000);
                             $(".slide .largeImg img").eq(page).fadeIn(1000);
                             $(".slide .largeImg").hide();
                             $(".slide .largeImg").eq(page).show();
    };

    //～秒間隔でイメージ切換の発火設定
    var Timer;
    function startTimer(){
        Timer =setInterval(function(){
              if(page === lastPage){
                             page = 0;
                             changePage();
                   }else{
                             page ++;
                             changePage();
              };
         },6000);
    }
    //（７）～秒間隔でイメージ切換の停止設定
    function stopTimer(){
        clearInterval(Timer);
    }

    /*** address/upload ***/
    var ajaxHandle = null;
    $('#addresses__csv_import #csv-upload').submit(function(){
        $('#result_msg').empty();
        var filedata = new FormData($("#addresses__csv_import #csv-upload")[0]); //選択されたファイルデータを取得する。
        ajaxHandle = $.ajax({ //キャンs流処理を有効にするためにajaxのハンドルを取得する。
            type: "POST",
            url: "csv_import",
            dataType: 'json',
            data: filedata,
            processData: false,
            contentType: false,
            success: function(msg)
            {
                $('#result_msg').text(msg);
                $('.loading').hide();
                $('#addresses__csv_import #cancel').hide();
                unlockScreen(lockId);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown)
            {
                $('.loading').hide();
                $('#addresses__csv_import #cancel').hide();
                unlockScreen(lockId);
                // キャンセルボタンが押下された際は、その旨のメッセージを出力する。
                if (textStatus == 'abort') {
                    $('#result_msg').text('インポートがキャンセルされました。');
                    $.ajax({
                        type: "POST",
                        url: "abort"
                    });
                }
                console.log("XMLHttpRequest : " + XMLHttpRequest.status);
                console.log("textStatus     : " + textStatus);
                console.log("errorThrown    : " + errorThrown.message);
            }
        });
        // .done(function( data ) {
        //     // alert(data);
        //     alert('hkfjgk');
        //     // $('#result').text(data.width + "x" + data.height);
        // });
        $('.loading').show();
        $('#addresses__csv_import #cancel').show();
        lockScreen(lockId);
        return false;
    });

    $('#addresses__csv_update #csv-update').submit(function(){
        $('#result_msg').empty();
        var filedata = new FormData($("#addresses__csv_update #csv-update")[0]); //選択されたファイルデータを取得する。
        ajaxHandle = $.ajax({ //キャンs流処理を有効にするためにajaxのハンドルを取得する。
            type: "POST",
            url: "csv_update",
            dataType: 'json',
            data: filedata,
            processData: false,
            contentType: false,
            success: function(msg)
            {
                $('#result_msg').text(msg);
                $('.loading').hide();
                $('#addresses__csv_update #cancel').hide();
                unlockScreen(lockId);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown)
            {
                $('.loading').hide();
                $('#addresses__csv_update #cancel').hide();
                unlockScreen(lockId);
                // キャンセルボタンが押下された際は、その旨のメッセージを出力する。
                if (textStatus == 'abort') {
                    $('#result_import_msg').text('インポートがキャンセルされました。');
                    $.ajax({
                        type: "POST",
                        url: "abort"
                    });
                }
                console.log("XMLHttpRequest : " + XMLHttpRequest.status);
                console.log("textStatus     : " + textStatus);
                console.log("errorThrown    : " + errorThrown.message);
            }
        });
        // .done(function( data ) {
        //     // alert(data);
        //     alert('hkfjgk');
        //     // $('#result').text(data.width + "x" + data.height);
        // });
        $('.loading').show();
        $('#addresses__csv_update #cancel').show();
        lockScreen(lockId);
        return false;
    });

    $('#cancel').click(function(){
        ajaxHandle.abort();
        ajaxHandle = null;
    });

    /*** address/apdate ***/

    /*** contacts/sendContact ***/
    sendContact();

    /***** 共通で使用する *****/
    // 選択したファイルのファイル名をinputタグに入力する。
    $('.label-file-name').on('change',function(){
        var file_str = "";
        var file_array = $(this).prop('files');
        $.each(file_array, function(index, element){
            file_str += element.name;
            if( !(index == file_array.length - 1) ){
                file_str += ',';
            }
        });
        $(this).parent('.input').parent('.label-file').next('.form-group').children('.file-name-input').val(file_str);
        $('.label-file-button').show();
    });

    /*** 以下に関数を定義する ***/
    var lockId = "lockId";
    /*
     * 画面操作を無効にする
     */
    function lockScreen(id) {

        // 現在画面を覆い隠すためのDIVタグを作成する
        var divTag = $('<div />').attr("id", id);

        // スタイルを設定
        divTag.css("z-index", "10000")
              .css("position", "absolute")
              .css("top", "0px")
              .css("left", "0px")
              .css("right", "0px")
              .css("bottom", "0px")
              .css("opacity", "0");

        // BODYタグに作成したDIVタグを追加
        $('body').append(divTag);
    }

    /*
     * 画面操作無効を解除する
     */
    function unlockScreen(id) {

        // 画面を覆っているタグを削除する
        $("#" + id).remove();
    }

    // selectボックスに要素を追加する関数。
    // セレクトボックス のidと追加したい要素を配列で渡すと追加される。
    // class名も考慮しようと思ったがそもそも同じようなセレクトボックス を
    // 同じ画面に何個も作成することがないので対応しない。
    function addSelectElem(select_id_name, select_array){
        select_str = '';
        $.each(select_array, function(index, elem){
            select_str += '<option>' + elem + '</option>'
        });
        $("#" + select_id_name).append(select_str);
    }

    function initAddressSelect(){
        // 都道府県、市区町村、町域の選択ボックスについては最初は非表示。
        $('#pref-select').hide();
        $('#city-select').hide();
        $('#town-select').hide();
    }

    function setModal() {
        // //HTML読み込み時にモーダルウィンドウの位置をセンターに調整
        // adjustCenter("div#msg-modal div.container");
        //
        // //ウィンドウリサイズ時にモーダルウィンドウの位置をセンターに調整
        // $(window).resize(function() {
        // 	adjustCenter("div#msg-modal div.container");
        // });

        //背景がクリックされた時にモーダルウィンドウを閉じる
        $("div#msg-modal div.background, #close-window").click(function() {
        	displayModal(false);
        });

        //リンクがクリックされた時にAjaxでコンテンツを読み込む
        $("a.msg-modal").click(function() {
        	$("div#msg-modal div.container").load($(this).attr("href"), data="html", onComplete);
        	return false;
        });

        //コンテンツの読み込み完了時にモーダルウィンドウを開く
        function onComplete() {
        	displayModal(true);
        }
    }

    //モーダルウィンドウを開く
    function displayModal(sign) {
    	if (sign) {
    		$("div#msg-modal").fadeIn(500);
            $("#msg-modal #close-window").show();
            // モーダルダイアログに不必要な要素を非表示にする。
            $("#msg-modal #header").hide();
            $("#msg-modal #footer").hide();
            $("#msg-modal .cake-sql-log").hide();
            // モーダルダイアログだとcssのクエリメディアが有効にならないので、content要素を直接width100%にする。
            $("#msg-modal #content").css({width: "100%"});

    	} else {
    		$("div#msg-modal").fadeOut(250);
            $("#msg-modal #close-window").hide();
    	}
    }

    //ウィンドウの位置をセンターに調整
    function adjustCenter(target) {
    	var margin_top = ($(window).height()-$(target).height())/2;
    	var margin_left = ($(window).width()-$(target).width())/2;
    	$(target).css({top:margin_top+"px", left:margin_left+"px"});
    }

    function sendMsg(){
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
                    $('#ajax-message').text(msg)
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
                    msg = $.parseJSON(json_msg);
                    $('#ajax-message').text(msg)
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
});
