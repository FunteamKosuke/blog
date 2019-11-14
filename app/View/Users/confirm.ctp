<h1><?php echo __('Add User Confirm'); ?></h1>
<p><?php echo __('Check whether the following contents are correct.'); ?></p>
<h3><?php echo __('Profile Image'); ?></h3>
<p><?php echo h($data['profile_image']['name']); ?></p>
<h3><?php echo __('E-Mail'); ?></h3>
<p><?php echo h($data['email']); ?></p>
<h3><?php echo __('Zipcode'); ?></h3>
<p><?php echo h($data['zipcode']); ?></p>
<h3><?php echo __('Address'); ?></h3>
<p><?php echo h($data['address']); ?></p>
<h3><?php echo __('Select Address'); ?></h3>
<p><?php echo h($data['sl_address']); ?></p>
<h3><?php echo __('User Authority'); ?></h3>
<p><?php echo h($data['role']); ?></p>
<?php echo $this->Form->create('User', array('url'=>$this->Html->url(array('controller'=>'users','action'=>'add'))));

foreach ($data as $name => $val) {
    if ($name === 'profile_image') {
        // $this->log($val);
        $image = $val;
        // $this->log($image);
        foreach ($image as $name => $value) {
            echo $this->Form->hidden('profile_image'.$name, array('value' => $value));
        }
    } else {
        echo $this->Form->hidden($name, array('value' => $val));
    }
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
