<div class="posts__add">
    <h1><?php echo __('Add Post'); ?></h1>
    <?php echo $this->Form->create( 'Post', array( 'url' => 'add', 'type'=>'file'/*, 'enctype' => 'multipart/form-data'*/)); ?>
    <div class="form-group">
        <h3><?php echo __('Title'); ?></h3>
        <?php echo $this->Form->input('title', array('label' => false, 'class' => 'form-control')); ?>
    </div>
    <div class="form-group">
        <h3><?php echo __('Body'); ?></h3>
        <?php echo $this->Form->input('body', array('label' => false, 'rows' => '3', 'class' => 'form-control')); ?>
    </div>
    <div class="form-group">
        <h3><?php echo __('Category'); ?></h3>
        <?php echo $this->Form->input('Category.category_id', array('label' => false, 'class' => 'form-control')); // プルダウンメニュー ?>
    </div>
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
        print_r($tagerror);
        echo '</div>';
        echo '</div>';
    } ?>


    <div class="form-group">
        <h3><?php echo __('Add Image'); ?></h3>
        <small>*<?php echo __('Multiple Add Possible'); ?></small><br>
        <label class="label-file btn btn-outline-primary" for="label-file-image">
        <?php echo __('Select Image File'); ?>
        <?php // 画像を投稿する。
        echo $this->Form->input( 'Image.files.', array(
                                                        'type' => 'file',
                                                        'multiple',
                                                        'id' => 'label-file-image',
                                                        'class' => 'form-control-file label-file-name',
                                                        'secure' => false //csrf防御を無効にしないと複数ファイルアップロードができない。
                                                    )); ?>
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
        echo $this->Form->input('Thumbnail.thumbnail', array(
                                                            'type' => 'file',
                                                            'id' => 'label-file-thumbnail',
                                                            'class' => 'form-control-file label-file-name',
                                                            'error' => false)); ?>
        </label>
        <div class="form-group">
            <input type="text" id="file-name-thumbnail" class="form-control file-name-input" readonly="readonly" placeholder="<?php echo __('No Select'); ?>">
        </div>
    </div>
    <!-- file inputをdisplay noneしているため、バリデーションメッセージが表示されないため、外だしにする。 -->
    <div class="file-error-message">
        <?php echo $this->Form->error('thumbnail'); ?>
    </div>
    <div class="container-fluid">
        <div class="row">
            <!-- 記事を追加するか下書き保存するか分ける。 -->
            <div id="add" class="col-6 padi_width_5px">
                <?php echo $this->Form->button(__('Add'), array(
                    'div' => false,
                    'class' => 'btn btn-primary btn-block',
                    'name' => 'publish_flg',
                    'value' => '1')); ?>
            </div>
            <div id="draft" class="col-6 padi_width_5px">
                <?php echo $this->Form->button(__('Draft'), array(
                    'div' => false,
                    'class' => 'btn btn-secondary btn-block',
                    'name' => 'publish_flg',
                    'value' => '0')); ?>
            </div>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>

<!-- <div class="form-group">
        <h3>タグ</h3>
        <small>*スペース区切りで入力することで、入力した分だけのタグを設定することができます。</small><br>
        <?php echo $this->Form->input('Tag.tag_str', array('label' => false, 'class' => 'form-control')); ?>
    </div> -->
