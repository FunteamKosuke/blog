<?php
echo $this->Form->create('Image', array('type' => 'file'));
?>
<h3><?php echo __('Replacement Image'); ?></h3>
<label class="label-file btn btn-outline-primary" for="label-file-image">
    <?php echo __('Select Image File'); ?>
    <?php
        echo $this->Form->input( 'image', array(   'type' => 'file',
                                                    'id' => 'label-file-image',
                                                    'class' => 'form-control-file label-file-name',
                                                    'error' => false));
    ?>
</label>
<div class="form-group">
    <input type="text" id="file-name-image" class="form-control file-name-input" readonly="readonly" placeholder="未選択">
</div>
<div class="file-error-message">
    <?php echo $this->Form->error('image'); ?>
</div>
<label id="image-edit-label" class='label-submit label-file-button btn btn-outline-primary btn-block' for="label-submit">
    <?php echo __('Replacement'); ?>
<?php echo $this->Form->end(array('id' => 'label-submit')); ?>
</label>
