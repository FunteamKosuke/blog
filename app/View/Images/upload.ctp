<?php
echo $this->Form->create( 'Image', array( 'type'=>'file', 'enctype' => 'multipart/form-data'));
?>
    <h3>画像を投稿する</h3>
    <small>*複数投稿可</small><br>
    <label class="label-file btn btn-outline-primary" for="label-file-image">
        ファイルを選択してください
        <?php
            echo $this->Form->input( 'files.', array(   'type' => 'file',
                                                        'multiple',
                                                        'id' => 'label-file-image',
                                                        'class' => 'form-control-file label-file-name'));
        ?>
    </label>
    <div class="form-group">
        <input type="text" id="file-name-image" class="form-control file-name-input" readonly="readonly" placeholder="未選択">
    </div>
    <!-- 関連づけたい記事のIDを渡す -->
    <?php echo $this->Form->hidden('Post.post_id', array('value' => $post_id)); ?>
<label id="image-upload-label" class='label-submit label-file-button btn btn-outline-primary btn-block' for="label-submit">
    画像を追加する
<?php echo $this->Form->end(array('id' => 'label-submit')); ?>
</label>
