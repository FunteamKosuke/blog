<!-- File: /app/View/Categories/add.ctp -->

<h1><?php echo __('Add Category'); ?></h1>
<?php echo $this->Form->create('Category'); ?>
<div class="form-group">
    <h3><?php echo __('Category Name'); ?></h3>
    <?php echo $this->Form->input('name', array('label' => false, 'class' => 'form-control')); ?>
</div>
<label class='label-submit btn btn-outline-primary btn-block' for="label-submit">
    <?php echo __('Add'); ?>
<?php echo $this->Form->end(array('id' => 'label-submit')); ?>
</label>
