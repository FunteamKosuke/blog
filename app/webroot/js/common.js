$(function(){
    /*** ヘッダー ***/
    // 検索フォームをクリックしたら表示する
    $('#search img').on('click',function(){
        $('.search_toggle').toggle();
    });

    /*** コントローラー固有のjsを以下に記述する ***/
    /*** users/add **/
    // 町域を選択するセレクトボックス が選択された場合に使用する、都道府県と市区町村を格納するための変数
    var pref_city_str = '';
    // ［検索］ボタンクリックで郵便番号検索を実行
    $('#zipcode').keyup(function() {
        // 郵便番号入力欄に7桁の数字が入力されたら検索を開始する。
        if(0 <= $(this).val().search(/\d{7}/)){
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
                        //町域選択欄を最初に空にする
                        $('#twon_msg').empty();
                        $('#town-select').empty();

                        if (search_result.length == 1) { // 町域が一意な場合
                            var address = search_result[0]['Address']['prefectures_kannzi'] +
                                          search_result[0]['Address']['city_kannzi'] +
                                          search_result[0]['Address']['town_area_kannzi'];

                        } else {// 郵便番号に複数の町域が含まれている場合の対応
                            // 都道府県名と市区町村までは同じなので通常通り入力欄に入力する。
                            var address = search_result[0]['Address']['prefectures_kannzi'] +
                                          search_result[0]['Address']['city_kannzi'];
                            // セレクトボックス変更時に使用する
                            pref_city_str = address;
                            // 町域はセレクトボックスを作成して選択された町域が入力欄に追加されるようにする。
                            var select_str = '<option>選択してください</option>'
                            $.each(search_result, function(index, elem){
                                select_str += '<option>' + elem['Address']['town_area_kannzi'] + '</option>'
                            });
                            // 空にしてから追加
                            var town_msg = '*郵便番号に複数の町域が含まれています。該当の町域を選択してください。';

                            $('#twon_msg').text(town_msg).show();
                            $('#town-select').append(select_str).show();
                        }
                        $('#address').val(address);
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
    $('#town-select').change(function() {
        if (!($('#town-select option:selected').text() == '選択してください')) {
            var address_str = pref_city_str + $('#town-select option:selected').text();
            $('#address').val(address_str);
        }
    });

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

    /*** addresses/csv_upload ***/
    $("#csv-upload").submit(function(){
        $('.loading').show();
        lockScreen(lockId);
    });

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
});
