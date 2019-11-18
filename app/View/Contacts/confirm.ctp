<h1>Contact Confirm</h1>
<p>以下の内容に誤りがないか確認してください。</p>
<?php $contact = $this->Session->read('Contact'); ?>
<h3><?php echo __('Name'); ?></h3>
<p><?php echo h($contact['name']); ?></p>
<h3><?php echo __('E-Mail'); ?></h3>
<p><?php echo h($contact['email']); ?></p>
<h3><?php echo __('Body'); ?></h3>
<p><?php echo h($contact['body']); ?></p>
<?php echo $this->Form->create('Contact', array('url'=>$this->Html->url(array('controller'=>'contacts','action'=>'add')))); ?>
<div class="container-fluid">
    <div class="row">
        <!-- 修正か実行か -->
        <div id="correct" class="col-6 padi_width_5px">
            <?php echo $this->Form->button(__('Correct'), array(
                'type' => 'submit',
                'name' => 'mode',
                'value' => 'correct',
                'class' => 'btn btn-outline-primary btn-block'
            )); ?>
        </div>
        <div id="exec" class="col-6 padi_width_5px">
            <?php echo $this->Form->button(__('Send'), array(
                'type' => 'submit',
                'name' => 'mode',
                'value' => 'exec',
                'class' => 'btn btn-outline-primary btn-block'
            )); ?>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>
