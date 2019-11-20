<h1><?php echo __('Add User Confirm'); ?></h1>
<p><?php echo __('Check whether the following contents are correct.'); ?></p>
<h3><?php echo __('Profile Image'); ?></h3>
<?php $user = $this->Session->read('User'); ?>
<p><?php echo h($user['User']['profile_image']['name']); ?></p>


<h3><?php echo __('Huri Hira'); ?></h3>
<p><?php echo h($user['User']['huri_hira_sei'].' '.$user['User']['huri_hira_mei']); ?></p>
<h3><?php echo __('Huri Kata'); ?></h3>
<p><?php echo h($user['User']['huri_kata_sei'].' '.$user['User']['huri_kata_mei']); ?></p>
<h3><?php echo __('Name'); ?></h3>
<p><?php echo h($user['User']['name_sei'].' '.$user['User']['name_mei']); ?></p>
<h3><?php echo __('User Name'); ?></h3>
<p><?php echo h($user['User']['username']); ?></p>
<h3><?php echo __('E-Mail'); ?></h3>
<p><?php echo h($user['User']['email']); ?></p>
<h3><?php echo __('Zipcode'); ?></h3>
<p><?php echo h($user['User']['zipcode']); ?></p>
<h3><?php echo __('Address'); ?></h3>
<p><?php echo h($user['User']['address']); ?></p>
<h3><?php echo __('Select Address'); ?></h3>
<p><?php echo h($user['User']['sl_address']); ?></p>
<h3><?php echo __('User Authority'); ?></h3>
<p><?php echo h($user['User']['role']); ?></p>
<?php echo $this->Form->create('User', array('url'=>$this->Html->url(array('controller'=>'users','action'=>'add')))); ?>
<div class="container-fluid">
    <div class="row">
        <!-- 修正 -->
        <div id="correct" class="col-6 padi_width_5px">
            <?php echo $this->Form->button(__('Correct'), array(
                'type' => 'submit',
                'name' => 'mode',
                'value' => 'correct',
                'class' => 'btn btn-outline-primary btn-block'
            )); ?>
        </div>
        <!-- 実行 -->
        <div id="exec" class="col-6 padi_width_5px">
            <?php echo $this->Form->button(__('Regist'), array(
                'type' => 'submit',
                'name' => 'mode',
                'value' => 'exec',
                'class' => 'btn btn-outline-primary btn-block'
            )); ?>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>
