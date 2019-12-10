<?php
echo $this->Form->create( 'Image', array( 'type'=>'file', 'enctype' => 'multipart/form-data'));
?>
    <h3><?php echo __('Add Image'); ?></h3>
    <small>*<?php echo __('Multiple Add Possible'); ?></small><br>
    <label class="label-file btn btn-outline-primary" for="label-file-image">
        <?php echo __('Select Image File'); ?>
        <?php
            echo $this->Form->input( 'files.', array(   'type' => 'file',
                                                        'multiple',
                                                        'id' => 'label-file-image',
                                                        'class' => 'form-control-file label-file-name',
                                                        'secure' => false));
        ?>
    </label>
    <div class="form-group">
        <input type="text" id="file-name-image" class="form-control file-name-input" readonly="readonly" placeholder="<?php echo __('No Select'); ?>">
    </div>
    <!-- 関連づけたい記事のIDを渡す -->
    <?php echo $this->Form->hidden('Post.post_id', array('value' => $post_id)); ?>
    <!-- アップロード後に推移するViewを渡す -->
    <?php echo $this->Form->hidden('Post.redirect_view', array('value' => $redirect_view)); ?>
<label id="image-upload-label" class='label-submit label-file-button btn btn-outline-primary btn-block' for="label-submit">
    <?php echo __('Add'); ?>
<?php echo $this->Form->end(array('id' => 'label-submit')); ?>
</label>
