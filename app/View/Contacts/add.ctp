<h1><?php echo __('Contact Us'); ?></h1>
<?php echo $this->Form->create('Contact'); ?>
<div class="form-group">
    <h3><?php echo __('Name'); ?></h3>
    <?php echo $this->Form->input('name', array('label' => false, 'class' => 'form-control')); ?>
</div>
<div class="form-group">
    <h3><?php echo __('E-Mail'); ?></h3>
    <?php echo $this->Form->input('email', array('label' => false, 'class' => 'form-control')); ?>
</div>
<div class="form-group">
    <h3><?php echo __('Body'); ?></h3>
    <?php echo $this->Form->input('body', array('label' => false, 'class' => 'form-control')); ?>
</div>
<label class='label-submit btn btn-outline-primary btn-block' for="label-submit">
    <?php echo __('Send'); ?>
<?php echo $this->Form->end(array('id' => 'label-submit')); ?>
</label>
