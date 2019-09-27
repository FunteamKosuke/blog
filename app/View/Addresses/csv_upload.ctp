<div id="addresses__csv_upload">
    <?php
    echo $this->Form->create( 'Address', array( 'id' => 'csv-upload', 'type'=>'file', 'accept' => "text/csv"));
    ?>
        <h3>csvインポート</h3>
        <label class="label-file btn btn-outline-primary" for="label-file-image">
            ファイルを選択してください
            <?php
                echo $this->Form->input( 'csv_file', array(   'type' => 'file',
                                                            'id' => 'label-file-image',
                                                            'class' => 'form-control-file label-file-name'));
            ?>
        </label>
        <div class="form-group">
            <input type="text" id="file-name-image" class="form-control file-name-input" readonly="readonly" placeholder="未選択">
        </div>
    <label id="csv-upload-label" class='label-submit label-file-button btn btn-outline-primary btn-block' for="upload-submit">
        csvファイルをインポートする
        <?php echo $this->Form->end(array('id' => 'upload-submit')); ?>
    </label>
    <div class="loading">
        <div class="dot-spin">
        </div>
        <p>インポート中</p>
    </div>
</div>
