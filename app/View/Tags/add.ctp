<!-- File: /app/View/Tags/add.ctp -->

<h1>タグの追加</h1>
<?php echo $this->Form->create('Tag'); ?>
<div class="form-group">
    <h3>タグ名</h3>
    <?php echo $this->Form->input('name', array('label' => false, 'class' => 'form-control')); ?>
</div>
<label class='label-submit btn btn-outline-primary btn-block' for="label-submit">
    タグを追加する
<?php echo $this->Form->end(array('id' => 'label-submit')); ?>
</label>
