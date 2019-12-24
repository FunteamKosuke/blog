const SCROLL_TOP_MOVE_ICON_SHOW_POS = 200;

$(function(){
    scrollTopMoveIconToggle();

    $(window).scroll(function(){
        scrollTopMoveIconToggle();
    });

    $('#scroll-top-move').click(function(){
        $("html,body").animate({scrollTop: 0});
    });


    /*** ヘッダー ***/
    // 検索フォームをクリックしたら表示する
    $('#search img').off('click').on('click',function(){
        if(confirm('表示していいですか？')){
            $('.search_toggle').toggle();
        }
    });
    // モバイル用のメニューを表示する。
    mobileHeaderMenuInit();

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
            console.log($('#UserAddForm').find('input:hidden[name="data[_Token][key]"]').val());
            $.ajax({
                type: "POST",
                url: "../addresses/getSelectElem",
                data: {
                    "distinct_column": 'city_kannzi',
                    "get_column": 'prefectures_kannzi',
                    "get_data": $('#pref-select option:selected').text(),
                    'data[_Token][key]': $('#UserAddForm').find('input:hidden[name="data[_Token][key]"]').val()
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
                    "get_data": $('#city-select option:selected').text(),
                    'data[_Token][key]': $('#UserAddForm').find('input:hidden[name="data[_Token][key]"]').val()
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
                dataType: 'json',
                data:
                {
                    "zipcode": $('#zipcode').val()
                    // "data[_Token][key]": $('#UserAddForm').find('input:hidden[name="data[_Token][key]"]').val(),
                    // "data[_Token][fields]": $('#users__add').find('input[name="data[_Token][fields]"]').val(),
                    // "data[_Token][unlocked]": $('#users__add').find('input[name="data[_Token][unlocked]"]').val(),
                    // "data[_Token][debug]": $('#users__add').find('input[name="data[_Token][debug]"]').val()
                },
                success: function(json_search_result){
                    //データを受け取っていれば、住所欄に入力する。
                    // var search_result = $.parseJSON(json_search_result);
                    var search_result = json_search_result;
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


    /*** posts/view ***/
    //現在表示している画像が何枚目かを表す
    slideShow();

    /*** address/upload ***/
    var ajaxHandle = null;
    $('#addresses__csv_import #csv-upload').submit(function(){
        $('#result_msg').empty();
        var formdata = new FormData($("#addresses__csv_import #csv-upload")[0]); //選択されたファイルデータを取得する。
        for (let value of formdata.entries()) {
            console.log(value);
        }
        ajaxHandle = $.ajax({ //キャンs流処理を有効にするためにajaxのハンドルを取得する。
            type: "POST",
            url: "csv_import",
            dataType: 'json',
            data: formdata,
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
        $('.loading').show();
        $('#addresses__csv_import #cancel').show();
        lockScreen(lockId);
        return false;
    });

    $('#addresses__csv_update #csv-update').submit(function(){
        $('#result_msg').empty();
        var formdata = new FormData($("#addresses__csv_update #csv-update")[0]); //選択されたファイルデータを取得する。
        ajaxHandle = $.ajax({ //キャンs流処理を有効にするためにajaxのハンドルを取得する。
            type: "POST",
            url: "csv_update",
            dataType: 'json',
            data: formdata,
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



    /***** 共通で使用する *****/
    // モバイル用のデザインを適用する。
    // 画面リサイズ時にデザインを変更する。
    $(window).resize(function(){
        if($(window).width() < 750){
            // 画面サイズが750px以下になったらbootstrapのflexデザインを削除する。
            flexDeleteSidebar('post__view');
            flexDeleteSidebar('post__index');
            flexDeleteSidebar('post__find');
            flexDeleteSlAddress('users__add');
        }
    });

    // 画面読み込み時にモバイルデザインを適用する。
    if($(window).width() < 750){
        // 画面サイズが750px以下になったらbootstrapのflexデザインを削除する。
        flexDeleteSidebar('post__view');
        flexDeleteSidebar('post__index');
        flexDeleteSidebar('post__find');
        flexDeleteSlAddress('users__add');
    }

    $(window).resize(function(){
        if($(window).width() > 749){
            // 画面サイズが750px以下になったらbootstrapのflexデザインを削除する。
            flexAddSidebar('post__view');
            flexAddSidebar('post__index');
            flexAddSidebar('post__find');
            flexAddSlAddress('users__add');
        }
    });

    // 画面読み込み時にモバイルデザインを適用する。
    if($(window).width() > 749){
        // 画面サイズが750px以下になったらbootstrapのflexデザインを削除する。
        flexAddSidebar('post__view');
        flexAddSidebar('post__index');
        flexAddSidebar('post__find');
        flexAddSlAddress('users__add');
    }


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

    // モーダルを表示する。
    setModal();

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

    // モバイル用のメニューを表示する
    function mobileHeaderMenuInit(){
        // メニューのハンバーガーアイコンを押下したときの処理を設定する。
        $('#mobile-header .menu-trigger').off('click');
        $('#mobile-header .menu-trigger').click(function(){
            mobileHeaderMenuTrigger();
        });

        // モバイルメニューのクローズボタンと背景の暗幕をクリックした時の処理を設定する。
        $("#mobile-header-body #mobile-close, #header .back-curtain").off('click');
        $("#mobile-header-body #mobile-close, #header .back-curtain").click(function(){
            mobileHeaderMenuTrigger();
        });

        //モバイルメニューにてアコーディオンメニューで表示できるようにする。
        mobileHeaderAcodion();
    }

    var display_flg = false; // 現在の表示/非表示を表すフラグ
    function mobileHeaderMenuTrigger(){
        if (!display_flg) { // 非表示か
            //表示する。
            $("#mobile-header-body")
            .show()
            .animate({

                "left": ($(window).width()-$("#mobile-header-body").width())+'px'
            });

            $('#header .back-curtain')
            .css({
                'width' : $(window).width(),    // ウィンドウ幅
                'height': $(window).height()    // 同 高さ
            })
            .show();

            // ハンバーグアイコンを×印にする。
            $('#mobile-header .menu-trigger').toggleClass('active');

            display_flg = true;

            // モーダルの背景がスクロールされないように固定する。
            bg_fixed(true, 'mobile-header-body')

        } else {
            // 非表示にする。

            $("#mobile-header-body")
            .animate({

                "left": '100%'
            });

            display_flg = false;

            // ハンバーグアイコンを三本線にする。
            $('#mobile-header .menu-trigger').toggleClass('active');

            // 背景の暗幕を非表示にする。
            $('#header .back-curtain').hide();

            // 背景のスクロール固定を解除する。
            bg_fixed(false);
        }
    }

    function flexDeleteSidebar(view_target){
        $('#'+view_target).find('.col-1').addClass('col-1bad').removeClass('col-1');
        $('#'+view_target).find('#side-bar').removeClass('col-4').css({width: '100%'});
        $('#'+view_target).find('.col-7').addClass('col-7bad').removeClass('col-7');
    }

    function flexAddSidebar(view_target){
        $('#'+view_target).find('.col-1bad').addClass('col-1').removeClass('col-1bad');
        $('#'+view_target).find('#side-bar').addClass('col-4');
        $('#'+view_target).find('.col-7bad').addClass('col-7').removeClass('col-7bad');
    }

    function flexDeleteSlAddress(view_target){
        $('#'+view_target).find('#region-select').removeClass('col-5');
        $('#'+view_target).find('#pref-select').removeClass('col-5');
        $('#'+view_target).find('#city-select').removeClass('col-5');
        $('#'+view_target).find('#town-select').removeClass('col-5');
        $('#'+view_target).find('.col-1').addClass('col-1bad').removeClass('col-1');
    }

    function flexAddSlAddress(view_target){
        $('#'+view_target).find('#select-address-elem').find('#region-select').addClass('col-5');
        $('#'+view_target).find('#select-address-elem').find('#pref-select').addClass('col-5');
        $('#'+view_target).find('#select-address-elem').find('#city-select').addClass('col-5');
        $('#'+view_target).find('#select-address-elem').find('#town-select').addClass('col-5');
        $('#'+view_target).find('#select-address-elem').find('.col-1bad').addClass('col-1').removeClass('col-1bad');
    }

    // モバイルメニューをアコーディオン表示できるようにする。
    function mobileHeaderAcodion(){
        $('#mobile-header-body ul li').off('click');
        $('#mobile-header-body ul span').click(function() {
            $(this).toggleClass('active');
            $(this).next('li').children('ul').slideToggle();
        });
    }

    function slideShow(){
        var page = 0; //現在何枚目の画像を表示しているかを表す。
        var nav_elem_width = 0;
        var mobile_display_flg = false;
        var slide_flg = false;

        //画像の数と最後が何ページ目かを表す数を取得する。
        var image_count = parseInt($(".slide .largeImg img").length);

        // 最後のページを表す数を取得する。
        var lastPage = image_count-1;

        // 画像の数だけナビゲーション領域にdiv要素を作成する。
        for (var i=0; i<image_count; i++) {
            $('.slide .slide-nav .elem').append('<div></div>');
        }
        // 現在位置を表す要素のtop位置に使用する。
        var nav_elem_margin_left = parseInt($('.slide .slide-nav .elem div').css('margin-left'),10);

        // 現在位置を表す要素をwidthを指定するのに使用する。
        var nav_elem_margin = nav_elem_margin_left+parseInt($('.slide .slide-nav .elem div').css('margin-right'),10);

        $('.slide .image').click(function(e) {

            slide_flg = true;
            page = $('.slide .image').index(this);

            //最初に全部のイメージを一旦非表示にします
            $(".slide .largeImg img").css("display","none");
            if ($(window).width() > 800) {
                $(".slide .largeImg").css({'width' : $(window).width() * 0.6});
                $(".slide .slide-nav").css({'width' : $(window).width() * 0.6});
                // 戻るボタンと次へボタンの位置を設定しやすくするための要素を表示する。
                $('#post__view .slide-operation')
                .css({
                    'width' : $(window).width(),    // ウィンドウ幅
                    'height': $(window).height()
                })
                .show();
            } else {
                $(".slide .largeImg").css({'width' : '100%'});
                $(".slide .slide-nav").css({'width' : '96%'});
                $('#post__view .mobile-slide-operation').show();
                mobile_display_flg = true;
            }

            $('.slide-nav').show();

            // ナビゲーションブロックの一つのサイズを算出する。
            nav_elem_width = $('.slide .slide-nav').width() / image_count;

            $('.slide .slide-nav .elem div').css({width: nav_elem_width+'px'});
            $('.slide .slide-nav .pos').css({width: nav_elem_width-nav_elem_margin+'px'});

            // ナビゲーションの現在の画像位置を表す場所を指定する。
            var pos = page == 0 ? nav_elem_margin_left : (nav_elem_width)*(page)+nav_elem_margin_left;
            $('.slide-nav .pos')
            .css({
                "left": pos+'px'
            });

            //初期ページを表示
            $(".slide .largeImg img").eq(page).css({display: "block"});
            $(".slide .largeImg").eq(page).css({display: "block"});

            // ポップアップ画像の後ろに幕を張る
            $('#post__view .back-curtain')
            .css({
                'width' : $(window).width(),    // ウィンドウ幅
                'height': $(window).height()    // 同 高さ
            })
            .show();

            startTimer(); //時間で画像をスライドできるようにする。
        });

        // スライドショー を無効にする処理
        $('.back-curtain, .largeImg, .slide-operation').click(function() {
            slide_flg = false;
            $('.largeImg').fadeOut('slow', function(){
                $('.back-curtain').hide();
                $("#post__view .slide-operation").hide();
                $("#post__view .mobile-slide-operation").hide();
            });
            stopTimer(); //画像を非表示にしたらタイマーイベントも停止させる。
            $('.slide-nav').hide(); //ナビゲーションの非表示
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
            // スライドショー が有効の時だけ以下の処理をする。
            if (slide_flg) {
                $('#post__view .back-curtain')
                .css({
                    'width' : $(window).width(),    // ウィンドウ幅
                    'height': $(window).height()    // 同 高さ
                });

                if ($(window).width() > 800) {
                    $(".slide .largeImg").css({'width' : $(window).width() * 0.6});
                    $(".slide .slide-nav").css({'width' : $(window).width() * 0.6});
                    $('#post__view .slide-operation')
                    .css({
                        'width' : $(window).width(),    // ウィンドウ幅
                        'height': $(window).height()
                    });
                    // スライドショーの戻るボタンと次へボタンを表示する
                    if (mobile_display_flg == true) {
                        // 戻るボタンと次へボタンの位置を設定しやすくするための要素を表示する。
                        $('#post__view .slide-operation').show();
                        $('#post__view .mobile-slide-operation').hide();
                        mobile_display_flg = false;
                    }
                } else if (mobile_display_flg == false) {
                    $(".slide .largeImg").css({'width' : '100%'});
                    $(".slide .slide-nav").css({'width' : '96%'});
                    // スライドショーの戻るボタンと次へボタンを表示する
                    $('#post__view .slide-operation').hide();
                    $('#post__view .mobile-slide-operation').show();
                    mobile_display_flg = true;
                }
                nav_elem_width = $('.slide .slide-nav').width() / image_count;
                $('.slide .slide-nav .elem div').css({width: nav_elem_width+'px'});
                $('.slide .slide-nav .pos').css({width: nav_elem_width-nav_elem_margin+'px'});
                var pos = page == 0 ? nav_elem_margin_left : (nav_elem_width)*(page)+nav_elem_margin_left;
                $('.slide-nav .pos')
                .css({
                    "left": pos+'px'
                });
            }
        });



        //ページ切換用、自作関数作成
        function changePage(){
                                 $(".slide .largeImg img").hide('Transfer');
                                 setTimeout(function(){
                                     $(".slide .largeImg").hide();
                                     $(".slide .largeImg").eq(page).show();
                                     $(".slide .largeImg img").eq(page).show('Transfer');
                                 },700);

                                 var pos = page == 0 ? nav_elem_margin_left : (nav_elem_width)*(page)+nav_elem_margin_left;
                                 $('.slide-nav .pos')
                                 .animate({
                                     "left": pos+'px'
                                 });
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
        $("div#msg-modal div.background").click(function() {
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

    // //ウィンドウの位置をセンターに調整
    // function adjustCenter(target) {
    //     var margin_top = ($(window).height()-$(target).height())/2;
    //     var margin_left = ($(window).width()-$(target).width())/2;
    //     $(target).css({top:margin_top+"px", left:margin_left+"px"});
    // }

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
            bg_fixed(true, 'msg-modal')
        } else {
            $("div#msg-modal").fadeOut(250);
            $("#msg-modal #close-window").hide();
            // 背景固定の解除
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
                elem.off('touchmove');
                elem.on('touchmove', function(e) {
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

    // 画面topに移動させるためのiconを画面top位置によって表示の有無を決める。
    function scrollTopMoveIconToggle(){
        if($(window).scrollTop() > SCROLL_TOP_MOVE_ICON_SHOW_POS){
            $('#scroll-top-move').fadeIn();
        } else {
            $('#scroll-top-move').fadeOut();
        }
    }
});
