<!-- File: /app/View/Posts/edit.ctp -->

<h1>Edit Post</h1>
<?php
    echo $this->Form->create('Post');
?>
<div class="form-group">
    <?php echo $this->Form->input('title', array('class' => 'form-control')); ?>
</div>
<div class="form-group">
    <?php echo $this->Form->input('body', array('rows' => '3', 'class' => 'form-control')); ?>
</div>
<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
<label class='label-submit btn btn-outline-primary btn-block' for="label-submit">
    記事を編集する
<?php echo $this->Form->end(array('id' => 'label-submit')); ?>
</label>
