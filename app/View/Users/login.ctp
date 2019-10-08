<div class="users form">
<?php echo $this->Flash->render('auth'); ?>
<?php echo $this->Form->create('User'); ?>
    <fieldset>
        <h1><?php echo __('Login'); ?></h1>
        <div class="form-group">
             <h3><?php echo __('User Name'); ?></h3>
            <?php echo $this->Form->input('username', array('label' => false,
                                                            'class' => 'form-control')); ?>
        </div>
        <div class="form-group">
             <h3><?php echo __('Password'); ?></h3>
            <?php echo $this->Form->input('password', array('label' => false,
                                                            'class' => 'form-control')); ?>
        </div>
    </fieldset>
    <label class='label-submit btn btn-outline-primary btn-block' for="label-submit">
        <?php echo __('Login'); ?>
    <?php echo $this->Form->end(array('id' => 'label-submit')); ?>
    </label>
</div>
