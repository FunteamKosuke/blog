<h1><?php echo __('Send Message'); ?></h1>

<?php echo $this->Form->create('User'); ?>
<div class="form-group">
    <?php echo $this->Form->input('Message.body', array('label' => false,
                                                    'class' => 'form-control')); ?>
</div>
<label class='label-submit btn btn-outline-primary btn-block' for="label-submit">
    <?php echo __('Send'); ?>
<?php echo $this->Form->end(array('id' => 'label-submit')); ?>
</label>
