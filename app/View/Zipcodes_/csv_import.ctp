<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-csv/0.8.9/jquery.csv.min.js"></script>
<script type="text/javascript">
$(function(){
    $("#csv-upload").submit(function()
    {
        var fd = new FormData($("#csv-upload")[0]);
        $.ajax({
            type: "POST",
            url: "csv_upload",
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'html',
            success: function(data)
            {
                $('#csv-upload-message').text('アップロードに成功しました。');
                $('#csv-import-label').show();
            },
            error: function(XMLHttpRequest, textStatus, errorThrown)
            {
                $('#csv-upload-message').text('アップロードに失敗しました。');
            }
        });
        return false;
    });

    $('#csv-import-label').click(function(){
        var csvfile = '../files/csv/zip.csv';
        $.get(csvfile, readCsv, 'text');
    });

    // 画像を投稿するfile inputでファイルを選択した時にテキストボックスにファイル名を表示する
    $('.label-file .csv').on('change',function(){
        var file_str = "";
        var file_array = $(this).prop('files');
        $.each(file_array, function(index, element){
          file_str += element.name;
          if( !(index == file_array.length - 1) ){
              file_str += ',';
          }
        });
        $('#file-name-image').val(file_str);
        // アップロードできるようにアップロードボタンを表示する。
        $('#csv-upload-label').show();
    });
});

function readCsv(data) {
    var target = '#csv-body';
    var csv = $.csv.toArrays(data);
    var insert = '';
    $.each(csv[0], function(index, value) {
        insert += "<input type='hidden' name='data[zip][]' value='";
        insert += value;
        insert += "'>";
    });
    // 二重で追加されないように空にしてから追加する
    $(target).empty();
    $(target).append(insert);
    // インポートボタンとsubmitボタン、ボタンを押す行為が２回になることを防ぐため内部でボタンをクリックする。
    $('#import-submit').click();
}
</script>
<div id="zipcodes__csv_import">
    <?php
    echo $this->Form->create( 'Zipcode-upload', array( 'id' => 'csv-upload', 'type'=>'file'));
    ?>
        <h3>csvファイルアップロード</h3>
        <label class="label-file btn btn-outline-primary" for="label-file-image">
            ファイルを選択してください
            <?php
                echo $this->Form->input( 'csv_file', array(   'type' => 'file',
                                                            'id' => 'label-file-image',
                                                            'class' => 'form-control-file csv'));
            ?>
        </label>
        <div class="form-group">
            <input type="text" id="file-name-image" class="form-control" readonly="readonly" placeholder="未選択">
        </div>
    <label id="csv-upload-label" class='label-submit btn btn-outline-primary btn-block' for="upload-submit">
        csvファイルをアップロードする
        <?php echo $this->Form->end(array('id' => 'upload-submit')); ?>
    </label>
    <div id="csv-upload-message">
    </div>

    <?php echo $this->Form->create('Zipcode-import'); ?>
    <div id="csv-body">
    <!-- この中にhidden要素として郵便番号を表示する。 -->
    </div>
    <label id="csv-import-label" class='label-submit btn btn-outline-primary btn-block'>
        csvをインポートする
    </label>
    <?php echo $this->Form->end(array('id' => 'import-submit')); ?>
</div>
