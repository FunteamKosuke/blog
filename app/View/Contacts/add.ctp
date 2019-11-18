<h1><?php echo __('Contact Us'); ?></h1>
<?php echo $this->Form->create('Contact'); ?>
<?php $contact = $this->Session->read('Contact'); ?>
<div class="form-group">
    <h3><?php echo __('Name'); ?></h3>
    <?php echo $this->Form->input('name', array('label' => false, 'class' => 'form-control', 'value' => $contact['name'])); ?>
</div>
<div class="form-group">
    <h3><?php echo __('E-Mail'); ?></h3>
    <?php echo $this->Form->input('email', array('label' => false, 'class' => 'form-control', 'value' => $contact['email'])); ?>
</div>
<div class="form-group">
    <h3><?php echo __('Body'); ?></h3>
    <?php echo $this->Form->input('body', array('label' => false, 'class' => 'form-control', 'value' => $contact['body'])); ?>
</div>
<div class="form-group">
    <?php echo $this->Form->button(__('Confirm'), array(
        'type' => 'submit',
        'name' => 'mode',
        'value' => 'confirm',
        'class' => 'btn btn-outline-primary btn-block'
    )); ?>
</div>
<?php echo $this->Form->end(); ?>
