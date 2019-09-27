<?php
echo $this->Form->create('Image', array('type' => 'file'));
?>
<h3>画像の差し替えをする</h3>
<label class="label-file btn btn-outline-primary" for="label-file-image">
    ファイルを選択してください
    <?php
        echo $this->Form->input( 'image', array(   'type' => 'file',
                                                    'id' => 'label-file-image',
                                                    'class' => 'form-control-file label-file-name'));
    ?>
</label>
<div class="form-group">
    <input type="text" id="file-name-image" class="form-control file-name-input" readonly="readonly" placeholder="未選択">
</div>

<label id="image-edit-label" class='label-submit label-file-button btn btn-outline-primary btn-block' for="label-submit">
    画像を差し替える
<?php echo $this->Form->end(array('id' => 'label-submit')); ?>
</label>
