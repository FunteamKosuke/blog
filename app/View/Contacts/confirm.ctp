<h1>Contact Confirm</h1>
<p>以下の内容に誤りがないか確認してください。</p>
<h3><?php echo __('Name'); ?></h3>
<p><?php echo h($data['name']); ?></p>
<h3><?php echo __('E-Mail'); ?></h3>
<p><?php echo h($data['email']); ?></p>
<h3><?php echo __('Body'); ?></h3>
<p><?php echo h($data['body']); ?></p>
<?php $this->log($data); ?>
<?php echo $this->Form->create('Contact', array('url'=>$this->Html->url(array('controller'=>'contacts','action'=>'add'))));

foreach ($data as $name => $val) {
    echo $this->Form->hidden($name, array('value' => $val));
} ?>
<div class="container-fluid">
    <div class="row">
        <!-- 記事を追加するか下書き保存するか分ける。 -->
        <div id="correct" class="col-6 padi_width_5px">
            <?php echo $this->Form->button(__('Correct'), array(
                'div' => false,
                'type' => 'button',
                'class' => 'btn btn-outline-info btn-block',
                'onclick' => 'history.back()')); ?>
        </div>
        <div id="send" class="col-6 padi_width_5px">
            <label class='label-submit btn btn-outline-primary btn-block' for="label-submit">
                <?php echo __('Send'); ?>
            <?php echo $this->Form->end(array('id' => 'label-submit')); ?>
            </label>
        </div>
    </div>
</div>
