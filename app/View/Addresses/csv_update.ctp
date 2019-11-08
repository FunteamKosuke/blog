<div id="addresses__csv_update">
    <?php
    echo $this->Form->create( 'Address', array( 'id' => 'csv-update', 'type'=>'file', 'accept' => "text/csv"));
    ?>
    <h3>住所情報アップデート</h3>
    <label class="label-file btn btn-outline-primary" for="label-file-image">
        csvファイルを選択してください
        <?php
            echo $this->Form->input( 'csv_file', array(   'type' => 'file',
                                                        'id' => 'label-file-image',
                                                        'class' => 'form-control-file label-file-name'));
        ?>
    </label>
    <div class="form-group">
        <input type="text" id="file-name-image" class="form-control file-name-input" readonly="readonly" placeholder="未選択">
    </div>
    <div class="container-fluid">
        <div class="form-group row">
            <div class="button col-6">
                <label id="csv-update-label" class='label-submit label-file-button btn btn-outline-primary btn-block' for="update-submit">
                    csvファイルをアップデートする
                    <?php echo $this->Form->end(array('id' => 'update-submit')); ?>
                </label>
            </div>
            <div class="button col-6">
                <label id="cancel" class="btn btn-outline-danger btn-block">キャンセル</label>
            </div>

        </div>
    </div>
    <div class="loading">
        <div class="dot-spin">
        </div>
        <p><?php echo __('Updating'); ?></p>
    </div>
    <div id="result_msg">
    </div>
</div>
