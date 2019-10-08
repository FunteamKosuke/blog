<!-- File: /app/View/Posts/add.ctp -->
<h1><?php echo __('Add Post'); ?></h1>
<?php echo $this->Form->create( 'Post', array( 'type'=>'file', 'enctype' => 'multipart/form-data', 'novalidate' => true)); ?>
<div class="form-group">
    <h3><?php echo __('Title'); ?></h3>
    <?php echo $this->Form->input('title', array('label' => false, 'class' => 'form-control')); ?>
</div>
<div class="form-group">
    <h3><?php echo __('Body'); ?></h3>
    <?php echo $this->Form->input('body', array('label' => false, 'rows' => '3', 'class' => 'form-control')); ?>
</div>
<!-- <?php echo $this->Form->input( 'body', array(
    'type' => 'select',
    'multiple'=> 'checkbox',
    'options' => $bodys)); ?> -->
<div class="form-group">
    <h3><?php echo __('Category'); ?></h3>
    <?php echo $this->Form->input('Category.category_id', array('label' => false, 'class' => 'form-control')); // プルダウンメニュー ?>
</div>
<!-- <div class="form-group">
    <h3>タグ</h3>
    <small>*スペース区切りで入力することで、入力した分だけのタグを設定することができます。</small><br>
    <?php echo $this->Form->input('Tag.tag_str', array('label' => false, 'class' => 'form-control')); ?>
</div> -->
<?php if(!empty ($tagerror)) { ?>
    <div class="tag-error">
<?php } ?>
<?php echo $this->Form->input( 'Tag.Tag', array(
    'type' => 'select',
    'multiple'=> 'checkbox',
    'options' => $tags)); ?>
<?php //タグエラーがあったら表示
if(!empty ($tagerror)) {
    echo '<div class="tag-error-message">';
    print_r($tagerror[0]);
    echo '</div>';
    echo '</div>';
} ?>


<div class="form-group">
    <h3><?php echo __('Add Image'); ?></h3>
    <small>*<?php echo __('Multiple Add Possible'); ?></small><br>
    <label class="label-file btn btn-outline-primary" for="label-file-image">
    <?php echo __('Select Image File'); ?>
    <?php // 画像を投稿する。
    echo $this->Form->input( 'PostImage.files.', array(
                                                    'type' => 'file',
                                                    'multiple',
                                                    'id' => 'label-file-image',
                                                    'class' => 'form-control-file label-file-name')); ?>
    </label>
    <div class="form-group">
        <input type="text" id="file-name-image" class="form-control file-name-input" readonly="readonly" placeholder="<?php echo __('No Select'); ?>">
    </div>
</div>
<div class="form-group">
    <h3><?php echo __('Add Thumbnail'); ?></h3>
    <label class="label-file btn btn-outline-primary" for="label-file-thumbnail">
    <?php echo __('Select Image File'); ?>
    <?php /// サムネイルを設定する。
    echo $this->Form->input('thumbnail', array(
                                                        'type' => 'file',
                                                        'id' => 'label-file-thumbnail',
                                                        'class' => 'form-control-file label-file-name',
                                                        'error' => false)); ?>
    </label>
    <div class="form-group">
        <input type="text" id="file-name-thumbnail" class="form-control file-name-input" readonly="readonly" placeholder="<?php echo __('No Select'); ?>">
    </div>
</div>
<div class="file-error-message">
    <?php echo $this->Form->error('thumbnail'); ?>
</div>
<label class='label-submit btn btn-outline-primary btn-block' for="label-submit">
    <?php echo __('Add'); ?>
<?php echo $this->Form->end(array('id' => 'label-submit')); ?>
</label>
