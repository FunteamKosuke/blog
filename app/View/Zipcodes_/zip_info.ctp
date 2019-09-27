<div id="zipcodes__zip_info">
    <?php echo $this->Form->create( 'Zipcode'); ?>
    <div class="form-group">
        <h3>郵便番号</h3>
        <?php echo $this->Form->input('Zipcode.zipcode_id', array('label' => false, 'id' => 'zip-select', 'class' => 'form-control')); // プルダウンメニュー ?>
    </div>
    <div class="form-group">
        <h3>住所</h3>
        <?php echo $this->Form->input('address', array('label' => false, 'id' => 'address', 'class' => 'form-control', 'readonly' => "readonly")); ?>
    </div>
    <?php echo $this->Form->end(array('id' => 'zip-info-submit')); ?>
</div>
<script type="text/javascript">
$(function(){
    // ［検索］ボタンクリックで郵便番号検索を実行
    $('#zip-select').change(function() {
        if ($('#zip-select option:selected').text() == '選択してください') {
            $('#address').val('');
        } else {
            $.getJSON('http://zipcloud.ibsnet.co.jp/api/search?callback=?',
              {
                // value値ではなく、表示名を設定する
                zipcode: $('#zip-select option:selected').text()
              }
            )
            // 結果を取得したら…
            .done(function(data) {
              // 中身が空でなければ、その値を［住所］欄に反映
              if (data.results) {
                var result = data.results[0];
                $('#address').val(result.address1 + result.address2 + result.address3);
              // 中身が空の場合は、エラーメッセージを反映
              } else {
                $('#address').val('該当する住所が存在しません。');
              }
            });
        }
    });
});
</script>
