<div id="users__send-msg">
    <h1><?php echo __('Send Message'); ?></h1>

    <?php echo $this->Form->create('User', array('id' => 'send-msg-form')); ?>
    <div class="form-group">
        <?php echo $this->Form->input('Message.body', array('label' => false,
                                                        'id'    => 'send-msg-textarea',
                                                        'class' => 'form-control')); ?>
    </div>
    <?php echo $this->Form->hidden('Message.user_id', array('value' => $user_id)); ?>
    <label id='send-msg' class='label-submit btn btn-outline-primary btn-block'  for="label-submit">
        <?php echo __('Send'); ?>
    <?php echo $this->Form->end(array('id' => 'label-submit')); ?>
    </label>
    <label id='close-window' class='label-submit btn btn-outline-secondary'>
        <?php echo __('Close Window'); ?>
    </label>
</div>
