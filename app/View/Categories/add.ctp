<!-- File: /app/View/Categories/add.ctp -->

<h1>カテゴリーの追加</h1>
<?php echo $this->Form->create('Category'); ?>
<div class="form-group">
    <h3>カテゴリー名</h3>
    <?php echo $this->Form->input('name', array('label' => false, 'class' => 'form-control')); ?>
</div>
<label class='label-submit btn btn-outline-primary btn-block' for="label-submit">
    カテゴリーを追加する
<?php echo $this->Form->end(array('id' => 'label-submit')); ?>
</label>
