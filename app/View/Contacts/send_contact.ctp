<div id="contacts__send-contact">
    <h1><?php echo __('Send Contact'); ?></h1>

    <?php echo $this->Form->create('Contact', array('id' => 'send-contact-form')); ?>
    <div class="form-group">
        <?php echo $this->Form->input('Contact.body', array('label' => false,
                                                        'id'    => 'send-msg-textarea',
                                                        'class' => 'form-control')); ?>
    </div>
    <?php echo $this->Form->hidden('Contact.id', array('value' => $contact_id)); ?>
    <label id='send-conatct' class='label-submit btn btn-outline-primary btn-block'  for="label-submit">
        <?php echo __('Send'); ?>
    <?php echo $this->Form->end(array('id' => 'label-submit')); ?>
    </label>
    <label id='close-window' class='label-submit btn btn-outline-secondary'>
        <?php echo __('Close Window'); ?>
    </label>
    <!-- ajaxの実行結果を表示する。 -->
    <div id="ajax-message"></div>
    <div class="loading">
        <div class="dot-spin">
        </div>
        <p><?php echo __('Replying'); ?></p>
    </div>
</div>
